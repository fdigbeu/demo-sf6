<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use App\Repository\JobRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/job')]
class JobController extends AbstractController
{
    #[Route('/p/{page<\d+>?1}', name: 'app_job')]
    public function index(ManagerRegistry $registry, $page): Response
    {
        $nbByPage = 5;
        $jobs = $registry->getRepository(Job::class)->findBy([], ["id" => "DESC"], $nbByPage, ($page - 1) * $nbByPage);
        $totalJobs = count($registry->getRepository(Job::class)->findAll());
        $maxPage = $totalJobs/$nbByPage;
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
        return $this->render('job/index.html.twig', [
            'jobs' => $jobs,
            'maxPage' => $maxPage,
            'page' => $page,
            'tabPages' => $tabPages,
            'job' => null,
        ]);
    }


    #[Route('/detail/{id<\d+>}', name: 'app_job_detail')]
    public function detail(ManagerRegistry $registry, Job $job = null): Response
    {
        if(!$job){
            $this->addFlash("info", "Ce job n'existe pas.");
            return $this->redirectToRoute('app_job');
        }
        return $this->render('job/index.html.twig', [
            'jobs' => null,
            'job' => $job,
        ]);
    }

    #[Route('/edit/{id<\d*>?0}', name: 'app_job_edit')]
    public function edit(Request $request, ManagerRegistry $registry, Job $job = null): Response
    {
        $msgAction = "modifié";
        if(!$job){
            $job = new Job();
            $msgAction = "ajouté";
        }
        $form = $this->createForm(JobType::class, $job);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        $form->handleRequest($request);
        
        $entityManager = $registry->getManager();
        if($form->isSubmitted()){
            if($form->isValid()){
                $entityManager->persist($job);
                $entityManager->flush();
                $this->addFlash('info', 'Vos données ont été '.$msgAction.'es avec succès !');
                return $this->redirectToRoute('app_job');
            }
        }

        return $this->render('job/edit.html.twig', [
            'form' => $form->createView(),
            'msgAction' => $msgAction,
        ]);
    }

    #[Route('/supprimer/{id<\d+>}', name: 'app_job_delete')]
    public function delete(ManagerRegistry $manager,  Job $job = null): RedirectResponse
    {
        if(!$job){
            $this->addFlash("danger", "Ce job n'existe pas dans la base !");
        }
        else{
            $this->addFlash("danger", "{$job->getDesignation()} a été supprimé avec succès !");
        }
        $jobEntity = $manager->getManager();
        $jobEntity->remove($job);
        $jobEntity->flush();
        return $this->redirectToRoute('app_job');
    }
}
