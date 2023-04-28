<?php

namespace App\Entity;

use App\Repository\GoodAddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;



#[ORM\Entity(repositoryClass: GoodAddressRepository::class)]
class GoodAddress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $intitule = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;


    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;


    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\ManyToMany(targetEntity: Offer::class, mappedBy: 'goodAddress')]
    private Collection $offers;

    #[ORM\ManyToOne(inversedBy: 'goodAddresses')]
    private ?Region $region = null;

    #[ORM\OneToMany(mappedBy: 'goodAddress', targetEntity: Pictures::class , cascade: ['persist','remove'])]
    private Collection $images;

    #[ORM\ManyToOne(inversedBy: 'goodAddresses')]
    private ?Category $category = null;


    public function __construct()
    {
        $this->offers = new ArrayCollection();
        $this->images = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

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
            $offer->addGoodAddress($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            $offer->removeGoodAddress($this);
        }

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }



        // Getter et setter pour le champ `images`
        public function getImages(): Collection
        {
            return $this->images;
        }
    
        public function addImage(Pictures $image): self
        {
            if (!$this->images->contains($image)) {
                $this->images[] = $image;
                $image->setGoodAddress($this);
            }
    
            return $this;
        }
    
        public function removeImage(Pictures $image): self
        {
            if ($this->images->contains($image)) {
                $this->images->removeElement($image);
                if ($image->getGoodAddress() === $this) {
                    $image->setGoodAddress(null);
                }
            }
    
            return $this;
        }

        public function getCategory(): ?Category
        {
            return $this->category;
        }

        public function setCategory(?Category $category): self
        {
            $this->category = $category;

            return $this;
        }






}
