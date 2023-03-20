<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\InheritanceType;


#[Table(name: "offer")]
#[InheritanceType("JOINED")]
#[DiscriminatorColumn(name: "type", type: "string")]
#[DiscriminatorMap(['offer' => Offer::class, 'excursion' => Excursion::class])]
#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    public ?string $title = null;

    #[ORM\Column(length: 255)]
    public ?string $description = null;
 
    #[ORM\ManyToMany(targetEntity: Country::class, inversedBy: 'offers')]
    #[ORM\JoinTable(name: "offer_country")]
    public Collection $country;
    

    #[ORM\ManyToMany(targetEntity: GoodAddress::class, inversedBy: 'offers')]
    #[ORM\JoinTable(name: "offer_good_address")]
    public Collection $goodAddress;

    #[ORM\ManyToOne(inversedBy: 'offers')]
    public ?Agence $agence = null;

    #[ORM\Column(length: 255)]
    public ?string $picture = null;

    #[ORM\OneToMany(mappedBy: 'offer', targetEntity: Reviews::class)]
    public Collection $reviews;

    public function __construct()
    {
        $this->country = new ArrayCollection();
        $this->goodAddress = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    /**
     * @return Collection<int, Country>
     */
    public function getCountry(): Collection
    {
        return $this->country;
       /*  return $this->country ?? new ArrayCollection(); */
    }

    public function addCountry(Country $country): self
    {
      /*   if (!$this->country->contains($country)) {
            $this->country->add($country);
        } */

        if (!$this->country->contains($country)) {
            $this->country[] = $country;
        }

        return $this;
    }

    public function removeCountry(Country $country): self
    {
        $this->country->removeElement($country);

        return $this;
    }

    /**
     * @return Collection<int, GoodAddress>
     */
    public function getGoodAddress(): Collection
    {
        return $this->goodAddress;
    }

    public function addGoodAddress(GoodAddress $goodAddress): self
    {
        if (!$this->goodAddress->contains($goodAddress)) {
            $this->goodAddress->add($goodAddress);
        }

        return $this;
    }

    public function removeGoodAddress(GoodAddress $goodAddress): self
    {
        $this->goodAddress->removeElement($goodAddress);

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;

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
     * @return Collection<int, Reviews>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Reviews $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setOffer($this);
        }

        return $this;
    }

    public function removeReview(Reviews $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getOffer() === $this) {
                $review->setOffer(null);
            }
        }

        return $this;
    }
}
