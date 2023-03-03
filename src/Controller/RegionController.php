<?php

namespace App\Controller;

use App\Entity\Region;
use App\Form\RegionType;
use App\Repository\RegionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/region')]
class RegionController extends AbstractController
{
    #[Route('/', name: 'app_region_index', methods: ['GET'])]
    public function index(RegionRepository $regionRepository): Response
    {
        return $this->render('destination/region/index.html.twig', [
            'regions' => $regionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_region_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RegionRepository $regionRepository): Response
    {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($region);
            $entityManager->flush();

            return $this->redirectToRoute('app_region_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('destination/region/form.html.twig', [
            'region' => $region,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_region_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Region $region, RegionRepository $regionRepository): Response
    {
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($region);
            $entityManager->flush();

            return $this->redirectToRoute('app_region_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('destination/region/form.html.twig', [
            'region' => $region,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_region_delete')]
    public function delete(Request $request, $id): Response
    {


        $region = $this->getDoctrine()->getRepository(Region::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($region);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_region_index');
    }
}
