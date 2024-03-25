<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrganisateurRepository;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

#[ORM\Entity(repositoryClass: OrganisateurRepository::class)]
class Organisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Can't be Empty")]
    #[Assert\Length(min:3,minMessage:" 3 characters minimum")]
    #[Assert\Length(max:10)]
    private $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Can't be Empty")]
    #[Assert\Length(min:8,minMessage:"must be 8 digits long")]
    #[Assert\Length(max:8)]
    #[Assert\Type(type:'digit',message:"numbers only")]
    private $numero = null;
    
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

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }


}
