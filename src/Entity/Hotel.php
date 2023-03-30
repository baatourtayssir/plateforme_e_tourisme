<?php

namespace App\Entity;

use App\Repository\HotelRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
class Hotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $intitule = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\ManyToOne(inversedBy: 'hotel')]
    private ?Region $region = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $characteristic = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: Pictures::class , cascade: ['persist','remove'])]
    private Collection $images;

    #[ORM\ManyToMany(targetEntity: PriceList::class, mappedBy: 'hotels')]
    private Collection $priceLists;



    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->priceLists = new ArrayCollection();
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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCharacteristic(): ?string
    {
        return $this->characteristic;
    }

    public function setCharacteristic(string $characteristic): self
    {
        $this->characteristic = $characteristic;

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

      // Getter et setter pour le champ `images`
      public function getImages(): Collection
      {
          return $this->images;
      }
  
      public function addImage(Pictures $image): self
      {
          if (!$this->images->contains($image)) {
              $this->images[] = $image;
              $image->setHotel($this);
          }
  
          return $this;
      }
  
      public function removeImage(Pictures $image): self
      {
          if ($this->images->contains($image)) {
              $this->images->removeElement($image);
              if ($image->getHotel() === $this) {
                  $image->setHotel(null);
              }
          }
  
          return $this;
      }

      /**
       * @return Collection<int, PriceList>
       */
      public function getPriceLists(): Collection
      {
          return $this->priceLists;
      }

      public function addPriceList(PriceList $priceList): self
      {
          if (!$this->priceLists->contains($priceList)) {
              $this->priceLists->add($priceList);
              $priceList->addHotel($this);
          }

          return $this;
      }

      public function removePriceList(PriceList $priceList): self
      {
          if ($this->priceLists->removeElement($priceList)) {
              $priceList->removeHotel($this);
          }

          return $this;
      }


}
