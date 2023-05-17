<?php

namespace App\Entity;

use App\Repository\HikingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HikingRepository::class)]
class Hiking extends Offer
{
  /*   #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; */

    #[ORM\ManyToOne(inversedBy: 'hikings')]
    private ?Region $region = null;

 /*    public function getId(): ?int
    {
        return $this->id;
    } */

    public function __construct()
    {
        parent::__construct();
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

    public function __toString(){
        return $this->title; 
    }
}
