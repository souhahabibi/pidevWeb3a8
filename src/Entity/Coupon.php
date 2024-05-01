<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CouponRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CouponRepository::class)] 
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private ?int $id;

    #[ORM\Column(name: "nomSociete", type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le nom de la sociéte ne peut pas être vide")]
    private ?string $nomsociete;

    #[ORM\Column(name: "code", type: "integer")]
    #[Assert\NotBlank(message: "Le code du coupon ne peut pas être vide")]
    #[Assert\Regex(
        pattern: '/^\d{8}$/',
        message: "Le code du coupon doit être un entier de 8 chiffres"
    )]
    private ?int $code;

    #[ORM\Column(name: "valeur", type: "integer")]
    #[Assert\NotBlank(message: "La valeur du coupon ne peut pas être vide")]
    #[Assert\Range(
        min: 0,
        max: 100,
        notInRangeMessage: "La valeur du coupon doit être comprise entre {{ min }} et {{ max }}"
    )]
    private ?int $valeur;

    #[ORM\Column(name: "dateExpiration", type: "string", length: 255)]
    #[Assert\NotBlank(message: "Date d'expiration ne peut pas être vide")]
//    #[Assert\Regex(
//        pattern: '/^\d{2}-\d{2}-\d{4}$/',
//        message: "Format de date invalide. Utilisez le format dd-mm-yyyy"
//    )]
    #[Assert\Date(message: "Date d'expiration invalide")]
//    #[Assert\GreaterThanOrEqual(
//        value: "today",
//        message: "La date d'expiration doit être aujourd'hui ou ultérieure"
//    )]
    private ?string $dateexpiration;

   
    

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