<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\Agence;
use App\Entity\Pictures;
use App\Entity\Country;
use App\Form\OfferType;
use App\Form\AgenceType;
use App\Repository\AgenceRepository;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\KernelService;


#[Route('/offer')]
class OfferController extends AbstractController
{
    #[Route('/', name: 'app_offer_index', methods: ['GET'])]
    public function index(Request $request, $agence, KernelService $kernelService, ?Offer $offer = null): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $offers = $this->getDoctrine()->getRepository(Offer::class)->findBy([
            'agence' => $agence,
        ]);

        foreach ($offers as $offer) {

            $form = $this->createForm(OfferType::class, $offer);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $myFile = $form['picture']->getData();
                $fileName = $kernelService->loadExcursionPicture($myFile);
                $offer->setPicture($fileName);
                $images = $form->get('images')->getData();

                foreach ($images as $image) {
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                    $image->move(
                        $this->getParameter('pictures_Excursion_directory'),
                        $fichier
                    );

                    $img = new Pictures();
                    $img->setName($fichier);
                    $offer->addImage($img);
                }


                $entityManager->persist($offer);
                $entityManager->flush();

                return $this->redirectToRoute('app_offer_index', [], Response::HTTP_SEE_OTHER);
            }


            // Create a new instance of FormView
            $formView = $form->createView();
        }

        return $this->render('offer/index.html.twig', [
            'offer' => $offer,
            'form' => $formView,
            'offers' => $offers,

        ]);
    }


    /* public function index(Agence $agence, array $offers)
    {
        return $this->render('offer/index.html.twig', [
            'agence' => $agence,
            'offers' => $offers,
        ]);
    } */

    /* public function index(OfferRepository $offerRepository): Response
    {
        return $this->render('offer/index.html.twig', [
            'offers' => $offerRepository->findAll(),
        ]);
     } */


    #[Route('/{id}/new', name: 'app_offer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OfferRepository $offerRepository, AgenceRepository $agenceRepository, KernelService $kernelService, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $offer = new Offer();
        /* $agence = $entityManager->getRepository(Agence::class)->find($id); */
      /*   $agence = $agenceRepository->find($id);
        $offer->setAgence($agence); */

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadExcursionPicture($myFile);
            $offer->setPicture($fileName);


            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_Excursion_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $offer->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();


            return $this->redirectToRoute('app_agence_show', ['id' => $id]);
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
            $fileName = $kernelService->loadExcursionPicture($myFile);
            $offer->setPicture($fileName);

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_Excursion_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $offer->addImage($img);
            }

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
