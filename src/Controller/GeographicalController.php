<?php

namespace App\Controller;

use App\Entity\Geographical;
use App\Form\GeographicalType;
use App\Repository\GeographicalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/geographical')]
class GeographicalController extends AbstractController
{
    #[Route('/', name: 'app_geographical_index', methods: ['GET'])]
    public function index(GeographicalRepository $geographicalRepository): Response
    {
        return $this->render('destination/geographical/index.html.twig', [
            'geographicals' => $geographicalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_geographical_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GeographicalRepository $geographicalRepository): Response
    {
        $geographical = new Geographical();
        $form = $this->createForm(GeographicalType::class, $geographical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($geographical);
            $entityManager->flush();
            return $this->redirectToRoute('app_geographical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('destination/geographical/form.html.twig', [
            'geographical' => $geographical,
            'form' => $form,
        ]);
    }

    /*     #[Route('/{id}', name: 'app_geographical_show', methods: ['GET'])]
    public function show(Geographical $geographical): Response
    {
        return $this->render('destination/geographical/form.html.twig', [
            'geographical' => $geographical,
        ]);
    } */

    #[Route('/{id}/edit', name: 'app_geographical_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Geographical $geographical, GeographicalRepository $geographicalRepository): Response
    {
        $form = $this->createForm(GeographicalType::class, $geographical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($geographical);
            $entityManager->flush();
            return $this->redirectToRoute('app_geographical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('destination/geographical/form.html.twig', [
            'geographical' => $geographical,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_geographical_delete')]
    public function delete(Request $request, $id): Response
    {

        $geographical = $this->getDoctrine()->getRepository(Geographical::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($geographical);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_geographical_index');
    }
}
