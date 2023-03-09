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

    public function loadGoodAdressePicture(UploadedFile $file)
    {
        $files_directory = $this->getParameter('goodAddressPicture_directory');
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($files_directory, $fileName);
        

        return $fileName;
    }
    
    public function loadHotelPicture(UploadedFile $file)
    {
        $files_directory = $this->getParameter('HotelPicture_directory');
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($files_directory, $fileName);
        

        return $fileName;
    }

    public function loadExcursionPicture(UploadedFile $file)
    {
        $files_directory = $this->getParameter('ExcursionPicture_directory');
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($files_directory, $fileName);
        

        return $fileName;
    }


    public function loadOfferPicture(UploadedFile $file)
    {
        $files_directory = $this->getParameter('OfferPicture_directory');
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($files_directory, $fileName);
        

        return $fileName;
    }


    public function loadpicture(UploadedFile $file)
    {
        $files_directory = $this->getParameter('reviews_directory');
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($files_directory, $fileName);
        

        return $fileName;
    }
    

    public function loadProfile(UploadedFile $file)
    {
        $files_directory = $this->getParameter('profile_directory');
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($files_directory, $fileName);
        

        return $fileName;
    }

    

    public function loadPictures(UploadedFile $file)
    {
        $files_directory = $this->getParameter('pictures_directory');
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($files_directory, $fileName);
        

        return $fileName;
    }
}