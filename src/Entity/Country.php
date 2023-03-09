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
    private ?string $entitled = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\OneToMany(mappedBy: 'Country', targetEntity: Region::class)]
    private Collection $regions;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: GoodAddress::class)]
    private Collection $goodAddresses;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Hotel::class)]
    private Collection $hotels;

    #[ORM\ManyToMany(targetEntity: Offer::class, mappedBy: 'country')]
    private Collection $offers;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntitled(): ?string
    {
        return $this->entitled;
    }

    public function setEntitled(string $entitled): self
    {
        $this->entitled = $entitled;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

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
    //     return (string) $this->entitled;
    // }

    /**
     * @return Collection<int, GoodAddress>
     */
    public function getGoodAddresses(): Collection
    {
        return $this->goodAddresses;
    }

    public function addGoodAddress(GoodAddress $goodAddress): self
    {
        if (!$this->goodAddresses->contains($goodAddress)) {
            $this->goodAddresses->add($goodAddress);
            $goodAddress->setCountry($this);
        }

        return $this;
    }

    public function removeGoodAddress(GoodAddress $goodAddress): self
    {
        if ($this->goodAddresses->removeElement($goodAddress)) {
            // set the owning side to null (unless already changed)
            if ($goodAddress->getCountry() === $this) {
                $goodAddress->setCountry(null);
            }
        }

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
            $hotel->setCountry($this);
        }

        return $this;
    }

    public function removeHotel(Hotel $hotel): self
    {
        if ($this->hotels->removeElement($hotel)) {
            // set the owning side to null (unless already changed)
            if ($hotel->getCountry() === $this) {
                $hotel->setCountry(null);
            }
        }

        return $this;
    }

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



  

}
