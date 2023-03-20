<?php

namespace App\Controller;

use App\Entity\Excursion;
use App\Form\ExcursionType;
use App\Repository\ExcursionRepository;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\KernelService;

#[Route('/excursion')]
class ExcursionController extends AbstractController
{

    public function __construct(private OfferRepository $offerRepository)
    {
    }

    #[Route('/', name: 'app_excursion_index', methods: ['GET'])]
    public function index(ExcursionRepository $excursionRepository): Response
    {
        return $this->render('offer/excursion/index.html.twig', [
            'excursions' => $excursionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_excursion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ExcursionRepository $excursionRepository, KernelService $kernelService): Response
    {
        $excursion = new Excursion();
        $form = $this->createForm(ExcursionType::class, $excursion);
      
        $form->handleRequest($request);
/* dd($excursion); */
        if ($form->isSubmitted() && $form->isValid()) {

            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadExcursionPicture($myFile);
            $excursion->setPicture($fileName);

            $entityManager = $this->getDoctrine()->getManager();


           /*  $selectedCountries = $form->get('country')->getData();
            foreach ($selectedCountries as $country) {
                $excursion->addCountry($country);
            }


            $regions = $form->get('regions')->getData();
            foreach ($regions as $region) {
                $excursion->addRegion($regions);
            } */

            $entityManager->persist($excursion);
            $entityManager->flush();

            return $this->redirectToRoute('app_excursion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/excursion/form.html.twig', [
            'excursion' => $excursion,
            'form' => $form,
        ]);
    }

    #[Route('{id}/show', name: 'app_excursion_show', methods: ['GET'])]
    public function show(Excursion $excursion): Response
    {
        return $this->render('excursion/show.html.twig', [
            'excursion' => $excursion,
        ]);
    }

    #[Route('{id}/edit', name: 'app_excursion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Excursion $excursion, ExcursionRepository $excursionRepository, KernelService $kernelService): Response
    {
        $form = $this->createForm(ExcursionType::class, $excursion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadExcursionPicture($myFile);
            $excursion->setPicture($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($excursion);
            $entityManager->flush();

            return $this->redirectToRoute('app_excursion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/excursion/form.html.twig', [
            'excursion' => $excursion,
            'form' => $form,
        ]);
    }

    #[Route('{id}/delete', name: 'app_excursion_delete')]
    public function delete(Request $request, $id): Response
    {

        $excursion = $this->getDoctrine()->getRepository(Excursion::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($excursion);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_excursion_index');
    }
}
