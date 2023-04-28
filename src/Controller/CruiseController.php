<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\Cruise;
use App\Entity\Offer;
use App\Entity\Pictures;
use App\Entity\PriceList;
use App\Form\CruiseType;
use App\Form\CruiseAgenceType;
use App\Form\PriceListType;
use App\Repository\AgenceRepository;
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


        $user = $this->getUser();
        $cruises = [];

        if ($this->isGranted('ROLE_ADMIN')) {
            $cruises = $this->getDoctrine()->getRepository(Cruise::class)->findAll();
        } elseif ($this->isGranted('ROLE_AGENT')) {
            $agent = $this->getDoctrine()->getRepository(Agent::class)->findOneBy(['email' => $user->getEmail()]);
            $cruises = $this->getDoctrine()->getRepository(Cruise::class)->findBy(['agence' => $agent->getAgence()]);
        }

        return $this->render('offer/cruise/index.html.twig', [
            'cruises' => $cruises,
        ]);
    }

    #[Route('/new', name: 'app_cruise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, KernelService $kernelService, CruiseRepository $cruiseRepository): Response
    {
        $cruise = new Cruise();
        $form = $this->createForm(CruiseType::class, $cruise);
        $form->handleRequest($request);
       /* dd($cruise); */
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



    
    #[Route('{id}/new/cruise', name: 'app_cruise_new_agence', methods: ['GET', 'POST'])]
    public function newCruiseAgence(Request $request, int $id, AgenceRepository $agenceRepository, CruiseRepository $cruiseRepository, KernelService $kernelService): Response
    {

        $agence = $agenceRepository->find($id);
        $cruise = new Cruise();

        $form = $this->createForm(CruiseAgenceType::class, $cruise);

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

            $cruise->setAgence($agence);
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($cruise);
            $entityManager->flush();

            return $this->redirectToRoute('app_cruise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/cruise/formAgence.html.twig', [
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



    
    #[Route('/{id}/show', name: 'app_cruise_show', methods: ['GET', 'POST'])]
    public function show(Request $request,  Offer $offer, KernelService $kernelService, int $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $cruise = $entityManager->getRepository(Cruise::class)->find($id);


        $offer = $entityManager->getRepository(Offer::class)->find($id);

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

        return $this->renderForm('offer/cruise/show.html.twig', [
            'cruise' => $cruise,
            'offer' => $offer,
            'form' => $form,
        ]);
    }


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

    #[Route('{cruise}/{id}/edit/cruise', name: 'app_cruise_edit_agence', methods: ['GET', 'POST'])]
    public function editCruiseAgence(Request $request, Cruise $cruise,int $id,AgenceRepository $agenceRepository, KernelService $kernelService, CruiseRepository $cruiseRepository): Response
    {
        $agence = $agenceRepository->find($id);
        $form = $this->createForm(CruiseAgenceType::class, $cruise);
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

            $cruise->setAgence($agence);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cruise);
            $entityManager->flush();

            return $this->redirectToRoute('app_cruise_index', ['id' => $cruise->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/cruise/formAgence.html.twig', [
            'cruise' => $cruise,
            'form' => $form,
        ]);
    }
}
