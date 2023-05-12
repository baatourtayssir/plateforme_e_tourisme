<?php



namespace App\Model;

/* use App\Entity\Country; */

class SearchData
{
    /** @var string|null */
    public $q;

    /** @var string|null */
    public $country;

    public function __toString() {
        return $this->q . ' ' . $this->country;
    }

    public function getQ(): ?string
    {
        return $this->q;
    }

    public function setQ(string $q): void
    {
        $this->q = $q;
    }

/*     public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): void
    {
        $this->country = $country;
    } */
}
