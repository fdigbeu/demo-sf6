<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/extension')]
class ExtensionController extends AbstractController
{
    #[Route('/', name: 'app_extension')]
    public function index(): Response
    {
        $lastname = "DIGBEU";
        $firstname = "Freddy Eric";
        $photo = "dfe.jpg";
        return $this->render('extension/index.html.twig', [
            'lastname' => $lastname,
            'firstname' => $firstname,
            'photo' => $photo,
        ]);
    }
}
