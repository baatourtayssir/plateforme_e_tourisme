<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\KernelService;
use Doctrine\Common\Collections\ArrayCollection;

#[Route('/offer')]
class OfferController extends AbstractController
{
    #[Route('/', name: 'app_offer_index', methods: ['GET'])]
    public function index(OfferRepository $offerRepository): Response
    {
        return $this->render('offer/index.html.twig', [
            'offers' => $offerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_offer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OfferRepository $offerRepository, KernelService $kernelService): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadOfferPicture($myFile);
            $offer->setPicture($fileName);


            $countryIds = $request->request->get('country');
            $countries = $entityManager->getRepository(Country::class)->findBy(['id' => $countryIds]);

            // Association des pays à l'offre
            $offer->addCountry($countries);


            /*   $countrys = $form->get('country')->getData();
            foreach ($countrys as $country) {
                $offer->addCountry($country);
            } */


            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('app_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/form.html.twig', [
            'offer' => $offer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offer_show', methods: ['GET'])]
    public function show(Offer $offer): Response
    {
        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_offer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offer $offer, OfferRepository $offerRepository, KernelService $kernelService): Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadOfferPicture($myFile);
            $offer->setPicture($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('app_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/form.html.twig', [
            'offer' => $offer,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_offer_delete')]
    public function delete(Request $request, $id): Response
    {
        $offer = $this->getDoctrine()->getRepository(Excursion::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($offer);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_offer_index');
    }
}
