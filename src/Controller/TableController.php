<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/table')]
class TableController extends AbstractController
{
    #[Route('/{nb?5<\d+>}', name: 'app_table')]
    public function index($nb): Response
    {
        $table = [];
        for($i=0; $i<$nb; $i++){
            $table[] = rand(10,200);
        }
        $minTable = min($table);
        $maxTable = max($table);
        $nbElement = count($table);
        return $this->render('table/index.html.twig', [
            'table' => $table,
            //'minTable' => $minTable,
            //'maxTable' => $maxTable,
            //'nbElement' => $nbElement,
        ]);
    }
}
