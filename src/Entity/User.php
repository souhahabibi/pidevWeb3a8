<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)] 
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: false)]
    private string $nom;

    #[ORM\Column(name: "email", type: "string", length: 255, nullable: false)]
    private string $email;

    #[ORM\Column(name: "motDePasse", type: "string", length: 255, nullable: false)]
    private string $motdepasse;

    #[ORM\Column(name: "specialite", type: "string", length: 255, nullable: true)]
    private ?string $specialite;

    #[ORM\Column(name: "numero", type: "integer", nullable: false)]
    private int $numero;

    #[ORM\Column(name: "recommandation", type: "string", length: 3, nullable: false)]
    private string $recommandation;

    #[ORM\Column(name: "poids", type: "integer", nullable: false)]
    private int $poids;

    #[ORM\Column(name: "taille", type: "integer", nullable: false)]
    private int $taille;

    #[ORM\Column(name: "niveau", type: "string", length: 255, nullable: false)]
    private string $niveau;

    #[ORM\Column(name: "role", type: "string", length: 10, nullable: false)]
    private string $role;

    #[ORM\Column(name: "mailcode", type: "string", length: 255, nullable: true)]
    private ?string $mailcode;

    #[ORM\Column(name: "is_verifIed", type: "boolean", nullable: true)]
    private ?bool $isVerified;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMotdepasse(): ?string
    {
        return $this->motdepasse;
    }

    public function setMotdepasse(string $motdepasse): static
    {
        $this->motdepasse = $motdepasse;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): static
    {
        $this->specialite = $specialite;

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

    public function getRecommandation(): ?string
    {
        return $this->recommandation;
    }

    public function setRecommandation(string $recommandation): static
    {
        $this->recommandation = $recommandation;

        return $this;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(int $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function setTaille(int $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getMailcode(): ?string
    {
        return $this->mailcode;
    }

    public function setMailcode(?string $mailcode): static
    {
        $this->mailcode = $mailcode;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(?bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }


    

}