<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HobbyController extends AbstractController
{
    #[Route('/hobby', name: 'app_hobby')]
    public function index(): Response
    {
        return $this->render('hobby/index.html.twig', [
            'controller_name' => 'HobbyController',
        ]);
    }
}
