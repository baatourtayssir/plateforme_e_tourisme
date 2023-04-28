<?php

namespace App\Controller;

use App\Entity\Cruise;
use App\Entity\Excursion;
use App\Entity\Hiking;
use App\Entity\Offer;
use App\Entity\Omra;
use App\Entity\PriceList;
use App\Entity\Travel;
use App\Form\PriceListTravelType;
use App\Form\PriceListType;
use App\Repository\CruiseRepository;
use App\Repository\ExcursionRepository;
use App\Repository\HikingRepository;
use App\Repository\OfferRepository;
use App\Repository\OmraRepository;
use App\Repository\PriceListRepository;
use App\Repository\TravelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


#[Route('/price/list')]
class PriceListController extends AbstractController
{
    #[Route('/', name: 'app_price_list_index', methods: ['GET'])]
    public function index($excursion): Response
    {
        $priceLists = $excursion->getOffer()->getPriceLists();
        return $this->render('offer/price_list/index.html.twig', [
            'priceLists' => $priceLists,
        ]);
    }

    #[Route('/show', name: 'app_price_list_show', methods: ['GET'])]
    public function showPriceListToExcursion($excursion): Response
    {
        $priceLists = $excursion->getPriceLists();

        return $this->render('offer/price_list/index.html.twig', [
            'priceLists' => $priceLists,
            'excursion' => $excursion,

        ]);
    }

    #[Route('/show/omra/price_list', name: 'app_price_list_show_to_omra', methods: ['GET'])]
    public function showPriceListToOmra($omra): Response
    {
        $priceLists = $omra->getPriceLists();

        return $this->render('offer/price_list/show_to_omra.html.twig', [
            'priceLists' => $priceLists,
            'omra' => $omra,
        ]);
    }

    #[Route('/show/cruise/price_list', name: 'app_price_list_show_to_Cruise', methods: ['GET'])]
    public function showPriceListToCruise(Request $request, $cruise): Response
    {

        $priceLists = $cruise->getPriceLists();
      /*   foreach ($priceLists as $priceList) {
            /*     $priceList = new PriceList(); */
          /*  $formPriceList = $this->createForm(PriceListType::class, $priceList);
            $formPriceList->handleRequest($request);

            if ($formPriceList->isSubmitted() && $formPriceList->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($priceList);
                $entityManager->flush();
                return $this->redirectToRoute('app_price_list_index', [], Response::HTTP_SEE_OTHER);
            }
        } */

        return $this->renderForm('offer/price_list/show_to_cruise.html.twig', [
            'priceLists' => $priceLists,
            /* 'priceList' => $priceList, */
            'cruise' => $cruise,
            /* 'formPriceList' => $formPriceList, */
        ]);
    }

    #[Route('/show/hiking/price_list', name: 'app_price_list_show_to_hiking', methods: ['GET'])]
    public function showPriceListToHiking($hiking): Response
    {
        $priceLists = $hiking->getPriceLists();

        return $this->render('offer/price_list/show_to_hiking.html.twig', [
            'priceLists' => $priceLists,
            'hiking' => $hiking,
        ]);
    }



    #[Route('/show/travel/price_list', name: 'app_price_list_show_to_travel', methods: ['GET'])]
    public function showPriceListToTravel($travel): Response
    {
        $priceLists = $travel->getPriceLists();

        return $this->render('offer/price_list/show_to_travel.html.twig', [
            'priceLists' => $priceLists,
            'travel' => $travel,
        ]);
    }

    /*   #[Route('/new', name: 'app_price_list_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $id,PriceListRepository $priceListRepository,ExcursionRepository $excursionRepository): Response
    {
        $priceList = new PriceList();
        $form = $this->createForm(PriceListType::class, $priceList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /* $excursion = $excursionRepository->find($id); */
    /*  $priceList->setOffer($excursion); */
    /*     $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($priceList);
            $entityManager->flush();
            return $this->redirectToRoute('app_price_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/price_list/form.html.twig', [
            'price_list' => $priceList,
            'form' => $form,
        ]);
    } */

