<?php

namespace App\Controller;

use App\Entity\PriceList;
use App\Form\PriceListType;
use App\Repository\PriceListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/price/list')]
class PriceListController extends AbstractController
{
    #[Route('/', name: 'app_price_list_index', methods: ['GET'])]
    public function index(PriceListRepository $priceListRepository): Response
    {
        return $this->render('offer/price_list/index.html.twig', [
            'price_lists' => $priceListRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_price_list_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PriceListRepository $priceListRepository): Response
    {
        $priceList = new PriceList();
        $form = $this->createForm(PriceListType::class, $priceList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $priceListRepository->save($priceList, true);

            return $this->redirectToRoute('app_price_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/price_list/form.html.twig', [
            'price_list' => $priceList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_price_list_show', methods: ['GET'])]
    public function show(PriceList $priceList): Response
    {
        return $this->render('price_list/show.html.twig', [
            'price_list' => $priceList,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_price_list_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PriceList $priceList, PriceListRepository $priceListRepository): Response
    {
        $form = $this->createForm(PriceListType::class, $priceList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $priceListRepository->save($priceList, true);

            return $this->redirectToRoute('app_price_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/price_list/form.html.twig', [
            'price_list' => $priceList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_price_list_delete', methods: ['POST'])]
    public function delete(Request $request, PriceList $priceList, PriceListRepository $priceListRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$priceList->getId(), $request->request->get('_token'))) {
            $priceListRepository->remove($priceList, true);
        }

        return $this->redirectToRoute('app_price_list_index', [], Response::HTTP_SEE_OTHER);
    }
}
