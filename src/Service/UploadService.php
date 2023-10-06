<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService{
    public function __construct(private SluggerInterface $slugger) {}

    // $fileData = $form->get('photo')->getData()
    // $fileDirectory = $this->getParameter('photos_directory')
    public function upload($fileData, $fileDirectory): string
    {
        $originalFilename = pathinfo($fileData->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$fileData->guessExtension();
        try {
            $fileData->move($fileDirectory, $newFilename);
        } 
        catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        return $this->slugger->slug($newFilename);
    }
}