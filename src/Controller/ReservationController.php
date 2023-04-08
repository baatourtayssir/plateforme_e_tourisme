<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Entity\Offer;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\AgenceRepository;
use App\Repository\ReservationRepository;
use App\Repository\OfferExcursionRepository;
use App\Repository\OfferRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('offer/reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }



/*     #[Route('/', name: 'app_reservation_show', methods: ['GET'])]
    public function show($agence, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findBy(['agence' => $agence]);

        foreach ($reservations as $reservation) {

            $form = $this->createForm(ReservationType::class, $reservation);
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager->persist($reservation);
                $entityManager->flush();
                return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
            }

            // Create a new instance of FormView
            $formView = $form->createView();
        }
        return $this->render('offer/reservation/show.html.twig', [
            'reservation' => $reservation,
            'form' => $formView,
            'reservations' => $reservations,
        ]);
    }  */
    #[Route('/', name: 'app_reservation_show', methods: ['GET'])]
    public function show($agence, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findBy(['agence' => $agence]); 
    
        $reservation = null; // Initialise la variable à null avant la boucle
    
        foreach ($reservations as $res) {
            $form = $this->createForm(ReservationType::class, $res);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($res);
                $entityManager->flush();
                return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
            }
    
            $formView = $form->createView();
            $reservation = $res; // Affecte la réservation courante à la variable $reservation
        }
    
        // Vérifie si une réservation a été trouvée
        if (!$reservation) {
            $reservation = new Reservation();
            $agenceEntity = $entityManager->getRepository(Agence::class)->find($agence);
            $reservation->setAgence($agenceEntity);
            $form = $this->createForm(ReservationType::class, $reservation);
        } else {
            $form = $this->createForm(ReservationType::class, $reservation);
            $formView = $form->createView();
        }
    
        $formView = $form->createView();
    
        return $this->render('offer/reservation/show.html.twig', [
            'reservation' => $reservation,
            'form' => $formView,
            'reservations' => $reservations,
        ]);
    }
    
    




    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $id, AgenceRepository $agenceRepository, ReservationRepository $reservationRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $agence = $agenceRepository->find($id);
            $reservation->setAgence($agence);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_resrvation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/reservation/form.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }



    /*    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    } */

    #[Route('/edit/{id}/', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, Agence $agence, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reservation = $entityManager->getRepository(Reservation::class)->find($id);
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            /*  $reservation->setAgence($agence); */


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            /*  $reservation->setAgence($agence); */

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/reservation/form.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('{id}/delete', name: 'app_reservation_delete')]
    public function delete(Request $request, $id): Response
    {
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($reservation);
        $entityManager->flush();

        $response = new Response();
        $response->send();


        return $this->redirectToRoute('app_reservation_index');
    }

    #[Route('/{id}/new', name: 'app_reservation_new_reservation', methods: ['GET', 'POST'])]
    public function newReservation(Request $request, int $id, AgenceRepository $agenceRepository, ReservationRepository $reservationRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $agence = $agenceRepository->find($id);
            $reservation->setAgence($agence);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_agence_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/reservation/form.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }


    #[Route('{agence}/delete/{id}', name: 'app_reservation_delete_reservation')]
    public function deleteReservation(Request $request, $id, Agence $agence): Response
    {
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($reservation);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        $reservation->setAgence($agence);
        return $this->redirectToRoute('app_agence_show', ['id' => $agence->getId()]);
    }

    #[Route('{agence}/edit/{id}/', name: 'app_reservation_edit_reservation', methods: ['GET', 'POST'])]
    public function editReservation(Request $request, int $id, Agence $agence, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reservation = $entityManager->getRepository(Reservation::class)->find($id);
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $reservation->setAgence($agence);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            $reservation->setAgence($agence);

            return $this->redirectToRoute('app_agence_show', ['id' => $agence->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/reservation/form.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }
}
