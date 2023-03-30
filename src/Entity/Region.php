<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use App\Entity\Country;
use App\Entity\Hotel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $intitule = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\ManyToOne(inversedBy: 'regions')]
    private ?Country $Country = null;

    #[ORM\ManyToMany(targetEntity: Excursion::class, mappedBy: 'regions')]
    private Collection $excursions;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: GoodAddress::class)]
    private Collection $goodAddresses;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: Hotel::class)]
    private Collection $hotel;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: Hiking::class)]
    private Collection $hikings;

    public function __construct()
    {
        $this->excursions = new ArrayCollection();
        $this->goodAddresses = new ArrayCollection();
        $this->hotel = new ArrayCollection();
        $this->hikings = new ArrayCollection();
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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $categry): self
    {
        $this->category = $categry;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->Country;
    }

    public function setCountry(?Country $Country): self
    {
        $this->Country = $Country;

        return $this;
    }

    /**
     * @return Collection<int, Excursion>
     */
    public function getExcursions(): Collection
    {
        return $this->excursions;
    }

    public function addExcursion(Excursion $excursion): self
    {
        if (!$this->excursions->contains($excursion)) {
            $this->excursions->add($excursion);
            $excursion->addRegion($this);
        }

        return $this;
    }

    public function removeExcursion(Excursion $excursion): self
    {
        if ($this->excursions->removeElement($excursion)) {
            $excursion->removeRegion($this);
        }

        return $this;
    }

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
            $goodAddress->setRegion($this);
        }

        return $this;
    }

    public function removeGoodAddress(GoodAddress $goodAddress): self
    {
        if ($this->goodAddresses->removeElement($goodAddress)) {
            // set the owning side to null (unless already changed)
            if ($goodAddress->getRegion() === $this) {
                $goodAddress->setRegion(null);
            }
        }

        return $this;
    }

        /**
     * @return Collection<int, GoodAddress>
     */
    public function getHotels(): Collection
    {
        return $this->hotel;
    }

    public function addHotel(Hotel $hotel): self
    {
        if (!$this->hotel->contains($hotel)) {
            $this->hotel->add($hotel);
            $hotel->setRegion($this);
        }

        return $this;
    }

    public function removeHotel(Hotel $hotel): self
    {
        if ($this->hotel->removeElement($hotel)) {
            // set the owning side to null (unless already changed)
            if ($hotel->getRegion() === $this) {
                $hotel->setRegion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hiking>
     */
    public function getHikings(): Collection
    {
        return $this->hikings;
    }

    public function addHiking(Hiking $hiking): self
    {
        if (!$this->hikings->contains($hiking)) {
            $this->hikings->add($hiking);
            $hiking->setRegion($this);
        }

        return $this;
    }

    public function removeHiking(Hiking $hiking): self
    {
        if ($this->hikings->removeElement($hiking)) {
            // set the owning side to null (unless already changed)
            if ($hiking->getRegion() === $this) {
                $hiking->setRegion(null);
            }
        }

        return $this;
    }

}
