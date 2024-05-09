<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CoursRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass:CoursRepository::class)]
class Cours
{
 
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $image=null;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide.")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le nom doit être une chaîne alphabétique.")]
    private ?string $nom = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide.")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le description doit être une chaîne alphabétique.")]
    private ?string $description=null;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message:"Veuillez sélectionner un niveau.")]
    private ?string $niveau=null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $commentaire="";

   # #[ORM\Column(type: "string", length: 255)]
    #private ?string $planning = 'CURRENT_TIMESTAMP';
   
    #[ORM\Column(type: "datetime", columnDefinition: "TIMESTAMP DEFAULT CURRENT_TIMESTAMP")]
    private ?\DateTimeInterface $planning = null;


    #[ORM\ManyToOne(inversedBy: "cours")]
    private ?User $user=null;

    

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }
/*
    public function getPlanning(): ?string
    {
        return $this->planning;
    }*/

    public function __construct()
    {
        $this->planning = new \DateTime();
    }

   /* public function getPlanning(): ?string
{
    return $this->planning ? $this->planning->format('Y-m-d H:i:s') : null;
}*/

public function getPlanning(): ?\DateTimeInterface
{
    return $this->planning;
}
/*


    public function setPlanning(string $planning): static
    {
        $this->planning = $planning;

        return $this;
    }*/
    public function setPlanning(\DateTimeInterface $planning): static
{
    $this->planning = $planning;

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