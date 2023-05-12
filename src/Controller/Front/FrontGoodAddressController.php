<?php

namespace App\Controller\Front;

use App\Entity\GoodAddress;
use App\Entity\Offer;
use App\Entity\Reservation;
use App\Entity\Travel;
use App\Form\ReservationOfferType;
use App\Form\ReservationType;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\AgenceRepository;
use App\Repository\CategoriesRepository;
use App\Repository\CategoryRepository;
use App\Repository\CountryRepository;
use App\Repository\CruiseRepository;
use App\Repository\GeographicalRepository;
use App\Repository\GoodAddressRepository;
use App\Repository\OfferRepository;
use App\Repository\ReservationRepository;
use App\Repository\TravelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\KernelService;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/front/GoodAddress')]
class FrontGoodAddressController extends AbstractController
{

/*     #[Route('/GoodAddress', name: 'app_front_goodAddress', methods: ['GET', 'POST'])]
    public function goodAddress(GoodAddressRepository $goodAddressRepository): Response
    {
        $goodAddresses = $goodAddressRepository->findAll();
        return $this->render('front/goodAddress/goodAddress.html.twig', [
            'goodAddresses' => $goodAddresses,
        ]);
    }



    #[Route('/{id}', name: 'app_front_goodAddress_show', methods: ['GET', 'POST'])]
    public function showgoodAddress(Request $request,GoodAddress $goodAddress): Response
    {
        return $this->render('front/goodAddress/show.html.twig', [
            'goodAddress' => $goodAddress,
           
        ]);
    } */

}
