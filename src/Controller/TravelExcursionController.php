<?php

namespace App\Controller;

use App\Entity\Travel;
use App\Entity\TravelExcursion;
use App\Form\TravelExcursionTravelType;
use App\Form\TravelExcursionType;
use App\Repository\OfferRepository;
use App\Repository\TravelExcursionRepository;
use App\Repository\TravelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/travel/excursion')]
class TravelExcursionController extends AbstractController
{
    #[Route('/', name: 'app_travel_excursion_index', methods: ['GET'])]
    public function index(TravelExcursionRepository $travelExcursionRepository): Response
    {
        return $this->render('offer/travel_excursion/index.html.twig', [
            'travel_excursions' => $travelExcursionRepository->findAll(),
        ]);
    }

    #[Route('/new/travel_excursion', name: 'app_travel_excursion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TravelExcursionRepository $travelExcursionRepository): Response
    {
        $travelExcursion = new TravelExcursion();
        $form = $this->createForm(TravelExcursionType::class, $travelExcursion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($travelExcursion);
            $entityManager->flush();
            return $this->redirectToRoute('app_travel_excursion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/travel_excursion/form.html.twig', [
            'travel_excursion' => $travelExcursion,
            'form' => $form,
        ]);
    }


    #[Route('/new/{id}', name: 'app_travel_excursion_new_travel', methods: ['GET', 'POST'])]
    public function newtravel(Request $request, int $id, OfferRepository $offerRepository, TravelRepository $travelRepository, TravelExcursionRepository $travelExcursionRepository): Response
    {
        $travel=$travelRepository->find($id);
        $travelExcursion = new TravelExcursion();
        $form = $this->createForm(TravelExcursionTravelType::class, $travelExcursion);
        $form->handleRequest($request);

        /*         $travel = $travelRepository->findOneBy(['id' => $id]);
 */

        $offer = $offerRepository->findOneBy(['id' => $id]);
        if (!$offer) {
            throw $this->createNotFoundException('L\'offre avec l\'id ' . $id . ' n\'existe pas');
        }

        if ($offer instanceof Travel) {
            $travel = $travelRepository->findOneBy(['id' => $id]);
            if (!$travel) {
                throw $this->createNotFoundException('Excursion not found');
            }
            $template = 'app_travel_show';
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $travel->addTravelExcursion($travelExcursion);
            $travelExcursion->addTravel($travel);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($travelExcursion);
            $entityManager->flush();
            return $this->redirectToRoute($template, ['id' => $travel->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/travel_excursion/formTravel.html.twig', [
            'travel_excursion' => $travelExcursion,
            'form' => $form,
            'travel' => $travel,
        ]);
    }

    /*     #[Route('/{id}', name: 'app_travel_excursion_show', methods: ['GET'])]
    public function show(TravelExcursion $travelExcursion): Response
    {
        return $this->render('travel_excursion/show.html.twig', [
            'travel_excursion' => $travelExcursion,
        ]);
    }
 */

    #[Route('/show/travel/Excursion', name: 'app_travel_excursion_show', methods: ['GET'])]
    public function show($travel): Response
    {
        $travel_excursions = $travel->getTravelExcursions();

        return $this->render('offer/travel_excursion/show_to_travel.html.twig', [
            'travel_excursions' => $travel_excursions,
            'travel' => $travel,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_tarvel_excursion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TravelExcursion $travelExcursion, TravelExcursionRepository $travelExcursionRepository): Response
    {
        $form = $this->createForm(TravelExcursionType::class, $travelExcursion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($travelExcursion);
            $entityManager->flush();
            return $this->redirectToRoute('app_travel_excursion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/travel_excursion/form.html.twig', [
            'tarvel_excursion' => $travelExcursion,
            'form' => $form,
        ]);
    }

    #[Route('{travel}/{id}/travel/edit', name: 'app_tarvel_excursion_edit_tarvel_excursion', methods: ['GET', 'POST'])]
    public function editTravel(Request $request,int $id,Travel $travel, TravelExcursion $travelExcursion, TravelRepository $travelRepository): Response
    {
        $travel=$travelRepository->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $travelExcursion = $entityManager->getRepository(TravelExcursion::class)->find($id);
        $form = $this->createForm(TravelExcursionTravelType::class, $travelExcursion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $travelExcursion->addTravel($travel);
            $entityManager->persist($travelExcursion);
            $entityManager->flush();
            return $this->redirectToRoute('app_travel_show', ['id' =>$travel->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/travel_excursion/formTravel.html.twig', [
            'tarvel_excursion' => $travelExcursion,
            'travel' => $travel,
            'form' => $form,
        ]);
    }

    #[Route('{id}/delete', name: 'app_travel_excursion_delete')]
    public function delete(Request $request, int $id): Response
    {
        $travelExcursion = $this->getDoctrine()->getRepository(TravelExcursion::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($travelExcursion);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_travel_excursion_index');
    }

    #[Route('{travel}/{id}/delete', name: 'app_travel_excursion_delete_travel_excursion')]
    public function deleteTravelExcursion(Request $request, int $id,Travel $travel): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $travelExcursion = $this->getDoctrine()->getRepository(TravelExcursion::class)->find($id);
        if ($travelExcursion != null) { // Check if $priceList is not NULL
            $entityManager->remove($travelExcursion);
            $entityManager->flush();

            $travelExcursion->setTravel($travel);
        }

        return $this->redirectToRoute('app_travel_show', ['id' => $travel->getId()]);
    }
}
