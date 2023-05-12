<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Offer;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $style = null;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'categories')]
    private Collection $articles;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: GoodAddress::class)]
    private Collection $goodAddresses;




    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->goodAddresses = new ArrayCollection();
    

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(string $style): self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->addCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            $article->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, GoodAddress>
     */
    public function getGoodAddresses(): Collection
    {
        return $this->goodAddresses;
    }

    public function addGoodAddress(GoodAddress $goodAddress): self
    {
        if (!$this->goodAddresses->contains($goodAddress)) {
            $this->goodAddresses->add($goodAddress);
            $goodAddress->setCategory($this);
        }

        return $this;
    }

    public function removeGoodAddress(GoodAddress $goodAddress): self
    {
        if ($this->goodAddresses->removeElement($goodAddress)) {
            // set the owning side to null (unless already changed)
            if ($goodAddress->getCategory() === $this) {
                $goodAddress->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->style;
    }




}
