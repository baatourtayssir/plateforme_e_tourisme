<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\KernelService;


#[Route('/admin')]
class AdminController extends AbstractController
{


    #[Route('/', name: 'admin_')]
    public function home(): Response
    {
        return $this->render('base.html.twig');
    }


   
    #[Route('/list/admin', name: 'app_admin_index', methods: ['GET'])]
    public function index(AdminRepository $adminRepository)
    {
        return $this->render("admin/users/index.html.twig", [
            'admins' => $adminRepository->findAll(),
        ]);
    } 

/* 
    #[Route('/', name: 'app_admin_index', methods: ['GET'])]
    public function index(AdminRepository $adminRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'admins' => $adminRepository->findAll(),
        ]);
    } */

    #[Route('/new', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AdminRepository $adminRepository, KernelService $kernelService, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);
       

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['avatar']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadProfile($myFile);
                $admin->setAvatar($fileName);
            }

            $admin->setPassword(
                $userPasswordHasher->hashPassword(
                    $admin,
                    $form->get('password')->getData()
                )
            );
            
            $admin->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->flush();


            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/users/form.html.twig', [
            'admin' => $admin,
            'form' => $form,
        ]);
    }
/* 
    #[Route('/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function show(Admin $admin): Response
    {
        return $this->render('admin/show.html.twig', [
            'admin' => $admin,
        ]);
    } */

    #[Route('/edit/{id}', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Admin $admin, AdminRepository $adminRepository, KernelService $kernelService, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['avatar']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadProfile($myFile);
                $admin->setAvatar($fileName);
            }

            $admin->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->flush();

           
            

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/users/form.html.twig', [
            'admin' => $admin,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_admin_delete')]
    public function delete(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository(Admin::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($user);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_admin_index');
}
}
