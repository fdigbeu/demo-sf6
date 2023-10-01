<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfosUserController extends AbstractController
{
    #[Route('/infos/user', name: 'app_infos_user')]
    public function index(): Response
    {
        return $this->render('infos_user/index.html.twig', [
            'controller_name' => 'InfosUserController',
        ]);
    }
}
