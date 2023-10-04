<?php

namespace App\Controller;

use App\Entity\Job;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/job')]
class JobController extends AbstractController
{
    #[Route('/', name: 'app_job')]
    public function index(): Response
    {
        return $this->render('job/index.html.twig', [
            'controller_name' => 'JobController',
        ]);
    }

    #[Route('/loaddata', name: 'app_job_loaddata')]
    public function loadData(ManagerRegistry $registry): RedirectResponse
    {
        $jobs = [
            "Data scientist", "Statisticien", "Analyse cyber-sécurité", "Médecin ORL", "Echographiste", 
            "Mathématicien", "Ingénieur logiciel", "Analyste informatique", "Pathologiste du discours/langage", "Directeur des ressources humaines", 
            "Hygiéniste dentaire"
        ];

        $entityManager = $registry->getManager();
        for($i=0; $i<count($jobs); $i++){
            $job = new Job();
            $job->setDesignation($jobs[$i]);
            $entityManager->persist($job);
        }
        $entityManager->flush();
        $this->addFlash('info', 'Vos données ont été enregistrées avec succès!');
        return $this->redirectToRoute('app_job');
    }
}
