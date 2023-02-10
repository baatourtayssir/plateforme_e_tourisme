<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\CrudUsersType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_')]
    public function home(): Response
    {
        return $this->render('admin/home.html.twig');
    }

    #[Route('/add/user', name: 'user_')]
    public function usersList(UserRepository $users){
        return $this->render("admin/users/home.html.twig",[
            'users' =>$users->findAll()
        ]);

    }

    #[Route('/edit/{id}', name: 'app_users_edit')]
    public function edit(User $user,Request $request)
    {
        $form = $this->createForm(CrudUsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this-> getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message','user modify successfully');
            return $this->redirectToRoute('user_');
        }

    //     return $this->renderForm('admin/users/index/edit.html.twig', [
    //         'userForm' => $form->createView()
    //     ]);
    // }



            return $this->renderForm('admin/users/index/edit.html.twig', [
                'user' => $user,
                'form' => $form,
            ]);
        }




        #[Route('/new', name: 'app_users_new', methods: ['GET', 'POST'])]
        public function new(Request $request, UserRepository $userRepository): Response
        
        {
             $user = new User();
             $form = $this->createForm(CrudUsersType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $sousAdmin = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
    
    
    
            return $this->redirectToRoute('user_', [], Response::HTTP_SEE_OTHER);
        }
    
            return $this->renderForm('admin/users/_form.html.twig', [
                'user' => $user,
                'form' => $form,
            ]);
    }




    #[Route('/delete/{id}', name: 'app_users_delete')]
    public function delete(Request $request, $id)
    {
        return $this->renderForm('admin/users/index/_delete_form.html.twig');
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($user);
        $entityManager->flush();

        $response = new Response();
        $response->send();


        return $this->redirectToRoute('user_');
    }

  }