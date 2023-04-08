<?php

namespace App\Entity;

use App\Repository\PriceListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PriceListRepository::class)]
class PriceList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToMany(targetEntity: Hotel::class, inversedBy: 'priceLists')]
    #[ORM\JoinTable(name: "price_list_hotel")]
    private Collection $hotels;

/*     #[ORM\ManyToMany(targetEntity: Excursion::class, inversedBy: 'priceLists')]
    #[ORM\JoinTable(name: "price_list_excursion")]
    private Collection $excursions; */

    #[ORM\Column(length: 255)]
    private ?string $prix = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\ManyToOne(inversedBy: 'priceLists')]
    private ?Offer $offer = null;

    public function __construct()
    {
        $this->hotels = new ArrayCollection();
        /* $this->excursions = new ArrayCollection(); */
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Hotel>
     */
    public function getHotels(): Collection
    {
        return $this->hotels;
    }

    public function addHotel(Hotel $hotel): self
    {
        if (!$this->hotels->contains($hotel)) {
            $this->hotels->add($hotel);
        }

        return $this;
    }

    public function removeHotel(Hotel $hotel): self
    {
        $this->hotels->removeElement($hotel);

        return $this;
    }

    /**
     * @return Collection<int, Excursion>
     */
  /*   public function getExcursions(): Collection
    {
        return $this->excursions;
    }

    public function addExcursion(Excursion $excursion): self
    {
        if (!$this->excursions->contains($excursion)) {
            $this->excursions->add($excursion);
        }

        return $this;
    }

    public function removeExcursion(Excursion $excursion): self
    {
        $this->excursions->removeElement($excursion);

        return $this;
    } */

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeImmutable
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeImmutable $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }


    public function getDateFin(): ?\DateTimeImmutable
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeImmutable $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
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
}
