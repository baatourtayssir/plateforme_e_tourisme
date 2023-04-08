<?php

namespace App\Controller;

use App\Entity\GoodAddress;
use App\Entity\Pictures;
use App\Entity\Excursion;
use App\Form\GoodAddressType;
use App\Repository\AgenceRepository;
use App\Repository\ExcursionRepository;
use App\Repository\GoodAddressRepository;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\KernelService;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/good/address')]
class GoodAddressController extends AbstractController
{
    #[Route('/', name: 'app_good_address_index', methods: ['GET'])]
    public function index(GoodAddressRepository $goodAddressRepository): Response
    {

        return $this->render('destination/good_address/index.html.twig', [
            'good_addresses' => $goodAddressRepository->findAll(),
        ]);
    }

    #[Route('/show', name: 'app_good_address_show', methods: ['GET'])]
    public function show($excursion): Response
    {
        $good_addresses = $excursion->getGoodAddress();
        /*         $form = $this->createForm(GoodAddressType::class, $goodAddress);
 */

        return $this->render('destination/good_address/show.html.twig', [
            'good_addresses' => $good_addresses,

        ]);
    }


    public function maMethode()
    {
        // Récupérez les données nécessaires depuis la base de données, un service, etc.

        return $this->render(
            'destination/good_address/index.html.twig'
            // Autres variables à transmettre à la vue
        );
    }

    #[Route('/new', name: 'app_good_address_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GoodAddressRepository $goodAddressRepository, KernelService $kernelService): Response
    {
        $goodAddress = new GoodAddress();
        $form = $this->createForm(GoodAddressType::class, $goodAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadGoodAdressePicture($myFile);
            $goodAddress->setPicture($fileName);

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_GoodAdress_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $goodAddress->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($goodAddress);
            $entityManager->flush();

            return $this->redirectToRoute('app_good_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('destination/good_address/form.html.twig', [
            'good_address' => $goodAddress,
            'form' => $form,
        ]);
    }

    /*  #[Route('/{id}', name: 'app_good_address_show', methods: ['GET'])]
    public function show(GoodAddress $goodAddress): Response
    {
        return $this->render('good_address/show.html.twig', [
            'good_address' => $goodAddress,
        ]);
    } */

    #[Route('/{id}/edit', name: 'app_good_address_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GoodAddress $goodAddress, GoodAddressRepository $goodAddressRepository, KernelService $kernelService): Response
    {
        $form = $this->createForm(GoodAddressType::class, $goodAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadGoodAdressePicture($myFile);
            $goodAddress->setPicture($fileName);

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_GoodAdress_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $goodAddress->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($goodAddress);
            $entityManager->flush();

            return $this->redirectToRoute('app_good_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('destination/good_address/form.html.twig', [
            'good_address' => $goodAddress,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_good_address_delete')]
    public function delete(Request $request, $id): Response
    {
        $goodAddress = $this->getDoctrine()->getRepository(GoodAddress::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($goodAddress);
        $entityManager->flush();

        $response = new Response();
        $response->send();
        return $this->redirectToRoute('app_good_address_index');
    }


    /*   #[Route('/{id}/new', name: 'app_excursion_new_good_address', methods: ['GET', 'POST'])]
    public function newGoodAddress(Request $request,Excursion $excursion,int $id, GoodAddressRepository $goodAddressRepository, KernelService $kernelService): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(ExcursionType::class, $excursion);
        $goodAddress = new GoodAddress();
        $form = $this->createForm(GoodAddressType::class, $goodAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadGoodAdressePicture($myFile);
            $goodAddress->setPicture($fileName);

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_GoodAdress_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $goodAddress->addImage($img);
            }

            /*  $excursion = $excursionRepository->find($id);
            $goodAddress->addOffer($excursion); */

    /*    $excursion->addGoodAddress($goodAddress);
            
            $entityManager->persist($excursion);
            $entityManager->persist($goodAddress);
           

            $entityManager->flush();


            return $this->redirectToRoute('app_excursion_show', ['id' => $excursion->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('destination/good_address/form.html.twig', [
            'good_address' => $goodAddress,
            'form' => $form,
        ]);
    } */

    /*   #[Route('/{excursion}/{id}/new-good-address', name: 'app_new_good_address')]
    public function newGoodAddress(Request $request, int $id, Excursion $excursion,GoodAddressRepository $goodAddressRepository, KernelService $kernelService): Response
    {

        // Récupération de l'excursion correspondant à l'ID
        $excursion = $this->getDoctrine()->getRepository(Excursion::class)->find($id);

        // Vérification que l'excursion existe bien
        if (!$excursion) {
            throw $this->createNotFoundException('Excursion not found');
        }

        $entityManager = $this->getDoctrine()->getManager();
        /*         $excursion = $entityManager->getRepository(Excursion::class)->find($id);
 */
    /*    $goodAddress = new GoodAddress();
        $form = $this->createForm(GoodAddressType::class, $goodAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadGoodAdressePicture($myFile);
            $goodAddress->setPicture($fileName);

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_GoodAdress_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $goodAddress->addImage($img);
            }


            $excursion->addGoodAddress($goodAddress);

            // Enregistrer les modifications dans la base de données
            $entityManager->persist($goodAddress);
            $entityManager->persist($excursion);
            $entityManager->flush();

            return $this->redirectToRoute('app_excursion_show', ['id' => $excursion->getId()]);
        }

        return $this->render('destination/good_address/form.html.twig', [
            'good_address' => $goodAddress,
            'form' => $form->createView(),
            'excursion' => $excursion,
        ]);
    }

 */


    #[Route('/{id}/new', name: 'app_excursion_new_good_address', methods: ['GET', 'POST'])]
    public function newGoodAddress(Request $request, OfferRepository $offerRepository, int $id, GoodAddressRepository $goodAddressRepository, KernelService $kernelService): Response
    {
        $goodAddress = new GoodAddress();
        $form = $this->createForm(GoodAddressType::class, $goodAddress);
        $form->handleRequest($request);

        $offer = $offerRepository->find($id);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadGoodAdressePicture($myFile);
            $goodAddress->setPicture($fileName);

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_GoodAdress_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $goodAddress->addImage($img);
            }

            // Ajout de la nouvelle GoodAddress à l'Offre
            $offer->addGoodAddress($goodAddress);

            // Enregistrement de l'Offre et de la nouvelle GoodAddress
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->persist($goodAddress);
            $entityManager->flush();

            return $this->redirectToRoute('app_excursion_show', ['id' => $offer->getId()]);
        }

        return $this->render('destination/good_address/form.html.twig', [
            'good_address' => $goodAddress,
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/excursion/{id}/good-address/new', name: 'add_good_address_to_excursion')]
    public function addGoodAddressToExcursion(Request $request, EntityManagerInterface $em, int $id)
    {
        // Récupérer l'excursion en fonction de l'identifiant
        $excursion = $em->getRepository(Excursion::class)->find($id);

        // Créer un nouveau formulaire pour la bonne adresse
        $goodAddress = new GoodAddress();
        $form = $this->createForm(GoodAddressType::class, $goodAddress);

        // Traiter le formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ajouter la bonne adresse à l'excursion
            $excursion->addGoodAddress($goodAddress);

            // Enregistrer les modifications
            $em->flush();

            // Rediriger vers la page de l'excursion
            return $this->redirectToRoute('app_excursion_show', ['id' => $excursion->getId()]);
        }

        // Afficher le formulaire pour ajouter une bonne adresse
        return $this->render('destination/good_address/form.html.twig', [
            'form' => $form->createView(),
            'excursion' => $excursion,
        ]);
    }
}
