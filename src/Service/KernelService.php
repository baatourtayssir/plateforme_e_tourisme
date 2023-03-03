<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class KernelService extends AbstractController  
{
    public function upload(UploadedFile $file)
    {
        $files_directory = $this->getParameter('files_directory');
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($files_directory, $fileName);
        

        return $fileName;
    }
}