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

    #[ORM\ManyToMany(targetEntity: PriceList::class, mappedBy: 'Included_excursions')]
    private Collection $grille_tarifaires;

    #[ORM\OneToMany(mappedBy: 'excursion', targetEntity: TravelExcursion::class)]
    private Collection $travelExcursions;

    
    public function __construct()
    {
        $this->regions = new ArrayCollection();
       $this->grille_tarifaires = new ArrayCollection();
       $this->travelExcursions = new ArrayCollection();
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
     public function getGrilleTarifaires(): Collection
    {
        return $this->grille_tarifaires;
    }

    public function addGrilleTarifaire(PriceList $grille_tarifaire): self
    {
        if (!$this->grille_tarifaires->contains($grille_tarifaire)) {
            $this->grille_tarifaires->add($grille_tarifaire);
            $grille_tarifaire->addIncluded_excursion($this);
        }

        return $this;
    }

    public function removeGrilleTarifaire(PriceList $grille_tarifaire): self
    {
        if ($this->grille_tarifaires->removeElement($grille_tarifaire)) {
            $grille_tarifaire->removeIncluded_excursion($this);
        }

        return $this;
    }

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
    public function getTravelExcursions(): Collection
    {
        return $this->travelExcursions;
    }

    public function addTravelExcursion(TravelExcursion $travelExcursion): self
    {
        if (!$this->travelExcursions->contains($travelExcursion)) {
            $this->travelExcursions->add($travelExcursion);
            $travelExcursion->setExcursion($this);
        }

        return $this;
    }

    public function removeTravelExcursion(TravelExcursion $travelExcursion): self
    {
        if ($this->travelExcursions->removeElement($travelExcursion)) {
            // set the owning side to null (unless already changed)
            if ($travelExcursion->getExcursion() === $this) {
                $travelExcursion->setExcursion(null);
            }
        }

        return $this;
    }





}
