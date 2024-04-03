<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AbonnementRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AbonnementRepository::class)] 
class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column]
    #[Assert\NotBlank(message: "Quantity is required")]
    #[Assert\Type(type: "integer", message: "Quantity must be an integer")]
    #[Assert\Range(min: 1, max: 9999, minMessage: "Quantity must be at least 1", maxMessage: "Quantity cannot exceed 9999")]
    private ?int $montant = null;
    
    #[ORM\Column]
    #[Assert\NotBlank(message: "Quantity is required")]
    #[Assert\Type(type: "integer", message: "Quantity must be an integer")]
    #[Assert\Range(min: 1, max: 9999, minMessage: "Quantity must be at least 1", maxMessage: "Quantity cannot exceed 9999")]
    private ?int $duree = null;

    #[ORM\Column(length: 150) ]
    #[Assert\NotBlank(message: "name is required") ] 
    #[Assert\Length(min: 1,max: 30,minMessage:"The name '{{ value }}' is too short", maxMessage: "The name '{{ value }}' is too long")]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'abonnement') ]
    #[ORM\JoinColumn(name: 'FK_idSalle')]
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

