<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use App\Entity\Region;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $intitule = null;


    #[ORM\OneToMany(mappedBy: 'Country', targetEntity: Region::class)]
    private Collection $regions;


    #[ORM\ManyToMany(targetEntity: Offer::class, mappedBy: 'countries')]
    public Collection $offers;

    #[ORM\ManyToMany(targetEntity: Cruise::class, mappedBy: 'countries')]
    public Collection $cruises;

    #[ORM\ManyToMany(targetEntity: Travel::class, mappedBy: 'countries')]
    public Collection $travel;

    #[ORM\ManyToOne(inversedBy: 'countries')]
    private ?Geographical $geographical = null;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
        $this->cruises = new ArrayCollection();
        $this->travel = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
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
            $region->setCountry($this);
        }

        return $this;
    }

    public function removeRegion(Region $region): self
    {
        if ($this->regions->removeElement($region)) {
            // set the owning side to null (unless already changed)
            if ($region->getCountry() === $this) {
                $region->setCountry(null);
            }
        }

        return $this;
    }
    // public function __toString()
    // {
    //     return (string) $this->intitule;
    // }



    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
            $offer->addCountry($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            $offer->removeCountry($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Cruise>
     */
    public function getCruises(): Collection
    {
        return $this->cruises;
    }

    public function addCruise(Cruise $cruise): self
    {
        if (!$this->cruises->contains($cruise)) {
            $this->cruises->add($cruise);
            $cruise->addCountry($this);
        }

        return $this;
    }

    public function removeCruise(Cruise $cruise): self
    {
        if ($this->cruises->removeElement($cruise)) {
            $cruise->removeCountry($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Travel>
     */
    public function getTravel(): Collection
    {
        return $this->travel;
    }

    public function addTravel(Travel $travel): self
    {
        if (!$this->travel->contains($travel)) {
            $this->travel->add($travel);
            $travel->addCountry($this);
        }

        return $this;
    }

    public function removeTravel(Travel $travel): self
    {
        if ($this->travel->removeElement($travel)) {
            $travel->removeCountry($this);
        }

        return $this;
    }

    public function getGeographical(): ?Geographical
    {
        return $this->geographical;
    }

    public function setGeographical(?Geographical $geographical): self
    {
        $this->geographical = $geographical;

        return $this;
    }



  

}
