<?php

namespace App\Entity;

use App\Repository\ReviewsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Client;
use DateTimeInterface;
use DateTimeImmutable;
use Doctrine\ORM\Event\LifecycleEventArgs;

#[ORM\Entity(repositoryClass: ReviewsRepository::class)]
class Reviews
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $comment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    private ?Article $article = null;

    #[ORM\OneToMany(mappedBy: 'reviews', targetEntity: Pictures::class , cascade: ['persist','remove'])]
    private Collection $images;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    private ?Offer $offer = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE , nullable: true)]
    private ?\DateTimeInterface $date = null;


  /*   #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeInterface $dateReservation = null; */

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    private ?Client $client = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

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

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function __construct()
    {
        $this->images = new ArrayCollection();
       /*  $this->date = new \DateTimeImmutable(); */
    }


    
 
    public function getDateReviews(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDateReview(DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    

    #[ORM\PrePersist]
    public function prePersist(LifecycleEventArgs $event): void
    {
        if ($this->date === null) {
            $this->date = new DateTimeImmutable();
        }
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
            $image->setReviews($this);
        }

        return $this;
    }

    public function removeImage(Pictures $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            if ($image->getReviews() === $this) {
                $image->setReviews(null);
            }
        }

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }


}
