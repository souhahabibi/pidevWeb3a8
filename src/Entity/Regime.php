<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RegimeRepository;

#[ORM\Entity(repositoryClass:RegimeRepository::class)]
class Regime
{
   #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column]
    private ?DateTime $startdate = null;

    
    
    #[ORM\Column]
    private ?DateTime $enddate = null;

    #[ORM\Column]
    private ?int $duration = null;

    
    #[ORM\Column] 
    private ?string $description = null;

  
    #[ORM\Column]
    private ?string $goal = null;

   
    #[ORM\ManyToOne(inversedBy: 'regimes')]
    private ?User $clientid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(\DateTimeInterface $startdate): static
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(\DateTimeInterface $enddate): static
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

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

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(string $goal): static
    {
        $this->goal = $goal;

        return $this;
    }

    public function getClientid(): ?User
    {
        return $this->clientid;
    }

    public function setClientid(?User $clientid): static
    {
        $this->clientid = $clientid;

        return $this;
    }


}