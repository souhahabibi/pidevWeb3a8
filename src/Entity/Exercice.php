<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExerciceRepository;
#[ORM\Entity(repositoryClass: ExerciceRepository::class)]
class Exercice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $ide=null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $nom=null;

    #[ORM\Column]
    private ?string $etape=null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $image=null;


    
    #[ORM\ManyToOne(inversedBy: "exercices")]
    private ?Cours $id=null;
    
    #[ORM\ManyToOne(inversedBy: "exercices")]
    private ?User $user=null;


    public function getIde(): ?int
    {
        return $this->ide;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEtape(): ?string
    {
        return $this->etape;
    }

    public function setEtape(string $etape): self
    {
        $this->etape = $etape;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getId(): ?Cours
    {
        return $this->id;
    }

    public function setId(?Cours $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


}