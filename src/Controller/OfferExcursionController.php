<?php

namespace App\Controller;

use App\Entity\OfferExcursion;
use App\Form\OfferExcursionType;
use App\Repository\OfferExcursionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/offer/excursion')]
class OfferExcursionController extends AbstractController
{
    #[Route('/', name: 'app_offer_excursion_index', methods: ['GET'])]
    public function index(OfferExcursionRepository $offerExcursionRepository): Response
    {
        return $this->render('offer/offer_excursion/index.html.twig', [
            'offer_excursions' => $offerExcursionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_offer_excursion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OfferExcursionRepository $offerExcursionRepository): Response
    {
        $offerExcursion = new OfferExcursion();
        $form = $this->createForm(OfferExcursionType::class, $offerExcursion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offerExcursion);
            $entityManager->flush();
            return $this->redirectToRoute('app_offer_excursion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/offer_excursion/form.html.twig', [
            'offer_excursion' => $offerExcursion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offer_excursion_show', methods: ['GET'])]
    public function show(OfferExcursion $offerExcursion): Response
    {
        return $this->render('offer_excursion/show.html.twig', [
            'offer_excursion' => $offerExcursion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_offer_excursion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OfferExcursion $offerExcursion, OfferExcursionRepository $offerExcursionRepository): Response
    {
        $form = $this->createForm(OfferExcursionType::class, $offerExcursion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offerExcursion);
            $entityManager->flush();
            return $this->redirectToRoute('app_offer_excursion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/offer_excursion/form.html.twig', [
            'offer_excursion' => $offerExcursion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offer_excursion_delete', methods: ['POST'])]
    public function delete(Request $request, OfferExcursion $offerExcursion, OfferExcursionRepository $offerExcursionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offerExcursion->getId(), $request->request->get('_token'))) {
            $offerExcursionRepository->remove($offerExcursion, true);
        }

        return $this->redirectToRoute('app_offer_excursion_index', [], Response::HTTP_SEE_OTHER);
    }
}
