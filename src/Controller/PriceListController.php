<?php

namespace App\Controller;

use App\Entity\Excursion;
use App\Entity\PriceList;
use App\Form\PriceListType;
use App\Repository\ExcursionRepository;
use App\Repository\OfferRepository;
use App\Repository\PriceListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function show($excursion): Response
    {
        $priceLists = $excursion->getPriceLists();

        return $this->render('offer/price_list/index.html.twig', [
            'priceLists' => $priceLists,
            'excursion' => $excursion,

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
    public function new(Request $request, int $id, PriceListRepository $priceListRepository, ExcursionRepository $excursionRepository): Response
    {
        $priceList = new PriceList();
        $form = $this->createForm(PriceListType::class, $priceList);
        $form->handleRequest($request);

        $excursion = $excursionRepository->findOneBy(['id' => $id]);
        if (!$excursion) {
            throw $this->createNotFoundException('Excursion not found');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $excursion->addPriceList($priceList);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($priceList);
            $entityManager->flush();
            return $this->redirectToRoute('app_excursion_show', ['id' => $excursion->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/price_list/form.html.twig', [
            'price_list' => $priceList,
            'form' => $form,
            'excursion' => $excursion,
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


    #[Route('{excursion}/edit/{id}/', name: 'app_price_list_edit_price_list', methods: ['GET', 'POST'])]
    public function editPriceList(Request $request, int $id, Excursion $excursion, PriceList $priceList, PriceListRepository $priceListRepository): Response
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
            'price_list' => $priceList,
            'form' => $form,
        ]);
    }

    #[Route('{excursion}/delete/{id}/', name: 'app_price_list_delete_price_list')]
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
}
