<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Entity\Offer;
use App\Entity\Reservation;
use App\Form\AgenceType;
use App\Form\OfferType;
use App\Form\ReservationType;
use App\Repository\AgenceRepository;
use App\Repository\ReservationRepository;
use App\Repository\OfferRepository;
use App\Service\KernelService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


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


    /*  #[Route('/espace/agence/show/{id}', name: 'show_agency_')]
    public function show($id)
    {
        $agence = $this->getDoctrine()->getRepository(Agence::class)
            ->find($id);

        return $this->render('admin/agence/show.html.twig', array('agence' => $agence));
    } */

    #[Route('/', name: 'app_agence_index', methods: ['GET'])]
    public function index(AgenceRepository $agenceRepository): Response
    {
        return $this->render('admin/agence/index.html.twig', [
            'agences' => $agenceRepository->findAll(),
        ]);
    }

    #[Route('/form', name: 'app_agence_form', methods: ['GET'])]
    public function form(AgenceRepository $agenceRepository): Response
    {
        return $this->render('admin/agence/form.html.twig', [
            'agences' => $agenceRepository->findAll(),
        ]);
    }



    #[Route('/{id}/show', name: 'app_agence_show', methods: ['GET', 'POST'])]
    public function show(Request $request, int $id, ReservationRepository $reservationRepository, OfferRepository $offerRepository, AgenceRepository $agenceRepository, KernelService $kernelService)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $agence = $entityManager->getRepository(Agence::class)->find($id);
        $offers = $this->getDoctrine()->getRepository(Offer::class)->findBy([
            'agence' => $agence,
        ]);
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findBy([
            'agence' => $agence,
        ]);
/*         $reservation = $entityManager->getRepository(Reservation::class)->findAll();
 */
        $offer = $entityManager->getRepository(Offer::class)->findAll();

        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $myFile = $form['brochurefilename']->getData();
            if ($myFile) {
                $fileName = $kernelService->upload($myFile);
                $agence->setBrochurefilename($fileName);     
                   }

            $entityManager->persist($agence);
            $entityManager->flush();

            return $this->redirectToRoute('app_agence_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        $formView = $form->createView(); 

        return $this->render('admin/agence/show.html.twig', [
            'agence' => $agence,
            'offers' => $offers,
            'offer' => $offer,
            'reservations' => $reservations,
/*             'reservation' => $reservation,
 */            'form' => $formView,
          
        ]);
    }



    #[Route('/new', name: 'app_agence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AgenceRepository $agenceRepository, KernelService $kernelService): Response
    {
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);
        /*  $agent = $this->getUser();
        $role[] = $agent->getRoles(); */
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
    public function edit(Request $request, Agence $agence, AgenceRepository $agenceRepository, KernelService $kernelService, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $agence = $entityManager->getRepository(Agence::class)->find($id);
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);
        $agent = $this->getUser();
        $role[] = $agent->getRoles();
        if ($form->isSubmitted() && $form->isValid()) {

            $myFile = $form['brochurefilename']->getData();

            $fileName = $kernelService->upload($myFile);
            $agence->setBrochurefilename($fileName);

            $entityManager->persist($agence);
            $entityManager->flush();
         

            return $this->redirectToRoute('app_agence_index', [], Response::HTTP_SEE_OTHER);


        }

        return $this->renderForm('admin/agence/form.html.twig', [

            'agence' => $agence,
            'form' => $form,

        ]);
    }



    /*     #[Route('/{id}/show', name: 'app_agence_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Agence $agence, AgenceRepository $agenceRepository, KernelService $kernelService, int $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $agence = $entityManager->getRepository(Agence::class)->find($id);
        $offers = $this->getDoctrine()->getRepository(Offer::class)->findBy([
            'agence' => $agence,
        ]);
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $myFile = $form['brochurefilename']->getData();
            $fileName = $kernelService->upload($myFile);
            $agence->setBrochurefilename($fileName);
            $entityManager->persist($agence);
            $entityManager->flush();

            return $this->redirectToRoute('app_agence_form', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/agence/show.html.twig', [
            'agence' => $agence,
            'offers' => $offers,
            'form' => $form,

        ]);
    }
 */




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
