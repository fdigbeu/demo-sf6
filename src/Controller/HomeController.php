<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'name' => 'Francky',
        ]);
    }

    
    #[Route('/home/{firstname}/{name}', name: 'app_home_civility')]
    public function home(Request $request, $firstname, $name): Response
    {
        //dd($request);
        return $this->render('home/index.html.twig', [
            'firstname' => $firstname,
            'name' => $name,
        ]);
    }
}
