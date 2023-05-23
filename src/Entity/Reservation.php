<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeInterface $dateReservation = null;

                                                                                       
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Offer $offer = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Agence $agence = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?PriceList $priceList = null;


    public function getId(): ?int
    {
        return $this->id;
    }



    public function getDateReservation(): ?DateTimeImmutable
    {
        return $this->dateReservation;
    }

    public function setDateReservation(DateTimeImmutable $dateReservation): self
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    

    #[ORM\PrePersist]
    public function prePersist(LifecycleEventArgs $event): void
    {
        if ($this->dateReservation === null) {
            $this->dateReservation = new DateTimeImmutable();
        }
    }



    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getPriceList(): ?PriceList
    {
        return $this->priceList;
    }

    public function setPriceList(?PriceList $priceList): self
    {
        $this->priceList = $priceList;

        return $this;
    }
}
