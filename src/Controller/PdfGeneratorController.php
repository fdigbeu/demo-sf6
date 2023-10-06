<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pdf/generator')]
class PdfGeneratorController extends AbstractController
{
    #[Route('/', name: 'app_pdf_generator')]
    public function index(): Response
    {
        $data = [
            'imageSrc'  => $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/assets/images/default.png'),
            'name'         => 'John Doe',
            'address'      => 'USA',
            'mobileNumber' => '000000000',
            'email'        => 'john.doe@email.com'
        ];

        $html =  $this->renderView('pdf_generator/index.html.twig', $data);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $content = "{$dompdf->stream('resume', ['Attachment' => false])}";
         
       return new Response ($content, Response::HTTP_OK, ['Content-Type' => 'application/pdf']);
    }

    private function imageToBase64($path) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}
