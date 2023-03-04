<?php

namespace App\Controller;


use App\Entity\Agence;
use App\Entity\Agent;
use App\Entity\User;
use App\Form\AgenceType;
use App\Form\RegisterAgentType;
use App\Form\UserType as FormUserType;
use App\Service\KernelService;
use App\Security\UsersAuthenticator;
use App\Security\AgencyAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(FormUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }





    #[Route('/agent/register', name: 'app_agent_register')]
    public function registerAgency(Request $request, UserPasswordHasherInterface $userPasswordHasher, KernelService $kernelService, UserAuthenticatorInterface $userAuthenticator, AgencyAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $agent = new Agent();
        /*  $agence = new Agence(); */
        /*  $agence->addAgent($agent); */
        $form = $this->createForm(RegisterAgentType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $agent->setPassword(
                $userPasswordHasher->hashPassword(
                    $agent,
                    $form->get('password')->getData()
                )
            );

            $agent->setRoles(["ROLE_USER","ROLE_SUPER_AGENT"]);
           
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($agent);
            $entityManager->flush();

            return $userAuthenticator->authenticateUser(
                $agent,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register_agent.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
