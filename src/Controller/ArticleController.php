<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if($session->has('nbVisite')){
            $nbVisite = $session->get('nbVisite') + 1;
        }
        else{
            $nbVisite = 1;
        }
        $session->set('nbVisite', $nbVisite);
        return $this->render('article/index.html.twig', [
            'nbVisite' => $nbVisite,
        ]);
    }
}
