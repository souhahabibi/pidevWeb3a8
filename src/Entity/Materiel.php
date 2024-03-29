<?php
namespace App\Entity;
use App\Repository\MaterielRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:MaterielRepository::class)]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150) ]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\Column]
    private ?int $quantite = null;
    
    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\Column(length: 150) ]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'Materiels') ]
    #[ORM\JoinColumn(name: 'FK_idSalle')]
    private ?Salle $fkIdsalle = null;

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

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