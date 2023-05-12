<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\KernelService;


#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('admin/client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClientRepository $clientRepository, UserPasswordHasherInterface $userPasswordHasher, KernelService $kernelService): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        $client->setRoles(['ROLE_CLIENT']);
        
            if ($form->isSubmitted() && $form->isValid()) {
    
                $myFile = $form['avatar']->getData();
                if ($myFile) {
                    $fileName = $kernelService->loadProfileClient($myFile);
                    $client->setAvatar($fileName);
                }
    
    
                $client->setPassword(
                    $userPasswordHasher->hashPassword(
                        $client,
                        $form->get('password')->getData()
                    )
                );
    

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($client);
                $entityManager->flush();
       
                    return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
              
            }
    

        return $this->renderForm('admin/client/form.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('admin/client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, ClientRepository $clientRepository, UserPasswordHasherInterface $userPasswordHasher, KernelService $kernelService): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        $client->setRoles(['ROLE_CLIENT']);


        if ($form->isSubmitted() && $form->isValid()) {

            $myFile = $form['avatar']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadProfileClient($myFile);
                $client->setAvatar($fileName);
            }


           

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/client/form.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_client_delete')]
    public function delete(Request $request, $id): Response
    {
        $client = $this->getDoctrine()->getRepository(Client::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($client);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_client_index');
    }

}
