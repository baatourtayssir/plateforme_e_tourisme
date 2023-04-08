<?php

namespace App\Entity;

use App\Repository\OfferExcursionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfferExcursionRepository::class)]
class OfferExcursion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\ManyToOne(inversedBy: 'offerExcursions')]
    private ?Excursion $excursion = null;

    #[ORM\Column(length: 255)]
    private ?string $prix = null;

    #[ORM\ManyToMany(targetEntity: Offer::class, inversedBy: 'offerExcursions')]
    #[ORM\JoinTable(name: "offer_excursion_offer")]
    private Collection $offers;


    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getExcursion(): ?Excursion
    {
        return $this->excursion;
    }

    public function setExcursion(?Excursion $excursion): self
    {
        $this->excursion = $excursion;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffer(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        $this->offers->removeElement($offer);

        return $this;
    }

  

 
}
