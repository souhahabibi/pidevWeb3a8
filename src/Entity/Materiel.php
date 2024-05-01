<?php
namespace App\Entity;
use App\Repository\MaterielRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass:MaterielRepository::class)]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150) ]
    #[Assert\NotBlank(message: "name is required") ] 
    #[Assert\Length(min: 1,max: 30,minMessage:"The name '{{ value }}' is too short", maxMessage: "The name '{{ value }}' is too long")]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Machine age is required")]
    #[Assert\Range( min: 1, max: 50, minMessage: "The machine age must be at least {{ limit }} years", maxMessage: "The machine age cannot exceed {{ limit }} years")]
    private ?int $age = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Quantity is required")]
    #[Assert\Type(type: "integer", message: "Quantity must be an integer")]
    #[Assert\Range(min: 1, max: 9999, minMessage: "Quantity must be at least 1", maxMessage: "Quantity cannot exceed 9999")]
    private ?int $quantite = null;
    
    #[ORM\Column]
    #[Assert\NotBlank(message: "Price is required")]
    #[Assert\Type(type: "integer", message: "Price must be an integer")]
    #[Assert\Range(min: 1, max: 999999, minMessage: "Price must be at least 1", maxMessage: "Price cannot exceed 9999999")]
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