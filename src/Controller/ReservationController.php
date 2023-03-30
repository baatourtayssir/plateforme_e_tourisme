<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
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

    public function showReservation($offerId)
{
    // récupérer les réservations pour l'offre spécifiée
    $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findBy(['offer' => $offerId]);

    // passer les réservations au modèle Twig
    return $this->render('offer/reservation/index.html.twig', [
        'reservations' => $reservations,
    ]);
}


    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
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

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();
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
}
