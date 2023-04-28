<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\Hiking;
use App\Entity\Offer;
use App\Entity\Pictures;
use App\Form\HikingType;
use App\Form\HikingAgenceType;
use App\Repository\AgenceRepository;
use App\Repository\HikingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\KernelService;

#[Route('/hiking')]
class HikingController extends AbstractController
{
    #[Route('/', name: 'app_hiking_index', methods: ['GET'])]
    public function index(HikingRepository $hikingRepository): Response
    {

        $user = $this->getUser();
        $hikings = [];

        if ($this->isGranted('ROLE_ADMIN')) {
            $hikings = $this->getDoctrine()->getRepository(Hiking::class)->findAll();
        } elseif ($this->isGranted('ROLE_AGENT')) {
            $agent = $this->getDoctrine()->getRepository(Agent::class)->findOneBy(['email' => $user->getEmail()]);
            $hikings = $this->getDoctrine()->getRepository(Hiking::class)->findBy(['agence' => $agent->getAgence()]);
        }

        return $this->render('offer/hiking/index.html.twig', [
            'hikings' => $hikings,
        ]);
    }

    #[Route('/new', name: 'app_hiking_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HikingRepository $hikingRepository, KernelService $kernelService): Response
    {
        $hiking = new Hiking();
        $form = $this->createForm(HikingType::class, $hiking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadHikingPicture($myFile);
            $hiking->setPicture($fileName);


            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_Hiking_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $hiking->addImage($img);
            }


            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($hiking);
            $entityManager->flush();


            return $this->redirectToRoute('app_hiking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/hiking/form.html.twig', [
            'hiking' => $hiking,
            'form' => $form,
        ]);
    }




    #[Route('{id}/new/hiking', name: 'app_hiking_new_agence', methods: ['GET', 'POST'])]
    public function newHikingAgence(Request $request, int $id, AgenceRepository $agenceRepository, HikingRepository $hikingRepository, KernelService $kernelService): Response
    {

        $agence = $agenceRepository->find($id);
        $hiking = new Hiking();

        $form = $this->createForm(HikingAgenceType::class, $hiking);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadHikingPicture($myFile);
            $hiking->setPicture($fileName);


            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_Hiking_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $hiking->addImage($img);
            }

            $hiking->setAgence($agence);
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($hiking);
            $entityManager->flush();

            return $this->redirectToRoute('app_hiking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/hiking/formAgence.html.twig', [
            'hiking' => $hiking,
            'form' => $form,
        ]);
    }


    /*   #[Route('/{id}', name: 'app_hiking_show', methods: ['GET'])]
    public function show(Hiking $hiking): Response
    {
        return $this->render('hiking/show.html.twig', [
            'hiking' => $hiking,
        ]);
    } */

    
    #[Route('/{id}/show', name: 'app_hiking_show', methods: ['GET'])]
    public function show(Request $request, Hiking $hiking, Offer $offer, KernelService $kernelService, int $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $hiking = $entityManager->getRepository(Hiking::class)->find($id);


        $offer = $entityManager->getRepository(Offer::class)->find($id);

        $form = $this->createForm(HikingType::class, $hiking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadHikingPicture($myFile);
            $hiking->setPicture($fileName);


            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_Hiking_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $hiking->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hiking);
            $entityManager->flush();


            return $this->redirectToRoute('app_hiking_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('offer/hiking/show.html.twig', [
            'hiking' => $hiking,
            'offer' => $offer,
            'form' => $form,

        ]);
    }

    #[Route('/{id}/edit', name: 'app_hiking_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hiking $hiking, KernelService $kernelService, HikingRepository $hikingRepository): Response
    {
        $form = $this->createForm(HikingType::class, $hiking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadHikingPicture($myFile);
            $hiking->setPicture($fileName);


            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_Hiking_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $hiking->addImage($img);
            }


            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($hiking);
            $entityManager->flush();

            return $this->redirectToRoute('app_hiking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/hiking/form.html.twig', [
            'hiking' => $hiking,
            'form' => $form,
        ]);
    }

    #[Route('{id}/delete', name: 'app_hiking_delete')]
    public function delete(Request $request, $id): Response
    {
        $hiking = $this->getDoctrine()->getRepository(Hiking::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($hiking);
        $entityManager->flush();

        $response = new Response();
        $response->send();


        return $this->redirectToRoute('app_hiking_index');
    }

    #[Route('{hiking}/{id}/edit/hiking', name: 'app_hiking_edit_agence', methods: ['GET', 'POST'])]
    public function editHikingAgence(Request $request, Hiking $hiking,int $id,AgenceRepository $agenceRepository, KernelService $kernelService, HikingRepository $hikingRepository): Response
    {
        $agence = $agenceRepository->find($id);
        $form = $this->createForm(HikingAgenceType::class, $hiking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadHikingPicture($myFile);
            $hiking->setPicture($fileName);


            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_Hiking_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $hiking->addImage($img);
            }

            $hiking->setAgence($agence);
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($hiking);
            $entityManager->flush();

            return $this->redirectToRoute('app_hiking_index', ['id' => $hiking->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/hiking/formAgence.html.twig', [
            'hiking' => $hiking,
            'form' => $form,
        ]);
    }
}
