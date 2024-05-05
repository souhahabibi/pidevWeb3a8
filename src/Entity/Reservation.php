<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Can't be Empty")]
    #[Assert\Positive(message: "Score must be a positive number.")]
    #[Assert\Range(min: 0,max: 100,notInRangeMessage: 'Score must be greater than 0 and less than 101.',)]
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
