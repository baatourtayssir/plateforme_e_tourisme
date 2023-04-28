<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\Offer;
use App\Entity\Omra;
use App\Entity\Pictures;
use App\Form\OmraType;
use App\Form\OmraAgenceType;
use App\Repository\AgenceRepository;
use App\Repository\OmraRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\KernelService;


#[Route('/omra')]
class OmraController extends AbstractController
{
    #[Route('/', name: 'app_omra_index', methods: ['GET'])]
    public function index(OmraRepository $omraRepository): Response
    {

        $user = $this->getUser();
        $omras = [];

        if ($this->isGranted('ROLE_ADMIN')) {
            $omras = $this->getDoctrine()->getRepository(Omra::class)->findAll();
        } elseif ($this->isGranted('ROLE_AGENT')) {
            $agent = $this->getDoctrine()->getRepository(Agent::class)->findOneBy(['email' => $user->getEmail()]);
            $omras = $this->getDoctrine()->getRepository(Omra::class)->findBy(['agence' => $agent->getAgence()]);
        }
        
        return $this->render('offer/omra/index.html.twig', [
            'omras' => $omras,
        ]);
    }

    #[Route('/new', name: 'app_omra_new', methods: ['GET', 'POST'])]
    public function new(Request $request, KernelService $kernelService, OmraRepository $omraRepository): Response
    {
        $omra = new Omra();
        $form = $this->createForm(OmraType::class, $omra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadPictureOmra($myFile);
                $omra->setPicture($fileName);
            }

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('Omra_Pictures_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $omra->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($omra);
            $entityManager->flush();


            return $this->redirectToRoute('app_omra_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/omra/form.html.twig', [
            'omra' => $omra,
            'form' => $form,
        ]);
    }




    #[Route('{id}/new/omra', name: 'app_omra_new_agence', methods: ['GET', 'POST'])]
    public function newOmraAgence(Request $request, int $id, AgenceRepository $agenceRepository, OmraRepository $omraRepository, KernelService $kernelService): Response
    {

        $agence = $agenceRepository->find($id);
        $omra = new Omra();

        $form = $this->createForm(OmraAgenceType::class, $omra);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadPictureOmra($myFile);
                $omra->setPicture($fileName);
            }

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('Omra_Pictures_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $omra->addImage($img);
            }


            $omra->setAgence($agence);
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($omra);
            $entityManager->flush();

            return $this->redirectToRoute('app_omra_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/omra/formAgence.html.twig', [
            'omra' => $omra,
            'form' => $form,
        ]);
    }





   /*  #[Route('/{id}', name: 'app_omra_show', methods: ['GET'])]
    public function show(Omra $omra): Response
    {
        return $this->render('omra/show.html.twig', [
            'omra' => $omra,
        ]);
    } */


    #[Route('/{id}/show', name: 'app_omra_show', methods: ['GET'])]
    public function show(Request $request, Omra $omra, Offer $offer, KernelService $kernelService, int $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $omra = $entityManager->getRepository(Omra::class)->find($id);
       
       
        $offer = $entityManager->getRepository(Offer::class)->find($id);
      
        $form = $this->createForm(OmraType::class, $omra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadPictureOmra($myFile);
                $omra->setPicture($fileName);
            }

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('Omra_Pictures_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $omra->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($omra);
            $entityManager->flush();


            return $this->redirectToRoute('app_omra_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('offer/omra/show.html.twig', [
            'omra' => $omra,
            'offer' => $offer,
            'form' => $form,
          

        ]);
    }




    #[Route('/{id}/edit', name: 'app_omra_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Omra $omra, KernelService $kernelService, OmraRepository $omraRepository): Response
    {
        $form = $this->createForm(OmraType::class, $omra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadPictureOmra($myFile);
                $omra->setPicture($fileName);
            }

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('Omra_Pictures_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $omra->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($omra);
            $entityManager->flush();

            return $this->redirectToRoute('app_omra_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/omra/form.html.twig', [
            'omra' => $omra,
            'form' => $form,
        ]);
    }

    #[Route('{id}/delete', name: 'app_omra_delete')]
    public function delete(Request $request,$id): Response
    {
        $omra = $this->getDoctrine()->getRepository(Omra::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($omra);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_omra_index');
    }


        #[Route('{omra}/{id}/edit/omra', name: 'app_omra_edit_agence', methods: ['GET', 'POST'])]
    public function editOmraAgence(Request $request, int $id,AgenceRepository $agenceRepository,Omra $omra, KernelService $kernelService, OmraRepository $omraRepository): Response
    {
        $agence = $agenceRepository->find($id);
        $form = $this->createForm(OmraAgenceType::class, $omra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadPictureOmra($myFile);
                $omra->setPicture($fileName);
            }

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('Omra_Pictures_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $omra->addImage($img);
            }
            $omra->setAgence($agence);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($omra);
            $entityManager->flush();

            return $this->redirectToRoute('app_omra_index', ['id' => $omra->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/omra/formAgence.html.twig', [
            'omra' => $omra,
            'form' => $form,
        ]);
    }
}
