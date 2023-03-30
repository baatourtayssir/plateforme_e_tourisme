<?php

namespace App\Entity;

use App\Repository\CruiseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CruiseRepository::class)]
class Cruise extends Offer
{
   /*  #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; */

    #[ORM\ManyToMany(targetEntity: Country::class, inversedBy: 'cruises')]
    private Collection $countries;

    public function __construct()
    {
        $this->countries = new ArrayCollection();
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

   
}
