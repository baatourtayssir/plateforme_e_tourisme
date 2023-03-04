<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Entity\Agents;
use App\Form\AgenceType;
use App\Form\AgentsType;
use App\Repository\AgenceRepository;
use App\Service\KernelService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/agence')]
class AgenceController extends AbstractController
{



    private $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    #[Route('/espace/agence', name: 'agence_')]
    public function home(): Response
    {
        return $this->render('agence/espace_agence.html.twig');
    }

    #[Route('/', name: 'app_agence_index', methods: ['GET'])]
    public function index(AgenceRepository $agenceRepository): Response
    {
        return $this->render('admin/agence/index.html.twig', [
            'agences' => $agenceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_agence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AgenceRepository $agenceRepository, KernelService $kernelService): Response
    {
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['brochurefilename']->getData();

            $fileName = $kernelService->upload($myFile);
            $agence->setBrochurefilename($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($agence);
            $entityManager->flush();

            return $this->redirectToRoute('app_agence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/agence/form.html.twig', [
            'agence' => $agence,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_agence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agence $agence, AgenceRepository $agenceRepository, KernelService $kernelService): Response
    {
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $myFile = $form['brochurefilename']->getData();

            $fileName = $kernelService->upload($myFile);
            $agence->setBrochurefilename($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($agence);
            $entityManager->flush();

            return $this->redirectToRoute('app_agence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/agence/form.html.twig', [
            'agence' => $agence,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_agence_delete')]
    public function delete(Request $request, $id): Response
    {


        $agence = $this->getDoctrine()->getRepository(Agence::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($agence);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_agence_index');
    }
}
