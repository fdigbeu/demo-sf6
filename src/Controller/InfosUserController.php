<?php

namespace App\Controller;

use App\Entity\InfosUser;
use App\Form\InfosUserType;
use App\Repository\InfosUserRepository;
use App\Service\UploadService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/infosuser')]
class InfosUserController extends AbstractController
{
    #[Route('/list', name: 'app_infos_user')]
    public function list(InfosUserRepository $repository): Response
    {
        $infosUsers = $repository->findAllInfosUser();
        return $this->render('infos_user/index.html.twig', [
            'infosUsers' => $infosUsers,
            'infosUser' => null,
        ]);
    }

    #[Route('/loaddata', name: 'app_infos_user_loaddata')]
    public function loadDatabase(ManagerRegistry $manager): RedirectResponse
    {
        $lastname = [
            "ABRY", "ACHARD", "ADAM", "ADENOT", "ADOBATI", "AGNIEL", "AJOUX", "ALAMERCERY", "ALBAN", "ALBERT", 
            "ALCARAZ", "ALEX", "ALIX", "BADEZ", "BADIN", "BADOUX", "BAG", "BAGNE", "BAGNON", "BAILLET", 
            "CHAMONAL", "CHAMPANAY", "CHAMPEL", "CHAMPIER", "CHANLOY", "CHANU", "CHAPELAND", "CHAPOLARD", "CHAPPUIS", "CHARBON"
        ];
        $firstname = [
            "BALLAND", "BALLET", "BALLY", "BALME", "BANAND", "BANDET", "BARAQUE", "BARBARIN", "BARBE", "BARBERAT", 
            "CABOT", "CABUT", "CACHET", "CADET", "CADOT", "CADOUX", "CADOZ", "CAGNIN", "CAILLAT", "CAILLER", 
            "DIALLO", "DIAS", "DIDIENNE", "DIENNET", "DIOCHON", "DOTHAL", "DOYONNAS", "FAUCHER", "FAUSSURIER", "FAVRE"
        ];

        $entityManager = $manager->getManager();
        //--
        if(count($lastname) == count($firstname)){
            for($i=0; $i<count($lastname); $i++){
                $infosUser = new InfosUser();
                $infosUser->setFirstname($firstname[$i]);
                $infosUser->setLastname(ucfirst($lastname[$i]));
                $infosUser->setAge(rand(18, 65));
                $entityManager->persist($infosUser);
            }
            $entityManager->flush();
            //--
            $this->addFlash("success", "Les données ont été ajoutées avec succès!");
        }
        //--
        return $this->redirectToRoute('app_infos_user');
    }

    #[Route('/list/findby', name: 'app_infos_user_findby')]
    public function findBy(ManagerRegistry $manager): Response
    {
        $infosUsers = $manager->getRepository(InfosUser::class)->findBy([], ["id" => "DESC"]);
        return $this->render('infos_user/index.html.twig', [
            'infosUsers' => $infosUsers,
            'infosUser' => null,
        ]);
    }

    #[Route('/list/pagination/{page<\d+>?1}', name: 'app_infos_user_findby_pagination')]
    public function pagination(ManagerRegistry $manager, $page): Response
    {
        $nbByPage = 5;
        $infosUsers = $manager->getRepository(InfosUser::class)->findBy([], ["id" => "DESC"], $nbByPage, ($page - 1) * $nbByPage);
        $totalUsers = count($manager->getRepository(InfosUser::class)->findAll());
        $maxPage = $totalUsers/$nbByPage;
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
        return $this->render('infos_user/pagination.html.twig', [
            'infosUsers' => $infosUsers,
            'maxPage' => $maxPage,
            'page' => $page,
            'tabPages' => $tabPages,
            //'nbByPage' => $nbByPage,
            //'totalUsers' => $totalUsers,
        ]);
    }

    #[Route('/list/findall', name: 'app_infos_user_findall')]
    public function findAll(ManagerRegistry $manager): Response
    {
        $infosUsers = $manager->getRepository(InfosUser::class)->findAll();
        return $this->render('infos_user/index.html.twig', [
            'infosUsers' => $infosUsers,
            'infosUser' => null,
        ]);
    }

    
    #[Route('/edit/{id<\d*>?0}', name: 'app_infos_user_edit')]
    public function add(Request $request, InfosUser $infosUser=null, ManagerRegistry $manager, UploadService $upload): Response
    {
        $msgAction = "modifié";
        if(!$infosUser){
            $infosUser = new InfosUser();
            $msgAction = "ajouté";
        }

        $form = $this->createForm(InfosUserType::class, $infosUser);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        $form->handleRequest($request);
        
        $entityManager = $manager->getManager();
        if ($form->isSubmitted()) { 
            if($form->isValid()){
                $photoFile = $form->get('photo')->getData();
                if ($photoFile) {
                    $fileDirectory = $this->getParameter('photos_directory');
                    $infosUser->setPhoto($upload->upload($photoFile, $fileDirectory));
                }

                $entityManager->persist($infosUser);
                $entityManager->flush();
                $this->addFlash("success", "{$infosUser->getFirstname()} {$infosUser->getLastname()} a été $msgAction avec succès !");
                return $this->redirectToRoute('app_infos_user_findby_pagination'); 
            }
            else{
                $this->addFlash("danger", "Une erreur s'est produite pendant l'ajout de l'utilisateur.");
            }
        }

        return $this->render('infos_user/edit.html.twig', [
            'form' => $form->createView(),
            'msgAction' => $msgAction,
        ]);
    }

    #[Route('/detail/{id<\d+>}', name: 'app_infos_user_detail')]
    public function detail(InfosUser $infosUser=null): Response
    {
        if(!$infosUser){
            $this->addFlash("danger", "Ce utilisateur n'existe pas dans la base.");
        }
        return $this->render('infos_user/index.html.twig', [
            'infosUser' => $infosUser,
            'infosUsers' => null,
        ]);
    }

    #[Route('/supprimer/{id<\d+>}', name: 'app_infos_user_delete')]
    public function delete(ManagerRegistry $manager, InfosUser $infosUser=null): RedirectResponse
    {
        if(!$infosUser){
            $this->addFlash("danger", "Ce utilisateur n'existe pas dans la base !");
        }
        else{
            $this->addFlash("danger", "{$infosUser->getFirstname()} {$infosUser->getLastname()} a été supprimé avec succès !");
        }
        $infosUserEntity = $manager->getManager();
        $infosUserEntity->remove($infosUser);
        $infosUserEntity->flush();
        return $this->redirectToRoute('app_infos_user_findby_pagination');
    }
}
