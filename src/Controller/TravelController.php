<?php

namespace App\Controller;

use App\Entity\Pictures;
use App\Entity\Travel;
use App\Form\TravelType;
use App\Repository\TravelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\KernelService;


#[Route('/travel')]
class TravelController extends AbstractController
{
    #[Route('/', name: 'app_travel_index', methods: ['GET'])]
    public function index(TravelRepository $travelRepository): Response
    {
        return $this->render('offer/travel/index.html.twig', [
            'travels' => $travelRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_travel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, KernelService $kernelService, TravelRepository $travelRepository): Response
    {
        $travel = new Travel();
        $form = $this->createForm(TravelType::class, $travel);
        $form->handleRequest($request);
       /*  dd($travel); */

        if ($form->isSubmitted() && $form->isValid()) {
           
            $myFile = $form['picture']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadTravelPicture($myFile);
                $travel->setPicture($fileName);
            }

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('Travel_Pictures_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $travel->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($travel);
            $entityManager->flush();
            return $this->redirectToRoute('app_travel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/travel/form.html.twig', [
            'travel' => $travel,
            'form' => $form,
        ]);
    }

/*     #[Route('/{id}', name: 'app_travel_show', methods: ['GET'])]
    public function show(Travel $travel): Response
    {
        return $this->render('travel/show.html.twig', [
            'travel' => $travel,
        ]);
    } */

    #[Route('/{id}/edit', name: 'app_travel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Travel $travel, KernelService $kernelService, TravelRepository $travelRepository): Response
    {
        $form = $this->createForm(TravelType::class, $travel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadTravelPicture($myFile);
                $travel->setPicture($fileName);
            }

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('Travel_Pictures_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $travel->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($travel);
            $entityManager->flush();
            return $this->redirectToRoute('app_travel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/travel/form.html.twig', [
            'travel' => $travel,
            'form' => $form,
        ]);
    }

    #[Route('{id}/delete', name: 'app_travel_delete')]
    public function delete(Request $request,$id): Response
    {
        $travel = $this->getDoctrine()->getRepository(Travel::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($travel);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_travel_index');
    }
}
