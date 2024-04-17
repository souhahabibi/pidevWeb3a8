<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CouponRepository;
#[ORM\Entity(repositoryClass: CouponRepository::class)] 
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private ?int $id;

    #[ORM\Column(name: "nomSociete", type: "string", length: 255, nullable: false)]
    private string $nomsociete;

    #[ORM\Column(name: "code", type: "integer", nullable: false)]
    private int $code;

    #[ORM\Column(name: "valeur", type: "integer", nullable: false)]
    private int $valeur;

    #[ORM\Column(name: "dateExpiration", type: "string", length: 255, nullable: false)]
    private string $dateexpiration;

   
    

    #[ORM\ManyToOne(inversedBy: 'coupons')] 
    private ?User $user=null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomsociete(): ?string
    {
        return $this->nomsociete;
    }

    public function setNomsociete(string $nomsociete): static
    {
        $this->nomsociete = $nomsociete;

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(int $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getDateexpiration(): ?string
    {
        return $this->dateexpiration;
    }

    public function setDateexpiration(string $dateexpiration): static
    {
        $this->dateexpiration = $dateexpiration;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

}