    #[Route('/new/{id}', name: 'app_price_list_new_price_list', methods: ['GET', 'POST'])]
    public function new(Request $request, int $id, OfferRepository $offerRepository, TravelRepository $travelRepository, HikingRepository $hikingRepository, CruiseRepository $cruiseRepository, OmraRepository $omraRepository, PriceListRepository $priceListRepository, ExcursionRepository $excursionRepository): Response
    {
        $priceList = new PriceList();
        $form = $this->createForm(PriceListType::class, $priceList);
        $form->handleRequest($request);
        $offer = $offerRepository->findOneBy(['id' => $id]);
        if (!$offer) {
            throw $this->createNotFoundException('L\'offre avec l\'id ' . $id . ' n\'existe pas');
        }

        if ($offer instanceof Excursion) {
            $excursion = $excursionRepository->findOneBy(['id' => $id]);
            if (!$excursion) {
                throw $this->createNotFoundException('Excursion not found');
            }
            $template = 'app_excursion_show';
        } elseif ($offer instanceof Omra) {
            $omra = $omraRepository->findOneBy(['id' => $id]);
            if (!$omra) {
                throw $this->createNotFoundException('Omra not found');
            }
            $template = 'app_omra_show';
        } elseif ($offer instanceof Hiking) {
            $hiking = $hikingRepository->findOneBy(['id' => $id]);
            if (!$hiking) {
                throw $this->createNotFoundException('Hiking not found');
            }
            $template = 'app_hiking_show';
        } elseif ($offer instanceof Cruise) {
            $cruise = $cruiseRepository->findOneBy(['id' => $id]);
            if (!$cruise) {
                throw $this->createNotFoundException('Cruise not found');
            }
            $template = 'app_cruise_show';
        } elseif ($offer instanceof Travel) {
            $travel = $travelRepository->findOneBy(['id' => $id]);
            if (!$travel) {
                throw $this->createNotFoundException('Travel not found');
            }
            $template = 'app_travel_show';
        } else {
            throw $this->createNotFoundException('Unsupported offer type');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            /* $excursion->addPriceList($priceList); */
            $offer->addPriceList($priceList);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($priceList);
            $entityManager->flush();
            return $this->redirectToRoute($template, ['id' => $offer->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/price_list/form.html.twig', [
            'price_list' => $priceList,
            'form' => $form,
            'offer' => $offer,
        ]);
    }



    /*   #[Route('/{id}/new', name: 'app_price_list_new_price_list', methods: ['GET', 'POST'])]
    public function newPriceList(Request $request, int $id,OfferRepository $offerRepository,PriceListRepository $priceListRepository,ExcursionRepository $excursionRepository): Response
    {
        $priceList = new PriceList();
        $form = $this->createForm(PriceListType::class, $priceList);
        $form->handleRequest($request);

        $offer = $offerRepository->findOneBy(['id' => $id]);
        if (!$offer) {
            throw $this->createNotFoundException('L\'offre avec l\'id ' . $id . ' n\'existe pas');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            /* $excursion = $excursionRepository->find($id); */
    /*  $priceList->setOffer($excursion); */

    /*   $offer->addPriceList($priceList);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->persist($priceList);
            $entityManager->flush();
            return $this->redirectToRoute('app_excursion_index', ['id' => $offer->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/price_list/form.html.twig', [
            'price_list' => $priceList,
            'form' => $form->createView(),
            'offer' =>$offer,
        ]);
    } */


    /*     #[Route('/{id}', name: 'app_price_list_show', methods: ['GET'])]
    public function show(PriceList $priceList): Response
    {
        return $this->render('price_list/show.html.twig', [
            'price_list' => $priceList,
        ]);
    } */

    /*  #[Route('/{id}', name: 'app_price_list_show', methods: ['GET'])] */
    /*     public function show($id)
    {

        $excursion = $this->getDoctrine()->getRepository(Excursion::class)->find($id);
        $priceLists = $this->getDoctrine()->getRepository(PriceList::class)->findBy([
            'excursion' => $excursion,
        ]);


        /* $excursion = $this->getDoctrine()->getRepository(Excursion::class)->find($id);

        $priceLists = [];

        if ($excursion !== null) {
            $priceLists = $excursion->getPriceLists();
        } */
    /*  $em = $this->getDoctrine()->getManager();
        $excursion = $em->getRepository(Excursion::class)->find($id);

        $priceLists = $excursion->getPriceLists(); */

    /*       return $this->render('offer/price_list/index.html.twig', [
            'priceLists' => $priceLists,
            'excursion' => $excursion,
        ]);
    } */

    #[Route('/{id}/edit', name: 'app_price_list_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PriceList $priceList, PriceListRepository $priceListRepository): Response
    {
        $form = $this->createForm(PriceListType::class, $priceList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($priceList);
            $entityManager->flush();
            return $this->redirectToRoute('app_price_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/price_list/form.html.twig', [
            'price_list' => $priceList,
            'form' => $form,
        ]);
    }

    #[Route('{id}/delete', name: 'app_price_list_delete')]
    public function delete(Request $request, $id): Response
    {
        $priceList = $this->getDoctrine()->getRepository(PriceList::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($priceList);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_price_list_index');
    }


    #[Route('{excursion}/edit/excursion/{id}/', name: 'app_price_list_edit_price_list_to_excursion', methods: ['GET', 'POST'])]
    public function editPriceListToExcursion(Request $request, int $id, Excursion $excursion, PriceList $priceList, PriceListRepository $priceListRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $priceList = $entityManager->getRepository(PriceList::class)->find($id);
        $form = $this->createForm(PriceListType::class, $priceList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*          $priceList->setExcursion($excursion); */

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($priceList);
            $entityManager->flush();
            $priceList->setOffer($excursion);
            return $this->redirectToRoute('app_excursion_show', ['id' => $excursion->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/price_list/form.html.twig', [
            'excursion' => $excursion,
            'price_list' => $priceList,
            'form' => $form,
        ]);
    }

    #[Route('{omra}/edit/omra/{id}/', name: 'app_price_list_edit_price_list_to_omra', methods: ['GET', 'POST'])]
    public function editPriceListToOmra(Request $request, int $id, Omra $omra, PriceList $priceList, PriceListRepository $priceListRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $priceList = $entityManager->getRepository(PriceList::class)->find($id);
        $form = $this->createForm(PriceListType::class, $priceList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*          $priceList->setExcursion($excursion); */

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($priceList);
            $entityManager->flush();
            $priceList->setOffer($omra);
            return $this->redirectToRoute('app_omra_show', ['id' => $omra->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/price_list/form.html.twig', [
            'omra' => $omra,
            'price_list' => $priceList,
            'form' => $form,
        ]);
    }

    #[Route('{hiking}/edit/hiking/{id}/', name: 'app_price_list_edit_price_list_to_hiking', methods: ['GET', 'POST'])]
    public function editPriceListToHiking(Request $request, int $id, Hiking $hiking, PriceList $priceList, PriceListRepository $priceListRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $priceList = $entityManager->getRepository(PriceList::class)->find($id);
        $form = $this->createForm(PriceListType::class, $priceList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*          $priceList->setExcursion($excursion); */

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($priceList);
            $entityManager->flush();
            $priceList->setOffer($hiking);
            return $this->redirectToRoute('app_hiking_show', ['id' => $hiking->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/price_list/form.html.twig', [
            'hiking' => $hiking,
            'price_list' => $priceList,
            'form' => $form,
        ]);
    }

    #[Route('{cruise}/edit/cruise/{id}/', name: 'app_price_list_edit_price_list_to_cruise', methods: ['GET', 'POST'])]
    public function editPriceListToCruise(Request $request, int $id, Cruise $cruise, PriceList $priceList, PriceListRepository $priceListRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $priceList = $entityManager->getRepository(PriceList::class)->find($id);
        $form = $this->createForm(PriceListType::class, $priceList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*          $priceList->setExcursion($excursion); */

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($priceList);
            $entityManager->flush();
            $priceList->setOffer($cruise);
            return $this->redirectToRoute('app_cruise_show', ['id' => $cruise->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/price_list/form.html.twig', [
            'cruise' => $cruise,
            'price_list' => $priceList,
            'form' => $form,
        ]);
    }


    #[Route('{travel}/edit/travel/{id}/', name: 'app_price_list_edit_price_list_to_travel', methods: ['GET', 'POST'])]
    public function editPriceListToTravel(Request $request, int $id, Travel $travel, PriceList $priceList, PriceListRepository $priceListRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $priceList = $entityManager->getRepository(PriceList::class)->find($id);
        $form = $this->createForm(PriceListTravelType::class, $priceList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*          $priceList->setExcursion($excursion); */

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($priceList);
            $entityManager->flush();
            $priceList->setOffer($travel);
            return $this->redirectToRoute('app_travel_show', ['id' => $travel->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/price_list/formTravel.html.twig', [
            'travel' => $travel,
            'price_list' => $priceList,
            'form' => $form,
        ]);
    }


    #[Route('{travel}/delete/{id}/', name: 'app_price_list_delete_price_list_to_travel')]
    public function deletePriceListToTravel(Request $request, $id, Travel $travel): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $priceList = $entityManager->getRepository(PriceList::class)->find($id);

        if ($priceList != null) { // Check if $priceList is not NULL
            $entityManager->remove($priceList);
            $entityManager->flush();

            $priceList->setOffer($travel);
        }
        return $this->redirectToRoute('app_travel_show', ['id' => $travel->getId()]);
    }



    #[Route('{excursion}/delete/excursion//{id}/', name: 'app_price_list_delete_price_list')]
    public function deletePriceList(Request $request, $id, Excursion $excursion): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $priceList = $entityManager->getRepository(PriceList::class)->find($id);

        if (!$priceList) {
            throw $this->createNotFoundException('PriceList not found');
        }

        $entityManager->remove($priceList);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        $priceList->setOffer($excursion);

        return $this->redirectToRoute('app_excursion_show', ['id' => $excursion->getId()]);
    }

    #[Route('{omra}/delete/omra/{id}/', name: 'app_price_list_delete_price_list_to_omra')]
    public function deletePriceListToOmra(Request $request, $id, Omra $omra): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $priceList = $entityManager->getRepository(PriceList::class)->find($id);

        if (!$priceList) {
            throw $this->createNotFoundException('PriceList not found');
        }

        $entityManager->remove($priceList);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        $priceList->setOffer($omra);

        return $this->redirectToRoute('app_omra_show', ['id' => $omra->getId()]);
    }

    #[Route('{hiking}/delete/hiking/{id}/', name: 'app_price_list_delete_price_list_to_hiking')]
    public function deletePriceListToHiking(Request $request, $id, Hiking $hiking): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $priceList = $entityManager->getRepository(PriceList::class)->find($id);

        if (!$priceList) {
            throw $this->createNotFoundException('PriceList not found');
        }

        $entityManager->remove($priceList);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        $priceList->setOffer($hiking);

        return $this->redirectToRoute('app_hiking_show', ['id' => $hiking->getId()]);
    }

    #[Route('{cruise}/delete/cruise/{id}/', name: 'app_price_list_delete_price_list_to_cruise')]
    public function deletePriceListToCruise(Request $request, $id, Cruise $cruise): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $priceList = $entityManager->getRepository(PriceList::class)->find($id);

        if (!$priceList) {
            throw $this->createNotFoundException('PriceList not found');
        }

        $entityManager->remove($priceList);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        $priceList->setOffer($cruise);

        return $this->redirectToRoute('app_cruise_show', ['id' => $cruise->getId()]);
    }


    #[Route('/new/{id}/priceList', name: 'app_price_list_new_price_list_travel', methods: ['GET', 'POST'])]
    public function newPriceListToTravel(Request $request, int $id, OfferRepository $offerRepository, TravelRepository $travelRepository, HikingRepository $hikingRepository, CruiseRepository $cruiseRepository, OmraRepository $omraRepository, PriceListRepository $priceListRepository, ExcursionRepository $excursionRepository): Response
    {
        $priceList = new PriceList();
        $form = $this->createForm(PriceListTravelType::class, $priceList);
        $form->handleRequest($request);
        $offer = $offerRepository->findOneBy(['id' => $id]);
        if (!$offer) {
            throw $this->createNotFoundException('L\'offre avec l\'id ' . $id . ' n\'existe pas');
        }

        if ($offer instanceof Excursion) {
            $excursion = $excursionRepository->findOneBy(['id' => $id]);
            if (!$excursion) {
                throw $this->createNotFoundException('Excursion not found');
            }
            $template = 'app_excursion_show';
        } elseif ($offer instanceof Omra) {
            $omra = $omraRepository->findOneBy(['id' => $id]);
            if (!$omra) {
                throw $this->createNotFoundException('Omra not found');
            }
            $template = 'app_omra_show';
        } elseif ($offer instanceof Hiking) {
            $hiking = $hikingRepository->findOneBy(['id' => $id]);
            if (!$hiking) {
                throw $this->createNotFoundException('Hiking not found');
            }
            $template = 'app_hiking_show';
        } elseif ($offer instanceof Cruise) {
            $cruise = $cruiseRepository->findOneBy(['id' => $id]);
            if (!$cruise) {
                throw $this->createNotFoundException('Cruise not found');
            }
            $template = 'app_cruise_show';
        } elseif ($offer instanceof Travel) {
            $travel = $travelRepository->findOneBy(['id' => $id]);
            if (!$travel) {
                throw $this->createNotFoundException('Travel not found');
            }
            $template = 'app_travel_show';
        } else {
            throw $this->createNotFoundException('Unsupported offer type');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            /* $excursion->addPriceList($priceList); */
            $offer->addPriceList($priceList);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($priceList);
            $entityManager->flush();
            return $this->redirectToRoute($template, ['id' => $offer->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/price_list/formTravel.html.twig', [
            'price_list' => $priceList,
            'form' => $form,
            'offer' => $offer,
        ]);
    }

}
