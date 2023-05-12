<?php

namespace App\Controller;

use App\Entity\Excursion;
use App\Entity\Pictures;
use App\Entity\Agent;
use App\Entity\GoodAddress;
use App\Entity\Offer;
use App\Entity\User;
use App\Entity\PriceList;
use App\Form\ExcursionType;
use App\Form\PriceListType;
use App\Form\ExcursionAgenceType;
use App\Repository\AgenceRepository;
use App\Repository\ExcursionRepository;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\KernelService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

#[Route('/excursion')]
class ExcursionController extends AbstractController
{

    public function __construct(private OfferRepository $offerRepository)
    {
    }

    #[Route('/', name: 'app_excursion_index', methods: ['GET'])]
    public function index(ExcursionRepository $excursionRepository): Response
    {

        $user = $this->getUser();
        $excursions = [];

        if ($this->isGranted('ROLE_ADMIN')) {
            $excursions = $this->getDoctrine()->getRepository(Excursion::class)->findAll();
        } elseif ($this->isGranted('ROLE_AGENT')) {
            $agent = $this->getDoctrine()->getRepository(Agent::class)->findOneBy(['email' => $user->getEmail()]);
            $excursions = $this->getDoctrine()->getRepository(Excursion::class)->findBy(['agence' => $agent->getAgence()]);
        }

        return $this->render('offer/excursion/index.html.twig', [
            'excursions' => $excursions,
        ]);
    }

    #[Route('/new', name: 'app_excursion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ExcursionRepository $excursionRepository, KernelService $kernelService): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $excursion = new Excursion();
        $form = $this->createForm(ExcursionType::class, $excursion);

        $form->handleRequest($request);
        /*  dd($excursion); */
        if ($form->isSubmitted() && $form->isValid()) {

            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadExcursionPicture($myFile);
            $excursion->setPicture($fileName);


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
                $excursion->addImage($img);
            }

            $entityManager->persist($excursion);
            $entityManager->flush();

            return $this->redirectToRoute('app_excursion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/excursion/form.html.twig', [
            'excursion' => $excursion,
            'form' => $form,
        ]);
    }

    #[Route('{id}/new/excursion', name: 'app_excursion_new_agence', methods: ['GET', 'POST'])]
    public function newExcursionAgence(Request $request, int $id, AgenceRepository $agenceRepository, ExcursionRepository $excursionRepository, KernelService $kernelService): Response
    {

        $agence = $agenceRepository->find($id);
        $excursion = new Excursion();

        $form = $this->createForm(ExcursionAgenceType::class, $excursion);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadExcursionPicture($myFile);
            $excursion->setPicture($fileName);


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
                $excursion->addImage($img);
            }


            $excursion->setAgence($agence);
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($excursion);
            $entityManager->flush();

            return $this->redirectToRoute('app_excursion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/excursion/formAgence.html.twig', [
            'excursion' => $excursion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_excursion_show', methods: ['GET'])]
    public function show(Request $request, Excursion $excursion, Offer $offer, PriceList $priceList, KernelService $kernelService, int $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $excursion = $entityManager->getRepository(Excursion::class)->find($id);


        $offer = $entityManager->getRepository(Offer::class)->find($id);

       
      /*   $priceLists = $this->showPriceListToExcursion($excursion); */

        $form = $this->createForm(ExcursionType::class, $excursion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $myFile = $form['brochurefilename']->getData();
            $fileName = $kernelService->upload($myFile);
            $excursion->setBrochurefilename($fileName);
            $entityManager->persist($excursion);
            $entityManager->flush();

            return $this->redirectToRoute('app_excursion_form', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/excursion/show.html.twig', [
            'excursion' => $excursion,
            'offer' => $offer,
            'form' => $form,
            /* 'priceLists' => $priceLists  */
            /* 'price_list' => $priceList, */


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
                $excursion->addImage($img);
            }

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

    /*  #[Route('{id}/delete', name: 'app_excursion_delete')] */
    /*   public function delete(Request $request, $id): Response
    {

        $excursion = $this->getDoctrine()->getRepository(Excursion::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($excursion);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_excursion_index');
    } */

    #[Route('/{id}/delete', name: 'app_excursion_delete')]
    public function delete(Offer $offer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Supprime toutes les lignes enfants de la table price_list qui font référence à l'offre
        $priceListRepository = $this->getDoctrine()->getRepository(PriceList::class);
        $priceLists = $priceListRepository->findBy(['offer' => $offer]);
        foreach ($priceLists as $priceList) {
            $entityManager->remove($priceList);
        }

        // Supprime l'offre
        $entityManager->remove($offer);
        $entityManager->flush();

        return $this->redirectToRoute('app_excursion_index');
    }

/*     #[Route('/{id}/delete', name: 'app_excursion_delete')]
    public function delete(Request $request,$id): Response
    {
        $excursion = $this->getDoctrine()->getRepository(Excursion::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($excursion);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_excursion_index');
    } */


    #[Route('/{excursion}/{id}/edit/excursion', name: 'app_excursion_edit_agence', methods: ['GET', 'POST'])]
    public function editExcursionAgence(Request $request, int $id, AgenceRepository $agenceRepository, Excursion $excursion, ExcursionRepository $excursionRepository, KernelService $kernelService): Response
    {
        $agence = $agenceRepository->find($id);
        $form = $this->createForm(ExcursionAgenceType::class, $excursion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadExcursionPicture($myFile);
            $excursion->setPicture($fileName);

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
                $excursion->addImage($img);
            }

            $excursion->setAgence($agence);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($excursion);
            $entityManager->flush();

            return $this->redirectToRoute('app_excursion_index', ['id' => $excursion->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/excursion/formAgence.html.twig', [
            'excursion' => $excursion,
            'form' => $form,
        ]);
    }
}
