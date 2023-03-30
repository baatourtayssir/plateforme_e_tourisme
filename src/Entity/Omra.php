<?php

namespace App\Entity;

use App\Repository\OmraRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OmraRepository::class)]
class Omra extends Offer
{
   /*  #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; */



  /*   public function getId(): ?int
    {
        return $this->id;
    }
 */
   
}
