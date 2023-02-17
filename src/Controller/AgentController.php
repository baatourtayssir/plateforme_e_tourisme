<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Form\AgentType;
use App\Repository\AgentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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


    #[Route('/new', name: 'app_agent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AgentRepository $agentRepository): Response
    {
        $agent = new Agent();
        $form = $this->createForm(AgentType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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



    #[Route('/{id}/edit', name: 'app_agent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agent $agent, AgentRepository $agentRepository): Response
    {
        $form = $this->createForm(AgentType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
