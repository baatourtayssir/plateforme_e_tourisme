<?php

namespace App\Controller;

use App\Entity\Cruise;
use App\Entity\GoodAddress;
use App\Entity\Pictures;
use App\Entity\Excursion;
use App\Entity\Hiking;
use App\Entity\Offer;
use App\Entity\Omra;
use App\Entity\Travel;
use App\Form\GoodAddressType;
use App\Repository\AgenceRepository;
use App\Repository\CruiseRepository;
use App\Repository\ExcursionRepository;
use App\Repository\GoodAddressRepository;
use App\Repository\HikingRepository;
use App\Repository\OfferRepository;
use App\Repository\OmraRepository;
use App\Repository\TravelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\KernelService;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/good/address')]
class GoodAddressController extends AbstractController
{
    #[Route('/', name: 'app_good_address_index', methods: ['GET'])]
    public function index(GoodAddressRepository $goodAddressRepository): Response
    {

        return $this->render('destination/good_address/index.html.twig', [
            'good_addresses' => $goodAddressRepository->findAll(),
        ]);
    }




     #[Route('/show', name: 'app_good_address_show', methods: ['GET'])]
    public function show($offer): Response
    {
        $good_addresses = $offer->getGoodAddress();

      /*   $good_addresses = $this->getDoctrine()->getRepository(GoodAddress::class)->findByRegions($regions); */

        return $this->render('destination/good_address/show.html.twig', [
            'good_addresses' => $good_addresses,
            'offer' => $offer,

        ]);
    }

 /*    #[Route('/show', name: 'app_good_address_show', methods: ['GET'])]
    public function show(Offer $offer, int $id, GoodAddressRepository $goodAddressRepository): Response
    {
        if ($offer instanceof Excursion) {
            $excursion = $this->getDoctrine()->getRepository(Excursion::class)->findOneBy(['id' => $id]);
            $regions = $excursion->getRegions();
        } elseif ($offer instanceof Hiking) {
            $hiking = $this->getDoctrine()->getRepository(Hiking::class)->findOneBy(['id' => $id]);
            $regions = [$hiking->getRegion()];
        } elseif ($offer instanceof Cruise) {
            $cruise = $this->getDoctrine()->getRepository(Cruise::class)->findOneBy(['id' => $id]);
            $countries = $cruise->getCountries();
            $regions = [];
            foreach ($countries as $country) {
                $regions = array_merge($regions, $country->getRegions());
            }
        } elseif ($offer instanceof Travel) {
            $travel = $this->getDoctrine()->getRepository(Travel::class)->findOneBy(['id' => $id]);
            $countries = $travel->getCountries();
            $regions = [];
            foreach ($countries as $country) {
                $regions = array_merge($regions, $country->getRegions());
            }
        } else {
            throw new \InvalidArgumentException("Invalid offer type");
        }
    
        $regions = array_unique(array_map(function ($region) {
            return $region->getIntitule();
        }, $regions->toArray()));
    
        $goodAddresses = $goodAddressRepository->findByRegions($regions);
    
        return $this->render('destination/good_address/show.html.twig', [
            'good_addresses' => $goodAddresses,
            'offer' => $offer,
        ]);
    }
     */




    public function maMethode()
    {
        // Récupérez les données nécessaires depuis la base de données, un service, etc.

        return $this->render(
            'destination/good_address/index.html.twig'
            // Autres variables à transmettre à la vue
        );
    }

    #[Route('/new', name: 'app_good_address_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GoodAddressRepository $goodAddressRepository, KernelService $kernelService): Response
    {
        $goodAddress = new GoodAddress();
        $form = $this->createForm(GoodAddressType::class, $goodAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadGoodAdressePicture($myFile);
            $goodAddress->setPicture($fileName);

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_GoodAdress_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $goodAddress->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($goodAddress);
            $entityManager->flush();

            return $this->redirectToRoute('app_good_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('destination/good_address/form.html.twig', [
            'good_address' => $goodAddress,
            'form' => $form,
        ]);
    }

    /*  #[Route('/{id}', name: 'app_good_address_show', methods: ['GET'])]
    public function show(GoodAddress $goodAddress): Response
    {
        return $this->render('good_address/show.html.twig', [
            'good_address' => $goodAddress,
        ]);
    } */


    #[Route('/{id}', name: 'show_good_address_')]
    public function showGoodAddress($id)
    {
        $good_address = $this->getDoctrine()->getRepository(GoodAddress::class)
            ->find($id);
        return $this->render('destination/good_address/showDetails.html.twig', array('good_address' => $good_address));
    }



