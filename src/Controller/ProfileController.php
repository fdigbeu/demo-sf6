<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/p/{page<\d+>?1}', name: 'app_profile')]
    public function index(ManagerRegistry $registry, $page): Response
    {
        $nbByPage = 5;
        $profiles = $registry->getRepository(Profile::class)->findBy([], ["id" => "DESC"], $nbByPage, ($page - 1) * $nbByPage);
        $totalProfiles = count($registry->getRepository(Profile::class)->findAll());
        $maxPage = $totalProfiles/$nbByPage;
        $floor = floor($maxPage);
        if($maxPage > $floor){$maxPage = $floor + 1;}
        else{$maxPage = $floor;}
        //--
        $min = 1;
        if($page-2 >= 1){ $min = $page-2; }
        if($min+4 <= $maxPage){ $max = $min+4; }else{ 
            $max = $maxPage; 
            if($maxPage-4 >= 1){ $min = $maxPage-4; }
        }
        $tabPages = [];
        for($i=$min; $i<=$max; $i++){
            $tabPages[] = $i;
        }
        //--
        return $this->render('profile/index.html.twig', [
            'profiles' => $profiles,
            'maxPage' => $maxPage,
            'page' => $page,
            'tabPages' => $tabPages,
            'profile' => null,
        ]);
    }


    #[Route('/detail/{id<\d+>}', name: 'app_profile_detail')]
    public function detail(ManagerRegistry $registry, Profile $profile = null): Response
    {
        if(!$profile){
            $this->addFlash("info", "Ce profile n'existe pas.");
            return $this->redirectToRoute('app_profile');
        }
        return $this->render('profile/index.html.twig', [
            'profiles' => null,
            'profile' => $profile,
        ]);
    }

    #[Route('/edit/{id<\d*>?0}', name: 'app_profile_edit')]
    public function edit(Request $request, ManagerRegistry $registry, Profile $profile = null): Response
    {
        $form = $this->createForm(ProfileType::class, $profile);
        $msgAction = "modifié";
        if(!$profile){
            $profile = new Profile();
            $msgAction = "ajouté";
        }
        $form->remove('infosUser');
        $form->remove('createdAt');
        $form->remove('updatedAt');
        $form->handleRequest($request);
        
        $entityManager = $registry->getManager();
        if($form->isSubmitted()){
            if($form->isValid()){
                $entityManager->persist($profile);
                $entityManager->flush();
                $this->addFlash('info', 'Vos données ont été '.$msgAction.'es avec succès !');
                return $this->redirectToRoute('app_profile');
            }
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'msgAction' => $msgAction,
        ]);
    }

    #[Route('/supprimer/{id<\d+>}', name: 'app_profile_delete')]
    public function delete(ManagerRegistry $manager,  Profile $profile = null): RedirectResponse
    {
        if(!$profile){
            $this->addFlash("danger", "Ce profile n'existe pas dans la base !");
        }
        else{
            $this->addFlash("danger", "{$profile->getSocialnetwork()} a été supprimé avec succès !");
        }
        $jobEntity = $manager->getManager();
        $jobEntity->remove($profile);
        $jobEntity->flush();
        return $this->redirectToRoute('app_profile');
    }
}
