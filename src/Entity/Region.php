<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use App\Entity\Country;
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
}
