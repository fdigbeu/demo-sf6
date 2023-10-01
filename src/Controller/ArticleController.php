<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if($session->has('nbVisite')){
            $nbVisite = $session->get('nbVisite') + 1;
        }
        else{
            $nbVisite = 1;
        }
        
        $articles = [
            ["name" => "Téléviseur", "price"=>23.55, "image"=>"https://www.usinenouvelle.com/expo/img/televiseur-samsung-55-full-hd-incurve-smarttv-003436773-product_zoom.jpg"],
            ["name" => "Téléphone android", "price"=>226.99, "image"=>"https://cdn-boutique.e-recycle.com/img/p/5/8/5/3/5853-home_default.jpg"],
            ["name" => "Basket jordan", "price"=>180.99, "image"=>"https://it.kicksmaniac.com/zdjecia/2021/05/02/605/43/NIKE_AIR_JORDAN_6_RETRO_GS_CARMINE-mini.jpg"],
            ["name" => "Tee-shirt adidas", "price"=>85.00, "image"=>"https://cdn-images.farfetch-contents.com/19/94/51/09/19945109_44876251_1000.jpg"],
            ["name" => "Coussin en cuir", "price"=>99.90, "image"=>"https://www.cdiscount.com/pdt2/4/0/3/1/1200x1200/wov0778107423403/rw/housses-de-coussins-lot-de-2-luxe-en-cuir-artific.jpg"],
        ];

        $session->set('nbVisite', $nbVisite);
        return $this->render('article/index.html.twig', [
            'nbVisite' => $nbVisite,
            'articles' => $articles,
        ]);
    }
}
