<?php

namespace App\Controller;

use App\Entity\Cruise;
use App\Entity\Pictures;
use App\Form\CruiseType;
use App\Repository\CruiseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\KernelService;


#[Route('/cruise')]
class CruiseController extends AbstractController
{
    #[Route('/', name: 'app_cruise_index', methods: ['GET'])]
    public function index(CruiseRepository $cruiseRepository): Response
    {
        return $this->render('offer/cruise/index.html.twig', [
            'cruises' => $cruiseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cruise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, KernelService $kernelService, CruiseRepository $cruiseRepository): Response
    {
        $cruise = new Cruise();
        $form = $this->createForm(CruiseType::class, $cruise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $myFile = $form['picture']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadCruisePicture($myFile);
                $cruise->setPicture($fileName);
            }

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('Cruise_Pictures_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $cruise->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cruise);
            $entityManager->flush();

            return $this->redirectToRoute('app_cruise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/cruise/form.html.twig', [
            'cruise' => $cruise,
            'form' => $form,
        ]);
    }

 /*    #[Route('/{id}', name: 'app_cruise_show', methods: ['GET'])]
    public function show(Cruise $cruise): Response
    {
        return $this->render('cruise/show.html.twig', [
            'cruise' => $cruise,
        ]);
    } */

    #[Route('/{id}/edit', name: 'app_cruise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cruise $cruise, KernelService $kernelService, CruiseRepository $cruiseRepository): Response
    {
        $form = $this->createForm(CruiseType::class, $cruise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                 
            $myFile = $form['picture']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadCruisePicture($myFile);
                $cruise->setPicture($fileName);
            }

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('Cruise_Pictures_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $cruise->addImage($img);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cruise);
            $entityManager->flush();

            return $this->redirectToRoute('app_cruise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/cruise/form.html.twig', [
            'cruise' => $cruise,
            'form' => $form,
        ]);
    }

    #[Route('{id}/delete', name: 'app_cruise_delete')]
    public function delete(Request $request, $id): Response
    {
        $cruise = $this->getDoctrine()->getRepository(Cruise::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($cruise);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_cruise_index');
    }
}
