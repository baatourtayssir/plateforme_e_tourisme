<?php

namespace App\Controller;

use App\Entity\Excursion;
use App\Entity\PriceList;
use App\Form\PriceListType;
use App\Repository\ExcursionRepository;
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
            'excursion' =>$excursion,

        ]);
    }

    #[Route('/new', name: 'app_price_list_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $id,PriceListRepository $priceListRepository,ExcursionRepository $excursionRepository): Response
    {
        $priceList = new PriceList();
        $form = $this->createForm(PriceListType::class, $priceList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /* $excursion = $excursionRepository->find($id); */
           /*  $priceList->setOffer($excursion); */
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
    public function delete(Request $request,$id): Response
    {
        $priceList = $this->getDoctrine()->getRepository(PriceList::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($priceList);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_price_list_index');
    }
}
