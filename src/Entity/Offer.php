<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\InheritanceType;


#[Table(name: "offer")]
#[InheritanceType("JOINED")]
#[DiscriminatorColumn(name: "type", type: "string")]
#[DiscriminatorMap(['offer' => Offer::class, 'excursion' => Excursion::class, 'hiking' => Hiking::class ,
 'cruise' => Cruise::class,'omra' => Omra::class,'travel' => Travel::class])]
#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    public ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
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

    #[ORM\OneToMany(mappedBy: 'offer', targetEntity: Pictures::class , cascade: ['persist','remove'])]
    private Collection $images;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $inclus = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $nonInclus = null;

    #[ORM\OneToMany(mappedBy: 'offer', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'offer', targetEntity: PriceList::class)]
    private Collection $priceLists;

    #[ORM\ManyToMany(targetEntity: OfferExcursion::class, mappedBy: 'offer')]
    private Collection $offerExcursions;




    

    public function __construct()
    {
        $this->country = new ArrayCollection();
        $this->goodAddress = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->priceLists = new ArrayCollection();
        $this->offerExcursions = new ArrayCollection();
    
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

        // Getter et setter pour le champ `images`
        public function getImages(): Collection
        {
            return $this->images;
        }
    
        public function addImage(Pictures $image): self
        {
             /*  $this->images = new ArrayCollection(); */
            if (!$this->images->contains($image)) {
                $this->images[] = $image;
                $image->setOffer($this);
            }
    
            return $this;
        }
    
        public function removeImage(Pictures $image): self
        {
            if ($this->images->contains($image)) {
                $this->images->removeElement($image);
                if ($image->getOffer() === $this) {
                    $image->setOffer(null);
                }
            }
    
            return $this;
        }

        public function getInclus(): ?string
        {
            return $this->inclus;
        }

        public function setInclus(string $inclus): self
        {
            $this->inclus = $inclus;

            return $this;
        }

        public function getNonInclus(): ?string
        {
            return $this->nonInclus;
        }

        public function setNonInclus(string $nonInclus): self
        {
            $this->nonInclus = $nonInclus;

            return $this;
        }

        /**
         * @return Collection<int, Reservation>
         */
        public function getReservations(): Collection
        {
            return $this->reservations;
        }

        public function addReservation(Reservation $reservation): self
        {
            if (!$this->reservations->contains($reservation)) {
                $this->reservations->add($reservation);
                $reservation->setOffer($this);
            }

            return $this;
        }

        public function removeReservation(Reservation $reservation): self
        {
            if ($this->reservations->removeElement($reservation)) {
                // set the owning side to null (unless already changed)
                if ($reservation->getOffer() === $this) {
                    $reservation->setOffer(null);
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
                $priceList->setOffer($this);
            }

            return $this;
        }

        public function removePriceList(PriceList $priceList): self
        {
            if ($this->priceLists->removeElement($priceList)) {
                // set the owning side to null (unless already changed)
                if ($priceList->getOffer() === $this) {
                    $priceList->setOffer(null);
                }
            }

            return $this;
        }

        /**
         * @return Collection<int, OfferExcursion>
         */
        public function getOfferExcursions(): Collection
        {
            return $this->offerExcursions;
        }

        public function addOfferExcursion(OfferExcursion $offerExcursion): self
        {
            if (!$this->offerExcursions->contains($offerExcursion)) {
                $this->offerExcursions->add($offerExcursion);
                $offerExcursion->addOffer($this);
            }

            return $this;
        }

        public function removeOfferExcursion(OfferExcursion $offerExcursion): self
        {
            if ($this->offerExcursions->removeElement($offerExcursion)) {
                $offerExcursion->removeOffer($this);
            }

            return $this;
        }




}
