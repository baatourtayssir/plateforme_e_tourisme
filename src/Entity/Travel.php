<?php

namespace App\Entity;

use App\Repository\TravelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TravelRepository::class)]
class Travel extends Offer
{
   /*  #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; */

    #[ORM\ManyToMany(targetEntity: Country::class, inversedBy: 'travel')]
    public Collection $countries;

    #[ORM\ManyToMany(targetEntity: TravelExcursion::class, mappedBy: 'travels')]
    private Collection $travelExcursions;

    public function __construct()
    {
        parent::__construct();
        $this->countries = new ArrayCollection();
        $this->travelExcursions = new ArrayCollection();

    }

  /*   public function getId(): ?int
    {
        return $this->id;
    } */

    /**
     * @return Collection<int, Country>
     */
    public function getCountries(): Collection
    {
        return $this->countries;
    }

    public function addCountry(Country $country): self
    {
        if (!$this->countries->contains($country)) {
            $this->countries->add($country);
        }

        return $this;
    }

    public function removeCountry(Country $country): self
    {
        $this->countries->removeElement($country);

        return $this;
    }
 
    public function __toString(){
        return $this->title; 
    }

        /**
     * @return Collection<int, OfferExcursion>
     */
    public function getTravelExcursions(): Collection
    {
        return $this->travelExcursions;
    }

    public function addTravelExcursion(TravelExcursion $travelExcursion): self
    {
        if (!$this->travelExcursions->contains($travelExcursion)) {
            $this->travelExcursions->add($travelExcursion);
            $travelExcursion->addTravel($this);
        }

        return $this;
    }

    public function removeOfferExcursion(TravelExcursion $travelExcursion): self
    {
        if ($this->travelExcursions->removeElement($travelExcursion)) {
            $travelExcursion->removeTravel($this);
        }

        return $this;
    }
  
}
