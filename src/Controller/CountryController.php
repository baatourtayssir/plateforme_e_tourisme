<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\KernelService;

#[Route('/country')]
class CountryController extends AbstractController
{
    #[Route('/', name: 'app_country_index', methods: ['GET'])]
    public function index(CountryRepository $countryRepository): Response
    {
        return $this->render('destination/country/index.html.twig', [
            'countries' => $countryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_country_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CountryRepository $countryRepository, KernelService $kernelService): Response
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadPictureCountry($myFile);
                $country->setPicture($fileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($country);
            $entityManager->flush();


            return $this->redirectToRoute('app_country_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('destination/country/form.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_country_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Country $country, CountryRepository $countryRepository, KernelService $kernelService): Response
    {
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $myFile = $form['picture']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadPictureCountry($myFile);
                $country->setPicture($fileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($country);
            $entityManager->flush();


            return $this->redirectToRoute('app_country_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('destination/country/form.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_country_delete')]
    public function delete(Request $request, $id): Response
    {


        $country = $this->getDoctrine()->getRepository(Country::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($country);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_country_index');
    }
}
