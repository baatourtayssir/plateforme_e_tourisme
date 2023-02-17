<?php

namespace App\Entity;

require_once('User.php');
use App\Entity\Agence;
use App\Repository\AgentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgentRepository::class)]
class Agent extends User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NumTel = null;

    #[ORM\Column(length: 255)]
    private ?string $Adresse = null;

    #[ORM\ManyToOne(inversedBy: 'agents')]
    private ?Agence $NomAgence = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumTel(): ?string
    {
        return $this->NumTel;
    }

    public function setNumTel(string $NumTel): self
    {
        $this->NumTel = $NumTel;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getNomAgence(): ?Agence
    {
        return $this->NomAgence;
    }

    public function setNomAgence(?Agence $NomAgence): self
    {
        $this->NomAgence = $NomAgence;

        return $this;
    }
}
