<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $score = null;

    #[ORM\ManyToOne(inversedBy: "reservations")]
    private ?Competition $fkCompetition = null;

    #[ORM\ManyToOne(inversedBy: "reservations")]
    private ?User $fkClient = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getFkCompetition(): ?Competition
    {
        return $this->fkCompetition;
    }

    public function setFkCompetition(?Competition $fkCompetition): static
    {
        $this->fkCompetition = $fkCompetition;

        return $this;
    }

    public function getFkClient(): ?User
    {
        return $this->fkClient;
    }

    public function setFkClient(?User $fkClient): static
    {
        $this->fkClient = $fkClient;

        return $this;
    }


}
