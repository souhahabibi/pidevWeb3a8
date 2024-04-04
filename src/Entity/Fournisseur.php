<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FournisseurRepository;
use Symfony\Component\Validator\Constraints as Assert;

 #[ORM\Entity(repositoryClass: FournisseurRepository::class)]
 
class Fournisseur
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_fournisseur")]
    private ?int $idFournisseur=null;
    

    
     #[ORM\Column(length: 150)] 
     #[Assert\NotBlank(message: 'Veuillez fournir un nom.')]
     #[Assert\Regex(
         pattern: '/^[a-zA-Z\s]*$/',
         message: 'Le nom  ne doit contenir que des lettres .'
     )]
    private ?string $nom = null;

    #[ORM\Column(length: 150)] 
    #[Assert\NotBlank(message: 'Veuillez fournir un prenom.')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z\s]*$/',
        message: 'Le prenom ne doit contenir que des lettres .'
    )]
    private ?string $prenom = null;

    
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez fournir un numéro.')]
    #[Assert\Length(
    min: 8,
    max: 8,
    exactMessage: 'Le numéro doit contenir exactement 8 chiffres.'
    )]
    private ?int $numero = null;

    #[ORM\Column(length: 150)] 
    private ?string $type = null;

    public function getIdFournisseur(): ?int
    {
        return $this->idFournisseur;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }
    public function __toString(): string
    {
        return $this->nom ?? '';
    }
    

}
