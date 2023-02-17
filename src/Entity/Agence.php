<?php

namespace App\Entity;
use App\Entity\Agent;
use App\Repository\AgenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
// use Vich\UploaderBundle\Mapping\Annotation as Vich;

// use Symfony\Component\HttpFoundation\File\File;

// #[Vich\Uploadable]
#[ORM\Entity(repositoryClass: AgenceRepository::class)]

class Agence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Numtel = null;

    #[ORM\Column(length: 255)]
    private ?string $Adresse = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    private ?string $Logo;

    #[ORM\OneToMany(mappedBy: 'NomAgence', targetEntity: Agent::class)]
    private Collection $agents;

    public function __construct()
    {
        $this->agents = new ArrayCollection();
    }


    
    // #[Vich\UploadableField(mapping: 'agence_images', fileNameProperty: 'Logo')]
    // private $LogoFile;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->Numtel;
    }

    public function setNumTel(string $Numtel): self
    {
        $this->Numtel = $Numtel;

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

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    // public function setLogoFile(File $Logo = null)
    // {
    //     $this->LogoFile = $Logo;

    //     // VERY IMPORTANT:
    //     // It is required that at least one field changes if you are using Doctrine,
    //     // otherwise the event listeners won't be called and the file is lost
    //     // if ($image) {
    //     //     // if 'updatedAt' is not defined in your entity, use another property
    //     //     $this->Date = new \DateTime('now');
    //     // }
    // }

    // public function getLogoFile()
    // {
    //     return $this->LogoFile;
    // }

    public function setLogo(string $Logo): self
    {
        $this->Logo = $Logo;
        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->Logo;
    }

    /**
     * @return Collection<int, Agent>
     */
    public function getAgents(): Collection
    {
        return $this->agents;
    }

    public function addAgent(Agent $agent): self
    {
        if (!$this->agents->contains($agent)) {
            $this->agents->add($agent);
            $agent->setNomAgence($this);
        }

        return $this;
    }

    public function removeAgent(Agent $agent): self
    {
        if ($this->agents->removeElement($agent)) {
            // set the owning side to null (unless already changed)
            if ($agent->getNomAgence() === $this) {
                $agent->setNomAgence(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return (string) $this->Nom;
    }


   
   
}
