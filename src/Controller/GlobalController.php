<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GlobalController extends AbstractController
{
    public function hello($lastname, $firstname): Response
    {
        return $this->render('global/hello.html.twig', [
            'lastname' => $lastname,
            'firstname' => $firstname,
        ]);
    }
}
