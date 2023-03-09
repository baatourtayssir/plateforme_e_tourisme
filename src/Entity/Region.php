<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use App\Entity\Country;
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
    private ?string $entitled = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\ManyToOne(inversedBy: 'regions')]
    private ?Country $Country = null;

    #[ORM\ManyToMany(targetEntity: Excursion::class, mappedBy: 'regions')]
    private Collection $excursions;

    public function __construct()
    {
        $this->excursions = new ArrayCollection();
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

}
