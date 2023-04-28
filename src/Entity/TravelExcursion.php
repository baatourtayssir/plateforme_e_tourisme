<?php

namespace App\Entity;

use App\Repository\TravelExcursionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TravelExcursionRepository::class)]
class TravelExcursion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\ManyToOne(inversedBy: 'travelExcursions')]
    public ?Excursion $excursion = null;

    #[ORM\Column(length: 255)]
    private ?string $prix = null;

    #[ORM\ManyToMany(targetEntity: Travel::class, inversedBy: 'travelExcursions')]
    #[ORM\JoinTable(name: "travel_excursion_travel")]
    public Collection $travels;


    public function __construct()
    {
        $this->travels = new ArrayCollection();
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
     * @return Collection<int, Travel>
     */
    public function getTravel(): Collection
    {
        return $this->travels;
    }

    public function addTravel(Travel $travel): self
    {
        if (!$this->travels->contains($travel)) {
            $this->travels->add($travel);
        }

        return $this;
    }

    public function removeTravel(Travel $travel): self
    {
        $this->travels->removeElement($travel);

        return $this;
    }

  

 
}
