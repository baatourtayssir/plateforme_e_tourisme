<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Form\AgenceType;
use App\Repository\AgenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/agence')]
class AgenceController extends AbstractController
{
    #[Route('/', name: 'app_agence_index', methods: ['GET'])]
    public function index(AgenceRepository $agenceRepository): Response
    {
        return $this->render('admin/agence/index.html.twig', [
            'agences' => $agenceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_agence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AgenceRepository $agenceRepository): Response
    {
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //     if (! is_null($agence->getLogo())) {
    //         $Logo = $agence->getLogo();
    //         $fileName = md5(uniqid()).'blog-.'.$file->guessExtension();
    //         $file->move(
    //             $this->getParameter('uploadLogo'),
    //             $fileName
    //         );
    //         $agence->setLogo($fileName);
    // }

         if ($form->isSubmitted() && $form->isValid()) {
        //     $agenceRepository->save($agence, true);
            // dd($_FILES);

            // $uploadedFile= $this->getParameter('uploadLogo' ).$_FILES;
            // move_uploaded_file($_FILES['Logo'],$uploadedFile);
            // $agence->setLogo($_FILES['agence']);
            // $agenceRepository->add($agence);

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


    // #[Route('/new', name: 'app_agence_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, SluggerInterface $slugger)
    // {
    //     $agence = new Agence();
    //     $form = $this->createForm(AgenceType::class, $agence);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         /** @var UploadedFile $Logo */
    //         $Logo = $form->get('Logo')->getData();

    //         // this condition is needed because the 'brochure' field is not required
    //         // so the PDF file must be processed only when a file is uploaded
    //         if ($Logo) {
    //             $originalFilename = pathinfo($Logo->getClientOriginalName(), PATHINFO_FILENAME);
    //             // this is needed to safely include the file name as part of the URL
    //             $safeFilename = $slugger->slug($originalFilename);
    //             $newFilename = $safeFilename . '-' . uniqid() . '.' . $Logo->guessExtension();

    //             // Move the file to the directory where brochures are stored
               
    //                 $Logo->move(
    //                     $this->getParameter('uploadLogo'),
    //                     $newFilename
    //                 );
               
    //             // updates the 'brochureFilename' property to store the PDF file name
    //             // instead of its contents
    //             $agence->setLogo($newFilename);
    //         }

    //         // ... persist the $product variable or any other work

    //         return $this->redirectToRoute('app_agence_index');
    //     }

    //     return $this->render('admin/agence/form.html.twig', [
    //         'agence' => $agence,
    //         'form' => $form,
    //     ]);
    // }







    #[Route('/{id}/edit', name: 'app_agence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agence $agence, AgenceRepository $agenceRepository): Response
    {
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
