<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AbonnementRepository;

#[ORM\Entity(repositoryClass: AbonnementRepository::class)] 
class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column]
    private ?int $montant = null;
    
    #[ORM\Column]
    private ?int $duree = null;

    #[ORM\Column(length: 150) ]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'abonnement') ]
    private ?Salle $fkIdsalle = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getFkIdsalle(): ?Salle
    {
        return $this->fkIdsalle;
    }

    public function setFkIdsalle(?Salle $fkIdsalle): static
    {
        $this->fkIdsalle = $fkIdsalle;

        return $this;
    }


}

