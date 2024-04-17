<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FournisseurRepository;

 #[ORM\Entity(repositoryClass: FournisseurRepository::class)]
 
class Fournisseur
{
    

     #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column]
    private ?int $id=null;

    
     #[ORM\Column(length: 150)] 
    private ?string $nom = null;

    #[ORM\Column(length: 150)] 
    private ?string $prenom = null;

    
    #[ORM\Column]
    private ?int $numero = null;

    #[ORM\Column(length: 150)] 
    private ?string $type = null;

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


}
