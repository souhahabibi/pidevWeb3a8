<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompetitionRepository;
use Doctrine\DBAL\Types\Types;
#[ORM\Entity(repositoryClass: CompetitionRepository::class)]
class Competition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $capacite = null;

    #[ORM\Column(length: 255)]
    private ?string $videourl = null;

    #[ORM\ManyToOne(inversedBy: "competitions")]
    private ?Organisateur $fkOrganisateur = null;


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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getVideourl(): ?string
    {
        return $this->videourl;
    }

    public function setVideourl(string $videourl): static
    {
        $this->videourl = $videourl;

        return $this;
    }

    public function getFkOrganisateur(): ?Organisateur
    {
        return $this->fkOrganisateur;
    }

    public function setFkOrganisateur(?Organisateur $fkOrganisateur): static
    {
        $this->fkOrganisateur = $fkOrganisateur;

        return $this;
    }


}
