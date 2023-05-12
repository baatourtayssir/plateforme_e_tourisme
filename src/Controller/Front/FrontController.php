<?php

namespace App\Controller\Front;

use App\Entity\Cruise;
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
use App\Repository\PriceListRepository;
use App\Repository\ReservationRepository;
use App\Repository\TravelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\KernelService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;


#[Route('/front/end')]
class FrontController extends AbstractController
{

    /*  private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    } */



    #[Route('/home', name: 'app_front_home', methods: ['GET', 'POST'])]
    public function home(Request $request, OfferRepository $offerRepository, AgenceRepository $agenceRepository, CountryRepository $countryRepository): Response
    {

        $agences = $agenceRepository->findAll();
        $countries = $countryRepository->findAll();
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);
        /* $form = $this->createForm(SearchType::class, $searchData, [
            'entityManager' => $this->entityManager,
        ]); */
        $form->handleRequest($request);

        $offers = [];
        if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')) {
            $offers = $offerRepository->search($searchData);
        }
        $goodAddresses = [];
        foreach ($offers as $offer) {
            foreach ($offer->getGoodAddress() as $goodAddress) {
                $goodAddresses[] = $goodAddress;
            }
        }


        return $this->render('front/home.html.twig', [
            'form' => $form->createView(),
            'offers' => $offers,
            'goodAddresses' => $goodAddresses,
            'countries' => $countries,
            'agences' => $agences,
        ]);
    }


    #[Route('/travel', name: 'app_front_travel', methods: ['GET', 'POST'])]
    public function travel(TravelRepository $travelRepository): Response
    {
        $travels = $travelRepository->findAll();
        return $this->render('front/travel/travel.html.twig', [
            'travels' => $travels,
        ]);
    }

    #[Route('/front/cruise', name: 'app_front_cruise', methods: ['GET', 'POST'])]
    public function cruise(CruiseRepository $cruiseRepository): Response
    {
        $cruises = $cruiseRepository->findAll();
        return $this->render('front/cruise/cruise.html.twig', [
            'cruises' => $cruises,
        ]);
    }


    /*        #[Route('/cruise/{cruise}', name: 'app_front_cruise_show', methods: ['GET', 'POST'])]
    public function showCruise(Request $request, Cruise $cruise, Security $security, FlashBagInterface $flashBag, GeographicalRepository $geographicalRepository, CountryRepository $countryRepository, CategoryRepository $categoryRepository): Response
    {

        $geographicals = $geographicalRepository->findAll();
        $countries = $countryRepository->findAll();
        $categories = $categoryRepository->findAll();
        $priceLists = $cruise->getPriceLists();
        foreach ($priceLists as $priceList) {
            $hotels = $priceList->getHotels();
        }

        $GoodAddresses = $cruise->getGoodAddress();
        $Reviews = $cruise->getReviews();
        $agence = $cruise->getAgence();
        $reservation = new Reservation();
        $form = $this->createForm(ReservationOfferType::class, $reservation);
        $form->handleRequest($request);

        if (!$security->getUser()) {
            // Rediriger vers la page d'inscription du client
            return $this->redirectToRoute('app_register_client');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $client = $this->getUser();
            $reservation->setAgence($agence);
            $reservation->setOffer($cruise);
            $reservation->setClient($client);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->persist($cruise);
            $entityManager->flush();

            $flashBag->add('success', 'La réservation a été effectuée avec succès.');

            $reservation = new Reservation();
            $form = $this->createForm(ReservationOfferType::class, $reservation);
        }

        return $this->render('front/cruise/show.html.twig', [
            'cruise' => $cruise,
            'geographicals' => $geographicals,
            'countries' => $countries,
            'categories' => $categories,
            'priceLists' => $priceLists,
            'hotels' => $hotels,
            'GoodAddresses' => $GoodAddresses,
            'Reviews' => $Reviews,
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    } */








    #[Route('/cruise/{cruise}', name: 'app_front_cruise_show', methods: ['GET'])]
    public function showCruise(
        Cruise $cruise,
        GeographicalRepository $geographicalRepository,
        CountryRepository $countryRepository,
        CategoryRepository $categoryRepository,
        RequestStack $requestStack
    ): Response {
        $geographicals = $geographicalRepository->findAll();
        $countries = $countryRepository->findAll();
        $categories = $categoryRepository->findAll();
        $priceLists = $cruise->getPriceLists();

        $GoodAddresses = $cruise->getGoodAddress();
        $Reviews = $cruise->getReviews();


        // Créer une instance de Reservation et le formulaire de réservation
        $reservation = new Reservation();
        $form = $this->createForm(ReservationOfferType::class, $reservation);

        // Récupérer la requête actuelle depuis le RequestStack
        $currentRequest = $requestStack->getCurrentRequest();

        return $this->render('front/cruise/show.html.twig', [
            'cruise' => $cruise,
            'geographicals' => $geographicals,
            'countries' => $countries,
            'categories' => $categories,
            'priceLists' => $priceLists,
            'GoodAddresses' => $GoodAddresses,
            'Reviews' => $Reviews,
            'form' => $form->createView(),
            'currentRequest' => $currentRequest,
        ]);
    }



    #[Route('/{cruise}/cruise/reservation', name: 'app_front_cruise_reservation', methods: ['POST'])]
    public function createReservation(Request $request, Cruise $cruise, Security $security, FlashBagInterface $flashBag): Response
    {
        if (!$security->getUser()) {
            // Rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        $reservation = new Reservation();
        $form = $this->createForm(ReservationOfferType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $this->getUser();
            $reservation->setAgence($cruise->getAgence());
            $reservation->setOffer($cruise);
            $reservation->setClient($client);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            $flashBag->add('success', 'La réservation a été effectuée avec succès.');
        } else {
            $flashBag->add('error', 'Le formulaire de réservation est invalide.');
        }

        return $this->redirectToRoute('app_front_cruise_show', ['cruise' => $cruise->getId()]);
    }


















    #[Route('/agences', name: 'app_front_agence')]
    public function agences(AgenceRepository $agenceRepository): Response
    {
        $agences = $agenceRepository->findAll();
        return $this->render('front/agence.html.twig', [
            'agences' => $agences,
        ]);
    }

    #[Route('/goodAddress', name: 'app_front_goodAddress', methods: ['GET', 'POST'])]
    public function goodAddress(GoodAddressRepository $goodAddressRepository): Response
    {
        $goodAddresses = $goodAddressRepository->findAll();
        return $this->render('front/goodAddress/goodAddress.html.twig', [
            'goodAddresses' => $goodAddresses,
        ]);
    }



    #[Route('/goodAddress/{id}', name: 'app_front_goodAddress_show', methods: ['GET', 'POST'])]
    public function showgoodAddress(Request $request, GoodAddress $goodAddress): Response
    {
        return $this->render('front/goodAddress/show.html.twig', [
            'goodAddress' => $goodAddress,

        ]);
    }



    #[Route('/search', name: 'app_offer_search', methods: ['GET', 'POST'])]
    public function index(Request $request, OfferRepository $offerRepository): Response
    {
        $searchData = new SearchData();

        $form = $this->createForm(SearchType::class, $searchData);
        /* $form = $this->createForm(SearchType::class, $searchData, [
            'entityManager' => $this->entityManager,
        ]); */
        $form->handleRequest($request);

        $offers = [];
        if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')) {
            $offers = $offerRepository->search($searchData);
        }
        $goodAddresses = [];
        foreach ($offers as $offer) {
            foreach ($offer->getGoodAddress() as $goodAddress) {
                $goodAddresses[] = $goodAddress;
            }
        }

        return $this->render('front/search.html.twig', [
            'form' => $form->createView(),
            'offers' => $offers,
            'goodAddresses' => $goodAddresses,
        ]);
    }

    #[Route('/{id}/show', name: 'app_front_offer_show')]
    public function show($id): Response
    {
        $offer = $this->getDoctrine()->getRepository(Offer::class)
            ->find($id);
        return $this->render('front/show_offer.html.twig', array('offer' => $offer));
    }

    /*     #[Route('/{id}/details/travel', name: 'app_front_travel_details', methods: ['GET', 'POST'])]
    public function detailsTravel($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $travel = $entityManager->getRepository(Travel::class)->find($id);
    
        return new Response($this->renderView('front/travel/detail.html.twig', [
            'travel' => $travel,
        ]));
       
    } */



    /*       #[Route('/{id}', name: 'app_front_travel_show', methods: ['GET', 'POST'])]
    public function showtravel(Request $request,Travel $travel, GeographicalRepository $geographicalRepository, CountryRepository $countryRepository, CategoryRepository $categoryRepository): Response
    {

        $geographicals = $geographicalRepository->findAll();
        $countries = $countryRepository->findAll();
        $categories = $categoryRepository->findAll();
        $priceLists = $travel->getPriceLists();
        foreach ($priceLists as $priceList) {
            $hotels = $priceList->getHotels();
        }
        $ExcursionsNotIncluded = $travel->getTravelExcursions();
        $GoodAddresses = $travel->getGoodAddress();
        $Reviews = $travel->getReviews();
        $agence= $travel->getAgence();
        $reservation = new Reservation();
        $form = $this->createForm(ReservationOfferType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $reservation->setAgence($agence);
            $reservation->setOffer($travel);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->persist($travel);
            $entityManager->flush();
        }

        return $this->render('front/travel/show.html.twig', [
            'travel' => $travel,
            'geographicals' => $geographicals,
            'countries' => $countries,
            'categories' => $categories,
            'priceLists' => $priceLists,
            'hotels' => $hotels,
            'ExcursionsNotIncluded' => $ExcursionsNotIncluded,
            'GoodAddresses' => $GoodAddresses,
            'Reviews' =>$Reviews,
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }
*/

    #[Route('/travel/{travel}', name: 'app_front_travel_show', methods: ['GET'])]
    public function showTravl(
        Travel $travel,
        GeographicalRepository $geographicalRepository,
        CountryRepository $countryRepository,
        CategoryRepository $categoryRepository,
        RequestStack $requestStack
    ): Response {
        $geographicals = $geographicalRepository->findAll();
        $countries = $countryRepository->findAll();
        $categories = $categoryRepository->findAll();
        $priceLists = $travel->getPriceLists();
        /* foreach ($priceLists as $priceList) {
            $hotels = $priceList->getHotels();
        } */
        $ExcursionsNotIncluded = $travel->getTravelExcursions();
        $GoodAddresses = $travel->getGoodAddress();
        $Reviews = $travel->getReviews();


        // Créer une instance de Reservation et le formulaire de réservation
        $reservation = new Reservation();
                $form = $this->createForm(ReservationOfferType::class, $reservation);

        /* $form = $this->createForm(ReservationOfferType::class, $reservation, [
            'price_lists' => $priceLists,
        ]); */
        // Récupérer la requête actuelle depuis le RequestStack
        $currentRequest = $requestStack->getCurrentRequest();

        return $this->render('front/travel/show.html.twig', [
            'travel' => $travel,
            'geographicals' => $geographicals,
            'countries' => $countries,
            'categories' => $categories,
            'priceLists' => $priceLists,
            /* 'hotels' => $hotels, */
            'ExcursionsNotIncluded' => $ExcursionsNotIncluded,
            'GoodAddresses' => $GoodAddresses,
            'Reviews' => $Reviews,
            'form' => $form->createView(),
            'currentRequest' => $currentRequest,
        ]);
    }




    #[Route('/{travel}/travel/reservation', name: 'app_front_travel_reservation', methods: ['POST'])]
    public function createReservationTravel(Request $request, Travel $travel, Security $security, PriceListRepository $priceListRepository, FlashBagInterface $flashBag): Response
    {
        if (!$security->getUser()) {
            // Rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        /* $priceLists = $travel->getPriceLists(); */

        $reservation = new Reservation();
                $form = $this->createForm(ReservationOfferType::class, $reservation);

        /* $form = $this->createForm(ReservationFormType::class, $reservation, [
            'price_lists' => $priceLists,
        ]); */
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $this->getUser();
            $reservation->setAgence($travel->getAgence());
            $reservation->setOffer($travel);
            $reservation->setClient($client);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            $flashBag->add('success', 'La réservation a été effectuée avec succès.');
        } else {
            $flashBag->add('error', 'Le formulaire de réservation est invalide.');
        }

        return $this->redirectToRoute('app_front_travel_show', ['travel' => $travel->getId()]);
    }
}
