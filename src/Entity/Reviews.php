<?php

namespace App\Entity;

use App\Repository\ReviewsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewsRepository::class)]
class Reviews
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $comment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    private ?Article $article = null;

    #[ORM\OneToMany(mappedBy: 'reviews', targetEntity: Pictures::class , cascade: ['persist','remove'])]
    private Collection $images;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    private ?Offer $offer = null;
  
    
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

}
