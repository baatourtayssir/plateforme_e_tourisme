<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Agence;
use App\Entity\Agent;
use App\Entity\Client;
use App\Entity\User;
use App\Form\AdminType;
use App\Form\ClientType;
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
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, KernelService $kernelService, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new Admin();
        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $myFile = $form['avatar']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadProfile($myFile);
                $user->setAvatar($fileName);
            }
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(["ROLE_ADMIN"]);
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
    public function registerAgency(Request $request, UserPasswordHasherInterface $userPasswordHasher, KernelService $kernelService,  UserAuthenticatorInterface $userAuthenticator, AgencyAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $agent = new Agent();
        /* $agence = new Agence(); */
        /*  $agence->addAgent($agent); */
        $form = $this->createForm(RegisterAgentType::class, $agent);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

           /*  $myFile = $form['brochurefilename']->getData();

            $fileName = $kernelService->upload($myFile);
            $agence->setBrochurefilename($fileName); */

            $myFile = $form['avatar']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadProfile($myFile);
                $agent->setAvatar($fileName);
            }
            // encode the plain password
            $agent->setPassword(
                $userPasswordHasher->hashPassword(
                    $agent,
                    $form->get('password')->getData()
                )
            );
         
            $agent->setRoles(["ROLE_USER", "ROLE_SUPER_AGENT"]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($agent);
            /* $entityManager->persist($agence); */
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


    #[Route('/register/Client', name: 'app_register_client')]
    public function registerClient(Request $request, UserPasswordHasherInterface $userPasswordHasher, KernelService $kernelService, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $myFile = $form['avatar']->getData();
                if ($myFile) {
                    $fileName = $kernelService->loadProfileClient($myFile);
                    $client->setAvatar($fileName);
                }
            // encode the plain password
            $client->setPassword(
                $userPasswordHasher->hashPassword(
                    $client,
                    $form->get('password')->getData()
                )
            );
            $client->setRoles(["ROLE_CLIENT"]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $client,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register_client.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
