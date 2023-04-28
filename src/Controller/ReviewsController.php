<?php

namespace App\Controller;

use App\Entity\Pictures;
use App\Entity\Reviews;
use App\Entity\User;
use App\Form\ReviewsType;
use App\Repository\ExcursionRepository;
use App\Repository\ReviewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\KernelService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/reviews')]
class ReviewsController extends AbstractController
{

    private $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    #[Route('/show/review/article/{id}', name: 'show_review_')]
    public function show($id)
    {

        $review = $this->getDoctrine()->getRepository(Reviews::class)
            ->find($id);

        $user = $review->getUser();
        $userName = $user->getLastname();
        return $this->render('admin/reviews/show_review.html.twig', [
            'review' => $review,
            'user' => $userName
        ]);
    }


    #[Route('/', name: 'app_reviews_index', methods: ['GET'])]
    public function index(ReviewsRepository $reviewsRepository): Response
    {
        return $this->render('admin/reviews/index.html.twig', [
            'reviews' => $reviewsRepository->findAll(),
        ]);
    }


    #[Route('/', name: 'app_review_show_review_Excursion', methods: ['GET'])]
    public function showReviewsExcursion($excursion): Response
    {
        $reviews = $excursion->getReviews();


        return $this->render('admin/reviews/show.html.twig', [
            'reviews' => $reviews,

        ]);
    }

    #[Route('/', name: 'app_review_show_review_Omra', methods: ['GET'])]
    public function showReviewsToOmra($omra): Response
    {
        $reviews = $omra->getReviews();


        return $this->render('admin/reviews/show.html.twig', [
            'reviews' => $reviews,

        ]);
    }

    #[Route('/', name: 'app_review_show_review_Cruise', methods: ['GET'])]
    public function showReviewsToCruise($cruise): Response
    {
        $reviews = $cruise->getReviews();


        return $this->render('admin/reviews/show.html.twig', [
            'reviews' => $reviews,

        ]);
    }

    #[Route('/hiking', name: 'app_review_show_review_Hiking', methods: ['GET'])]
    public function showReviewsToHiking($hiking): Response
    {
        $reviews = $hiking->getReviews();


        return $this->render('admin/reviews/show.html.twig', [
            'reviews' => $reviews,

        ]);
    }

    #[Route('/travel', name: 'app_review_show_review_travel', methods: ['GET'])]
    public function showReviewsToTravel($travel): Response
    {
        $reviews = $travel->getReviews();


        return $this->render('admin/reviews/show.html.twig', [
            'reviews' => $reviews,

        ]);
    }


    #[Route('/new', name: 'app_reviews_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReviewsRepository $reviewsRepository, KernelService $kernelService): Response
    {
        $review = new Reviews();
        $form = $this->createForm(ReviewsType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            /*  if ($this->getUser() instanceof User) {
                $review->setUser($this->getUser());
            } */
            $user = $form->get('user')->getData();
            $review->setUser($user);

            $myFile = $form['picture']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadpicture($myFile);
                $review->setPicture($fileName);
            }

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $review->addImage($img);
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('app_reviews_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/reviews/form.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }

    /*  #[Route('/show/{id}', name: 'app_reviews_show', methods: ['GET'])]
    public function show(Reviews $review): Response
    {
        return $this->render('reviews/show.html.twig', [
            'review' => $review,
        ]);
    } */

    #[Route('/{id}/edit', name: 'app_reviews_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reviews $review, ReviewsRepository $reviewsRepository, KernelService $kernelService): Response
    {

        $form = $this->createForm(ReviewsType::class, $review);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {


            $myFile = $form['picture']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadpicture($myFile);
                $review->setPicture($fileName);
            }


            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $review->addImage($img);
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('app_reviews_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/reviews/form.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reviews_delete')]
    public function delete(Request $request, $id): Response
    {
        $review = $this->getDoctrine()->getRepository(Reviews::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($review);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_reviews_index');
    }


    #[Route('/delete/pictures/{id}', name: 'reviews_delete_pictures', methods: ['DELETE'])]
    public function deleteImage(Pictures $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            // On récupère le nom de l'image
            $nom = $image->getName();
            // On supprime le fichier
            unlink($this->getParameter('pictures_directory') . '/' . $nom);

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

    #[Route('/{id}/new', name: 'app_reviews_new_reviews', methods: ['GET', 'POST'])]
    public function newReview(Request $request, int $id, ExcursionRepository $excursionRepository, ReviewsRepository $reviewsRepository, KernelService $kernelService): Response
    {
        $review = new Reviews();
        /*         $review->setDate(new \DateTime());
 */
        $form = $this->createForm(ReviewsType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $myFile = $form['picture']->getData();
            if ($myFile) {
                $fileName = $kernelService->loadpicture($myFile);
                $review->setPicture($fileName);
            }

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $review->addImage($img);
            }


            $excursion = $excursionRepository->find($id);
            $review->setOffer($excursion);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('app_excursion_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/reviews/form.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }
}
