<?php

namespace App\Controller;

use App\Entity\GoodAddress;
use App\Entity\Pictures;
use App\Form\GoodAddressType;
use App\Repository\GoodAddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\KernelService;

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

    public function maMethode()
    {
        // Récupérez les données nécessaires depuis la base de données, un service, etc.

        return $this->render('destination/good_address/index.html.twig'
            // Autres variables à transmettre à la vue
        );
    }

    #[Route('/new', name: 'app_good_address_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GoodAddressRepository $goodAddressRepository , KernelService $kernelService): Response
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

    #[Route('/{id}', name: 'app_good_address_show', methods: ['GET'])]
    public function show(GoodAddress $goodAddress): Response
    {
        return $this->render('good_address/show.html.twig', [
            'good_address' => $goodAddress,
        ]);
    }

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


}
