<?php

namespace App\Controller;

use App\Entity\Hobby;
use App\Form\HobbyType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/hobby')]
class HobbyController extends AbstractController
{
    #[Route('/p/{page<\d+>?1}', name: 'app_hobby')]
    public function index(ManagerRegistry $registry, $page): Response
    {
        $nbByPage = 5;
        $hobbies = $registry->getRepository(Hobby::class)->findBy([], ["id" => "DESC"], $nbByPage, ($page - 1) * $nbByPage);
        $totalHobbies = count($registry->getRepository(Hobby::class)->findAll());
        $maxPage = $totalHobbies/$nbByPage;
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
        return $this->render('hobby/index.html.twig', [
            'hobbies' => $hobbies,
            'maxPage' => $maxPage,
            'page' => $page,
            'tabPages' => $tabPages,
            'hobby' => null,
        ]);
    }


    #[Route('/detail/{id<\d+>}', name: 'app_hobby_detail')]
    public function detail(Hobby $hobby = null): Response
    {
        if(!$hobby){
            $this->addFlash("info", "Ce hobby n'existe pas.");
            return $this->redirectToRoute('app_hobby');
        }
        return $this->render('hobby/index.html.twig', [
            'hobbies' => null,
            'hobby' => $hobby,
        ]);
    }

    #[Route('/edit/{id<\d*>?0}', name: 'app_hobby_edit')]
    public function edit(Request $request, ManagerRegistry $registry, Hobby $hobby = null): Response
    {
        $msgAction = "modifié";
        if(!$hobby){
            $hobby = new Hobby();
            $msgAction = "ajouté";
        }
        $form = $this->createForm(HobbyType::class, $hobby);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        $form->remove('infosUsers');
        $form->handleRequest($request);
        
        $entityManager = $registry->getManager();
        if($form->isSubmitted()){
            if($form->isValid()){
                $entityManager->persist($hobby);
                $entityManager->flush();
                $this->addFlash('info', 'Vos données ont été '.$msgAction.'es avec succès !');
                return $this->redirectToRoute('app_hobby');
            }
        }

        return $this->render('hobby/edit.html.twig', [
            'form' => $form->createView(),
            'msgAction' => $msgAction,
        ]);
    }

    #[Route('/supprimer/{id<\d+>}', name: 'app_hobby_delete')]
    public function delete(ManagerRegistry $manager,  Hobby $hobby = null): RedirectResponse
    {
        if(!$hobby){
            $this->addFlash("danger", "Ce hobby n'existe pas dans la base !");
        }
        else{
            $this->addFlash("danger", "{$hobby->getDesignation()} a été supprimé avec succès !");
        }
        $hobbyEntity = $manager->getManager();
        $hobbyEntity->remove($hobby);
        $hobbyEntity->flush();
        return $this->redirectToRoute('app_hobby');
    }
}