    #[Route('/{id}/edit', name: 'app_good_address_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GoodAddress $goodAddress, GoodAddressRepository $goodAddressRepository, KernelService $kernelService): Response
    {
        $form = $this->createForm(GoodAddressType::class, $goodAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadGoodAdressePicture($myFile);
            $goodAddress->setPicture($fileName);

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_GoodAdress_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $goodAddress->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($goodAddress);
            $entityManager->flush();

            return $this->redirectToRoute('app_good_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('destination/good_address/form.html.twig', [
            'good_address' => $goodAddress,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_good_address_delete')]
    public function delete(Request $request, $id): Response
    {
        $goodAddress = $this->getDoctrine()->getRepository(GoodAddress::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($goodAddress);
        $entityManager->flush();

        $response = new Response();
        $response->send();
        return $this->redirectToRoute('app_good_address_index');
    }


    #[Route('{offer}/goodaddress/{id}/delete', name: 'app_good_address_delete_good_address')]
    public function deleteGoodAddress(Offer $offer, int $id, OfferRepository $offerRepository): Response
    {
        $goodAddress = $this->getDoctrine()->getRepository(GoodAddress::class)->find($id);

        // Vérifie que l'adresse est bien associée à l'offre
        if (!$offer->getGoodAddress()->contains($goodAddress)) {
            throw $this->createNotFoundException('Good address not found');
        }

        // Supprime la bonne adresse de l'offre courante
        $offer->removeGoodAddress($goodAddress);

        // Enregistre les changements dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Redirige l'utilisateur vers la page d'affichage de l'offre
        $template = '';
        if ($offer instanceof Excursion) {
            $template = 'app_excursion_show';
        } elseif ($offer instanceof Omra) {
            $template = 'app_omra_show';
        } elseif ($offer instanceof Hiking) {
            $template = 'app_hiking_show';
        } elseif ($offer instanceof Cruise) {
            $template = 'app_cruise_show';
        } elseif ($offer instanceof Travel) {
            $template = 'app_travel_show';
        } else {
            throw $this->createNotFoundException('Unsupported offer type');
        }

        return $this->redirectToRoute($template, ['id' => $offer->getId()]);
    }







    #[Route('/{id}/new', name: 'app_excursion_new_good_address', methods: ['GET', 'POST'])]
    public function newGoodAddress(Request $request, TravelRepository $travelRepository, CruiseRepository $cruiseRepository, OfferRepository $offerRepository, HikingRepository $hikingRepository, ExcursionRepository $excursionRepository, OmraRepository $omraRepository, int $id, GoodAddressRepository $goodAddressRepository, KernelService $kernelService): Response
    {
        $goodAddress = new GoodAddress();
        $form = $this->createForm(GoodAddressType::class, $goodAddress);
        $form->handleRequest($request);

        /* $offer = $offerRepository->find($id); */
        $offer = $offerRepository->findOneBy(['id' => $id]);
        if (!$offer) {
            throw $this->createNotFoundException('L\'offre avec l\'id ' . $id . ' n\'existe pas');
        }

        if ($offer instanceof Excursion) {
            $excursion = $excursionRepository->findOneBy(['id' => $id]);
            if (!$excursion) {
                throw $this->createNotFoundException('Excursion not found');
            }
            $template = 'app_excursion_show';
        } elseif ($offer instanceof Omra) {
            $omra = $omraRepository->findOneBy(['id' => $id]);
            if (!$omra) {
                throw $this->createNotFoundException('Omra not found');
            }
            $template = 'app_omra_show';
        } elseif ($offer instanceof Hiking) {
            $hiking = $hikingRepository->findOneBy(['id' => $id]);
            if (!$hiking) {
                throw $this->createNotFoundException('Hiking not found');
            }
            $template = 'app_hiking_show';
        } elseif ($offer instanceof Cruise) {
            $cruise = $cruiseRepository->findOneBy(['id' => $id]);
            if (!$cruise) {
                throw $this->createNotFoundException('Cruise not found');
            }
            $template = 'app_cruise_show';
        } elseif ($offer instanceof Travel) {
            $travel = $travelRepository->findOneBy(['id' => $id]);
            if (!$travel) {
                throw $this->createNotFoundException('Travel not found');
            }
            $template = 'app_travel_show';
        } else {
            throw $this->createNotFoundException('Unsupported offer type');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form['picture']->getData();

            $fileName = $kernelService->loadGoodAdressePicture($myFile);
            $goodAddress->setPicture($fileName);

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('pictures_GoodAdress_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Pictures();
                $img->setName($fichier);
                $goodAddress->addImage($img);
            }

            // Ajout de la nouvelle GoodAddress à l'Offre
            $offer->addGoodAddress($goodAddress);

            // Enregistrement de l'Offre et de la nouvelle GoodAddress
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->persist($goodAddress);
            $entityManager->flush();

            return $this->redirectToRoute($template, ['id' => $offer->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('destination/good_address/form.html.twig', [
            'good_address' => $goodAddress,
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }


    #[Route('{offer}/goodaddress/{id}/add', name: 'app_good_address_add_good_address')]
    public function AddGoodAddress(Offer $offer, int $id, OfferRepository $offerRepository): Response
    {
        $goodAddress = $this->getDoctrine()->getRepository(GoodAddress::class)->find($id);


        $offer->addGoodAddress($goodAddress);

        // Enregistre les changements dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Redirige l'utilisateur vers la page d'affichage de l'offre
        $template = '';
        if ($offer instanceof Excursion) {
            $template = 'app_excursion_show';
        } elseif ($offer instanceof Omra) {
            $template = 'app_omra_show';
        } elseif ($offer instanceof Hiking) {
            $template = 'app_hiking_show';
        } elseif ($offer instanceof Cruise) {
            $template = 'app_cruise_show';
        } elseif ($offer instanceof Travel) {
            $template = 'app_travel_show';
        } else {
            throw $this->createNotFoundException('Unsupported offer type');
        }

        return $this->redirectToRoute($template, ['id' => $offer->getId()]);
    }
}
