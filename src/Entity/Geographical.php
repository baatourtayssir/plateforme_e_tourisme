<?php

namespace App\Entity;

use App\Repository\GeographicalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GeographicalRepository::class)]
class Geographical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\OneToMany(mappedBy: 'geographical', targetEntity: Country::class)]
    private Collection $countries;

    #[ORM\OneToMany(mappedBy: 'geographical', targetEntity: Region::class)]
    private Collection $regions;

    public function __construct()
    {
        $this->countries = new ArrayCollection();
        $this->regions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

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
            $country->setGeographical($this);
        }

        return $this;
    }

    public function removeCountry(Country $country): self
    {
        if ($this->countries->removeElement($country)) {
            // set the owning side to null (unless already changed)
            if ($country->getGeographical() === $this) {
                $country->setGeographical(null);
            }
        }

        return $this;
    }


     public function __toString()
    {
        return (string) $this->location;
    }

     /**
      * @return Collection<int, Region>
      */
     public function getRegions(): Collection
     {
         return $this->regions;
     }

     public function addRegion(Region $region): self
     {
         if (!$this->regions->contains($region)) {
             $this->regions->add($region);
             $region->setGeographical($this);
         }

         return $this;
     }

     public function removeRegion(Region $region): self
     {
         if ($this->regions->removeElement($region)) {
             // set the owning side to null (unless already changed)
             if ($region->getGeographical() === $this) {
                 $region->setGeographical(null);
             }
         }

         return $this;
     }
}
