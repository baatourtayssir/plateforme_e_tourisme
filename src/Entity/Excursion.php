<?php

namespace App\Entity;

use App\Repository\ExcursionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExcursionRepository::class)]
class Excursion extends Offer
{
  /*   #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; */

    #[ORM\ManyToMany(targetEntity: Region::class, inversedBy: 'excursions')]
    #[ORM\JoinTable(name: "excursion_region")]
    private Collection $regions;

 /*    #[ORM\ManyToMany(targetEntity: PriceList::class, mappedBy: 'excursions')]
    private Collection $priceLists;
 */
    #[ORM\OneToMany(mappedBy: 'excursion', targetEntity: OfferExcursion::class)]
    private Collection $offerExcursions;
    

    public function __construct()
    {
        $this->regions = new ArrayCollection();
     /*   $this->priceLists = new ArrayCollection(); */
       $this->offerExcursions = new ArrayCollection();
       /*  $this->images = new ArrayCollection();  */
        
    
    }

   /*  public function getId(): ?int
    {
        return $this->id;
    } */

    /**
     * @return Collection<int, Region>
     */
    public function getRegions(): Collection
    {
        return $this->regions;
    }

    public function addRegion(Region $region): self
    {
        if (!$this->regions->contains($region)) {
            $this->regions->add($region);
        }

        return $this;
    }

    public function removeRegion(Region $region): self
    {
        $this->regions->removeElement($region);

        return $this;
    }

   /**
     * @return Collection<int, PriceList>
     */
  /*    public function getPriceLists(): Collection
    {
        return $this->priceLists;
    }

    public function addPriceList(PriceList $priceList): self
    {
        if (!$this->priceLists->contains($priceList)) {
            $this->priceLists->add($priceList);
            $priceList->addExcursion($this);
        }

        return $this;
    }

    public function removePriceList(PriceList $priceList): self
    {
        if ($this->priceLists->removeElement($priceList)) {
            $priceList->removeExcursion($this);
        }

        return $this;
    } */

/*     public function getPriceListsForExcursion()
{
    $priceLists =[];
    $priceLists = $this->getPriceLists();
    $result = [];

    foreach ($priceLists as $priceList) {
        $result[] = [
            'title' => $priceList->getTitle(),
            'prix' => $priceList->getPrix(),
            'dateDebut' =>$priceList->getDateDebut(),
            'dateFin' =>$priceList->getDateFin(),
            // ajouter d'autres propriétés de PriceList que vous voulez afficher
        ];
    }

    return $result;
} */


    public function __toString() {
        return $this->title;
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
            $offerExcursion->setExcursion($this);
        }

        return $this;
    }

    public function removeOfferExcursion(OfferExcursion $offerExcursion): self
    {
        if ($this->offerExcursions->removeElement($offerExcursion)) {
            // set the owning side to null (unless already changed)
            if ($offerExcursion->getExcursion() === $this) {
                $offerExcursion->setExcursion(null);
            }
        }

        return $this;
    }



}
