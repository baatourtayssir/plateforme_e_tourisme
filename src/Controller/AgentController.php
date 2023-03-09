<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Entity\Agent;
use App\Form\AgentType;
use App\Form\AgentSansAgenceType;
use App\Repository\AgentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\KernelService;


#[Route('/agent')]
class AgentController extends AbstractController
{



    #[Route('/', name: 'app_agent_index', methods: ['GET'])]
    public function index(AgentRepository $agentRepository): Response
    {
        return $this->render('admin/agent/index.html.twig', [
            'agents' => $agentRepository->findAll(),
        ]);
    }

    /*     #[Route('/show/agency', name: 'app_agent_sans_agence_index', methods: ['GET'])]
    public function index_sans_agence(AgentRepository $agentRepository): Response
    {
        return $this->render('admin/agence/show_agency.html.twig', [
            'agents' => $agentRepository->findAll(),
        ]);
    } */



    /* 
    #[Route('/new_agent/{id}', name: 'app_agentSansAgence_new', methods: ['GET', 'POST'])]
    public function new_agent($id,Request $request, AgentRepository $agentRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {  
       
        $agent = $this->getDoctrine()->getRepository(Agent::class)
            ->find($id);
        $agent = new Agent();
        
        $form = $this->createForm(AgentSansAgenceType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $agent->setPassword(
                $userPasswordHasher->hashPassword(
                    $agent,
                    $form->get('password')->getData()
                )
            );
            $agent->setRoles(["ROLE_USER", "ROLE_AGENT"]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($agent);
            $entityManager->flush();
        
            return $this->redirectToRoute('show_agency_', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/agent/form.html.twig', [
            'agent' => $agent,
            'form' => $form,
        ]);
    }


    #[Route('/edit_agent/{id}', name: 'app_agentSansAgence_edit', methods: ['GET', 'POST'])]
    public function edit_agent(Request $request, Agent $agent, AgentRepository $agenceRepository): Response
    {
        $form = $this->createForm(AgentSansAgenceType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($agent);
            $entityManager->flush();

            return $this->redirectToRoute('app_agent_sans_agence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/agent/form_sans_agence.html.twig', [
            'agent' => $agent,
            'form' => $form,
        ]);
    } */



    #[Route('/new', name: 'app_agent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AgentRepository $agentRepository, UserPasswordHasherInterface $userPasswordHasher, KernelService $kernelService): Response
    {
        $agent = new Agent();

        $form = $this->createForm(AgentType::class, $agent);
        $form->handleRequest($request);
        $agent->setRoles(['ROLE_AGENT']);


        if ($form->isSubmitted() && $form->isValid()) {

            $myFile = $form['avatar']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadProfile($myFile);
                $agent->setAvatar($fileName);
            }


            $agent->setPassword(
                $userPasswordHasher->hashPassword(
                    $agent,
                    $form->get('password')->getData()
                )
            );


            $agent->setRoles(array_merge($agent->getRoles(), $form->get('roles')->getData()));


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($agent);
            $entityManager->flush();

            return $this->redirectToRoute('app_agent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/agent/form.html.twig', [
            'agent' => $agent,
            'form' => $form,
        ]);
    }





    #[Route('/edit/{id}', name: 'app_agent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agent $agent, AgentRepository $agenceRepository, KernelService $kernelService): Response
    {
        $form = $this->createForm(AgentType::class, $agent);
        $form->handleRequest($request);
        $agent->setRoles(['ROLE_AGENT']);


        if ($form->isSubmitted() && $form->isValid()) {

            $myFile = $form['avatar']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadProfile($myFile);
                $agent->setAvatar($fileName);
            }


            $agent->setRoles(array_merge($agent->getRoles(), $form->get('roles')->getData()));
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($agent);
            $entityManager->flush();

            return $this->redirectToRoute('app_agent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/agent/form.html.twig', [
            'agent' => $agent,
            'form' => $form,
        ]);
    }




    #[Route('/delete/{id}', name: 'app_agent_delete')]
    public function delete(Request $request, $id): Response
    {
        $agent = $this->getDoctrine()->getRepository(Agent::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($agent);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_agent_index');
    }
}
