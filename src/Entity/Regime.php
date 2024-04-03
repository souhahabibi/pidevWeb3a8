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

    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"You cannot leave this field Empty")]
    #[Assert\GreaterThan("today" ,message:"Input Date must be in the future")]
    private ?\DateTimeInterface $startdate = null;

    
    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"You cannot leave this field Empty")]
    #[Assert\GreaterThan("today" ,message:"Input Date must be in the future")]
    private ?\DateTimeInterface $enddate = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"You cannot leave this field Empty")]
    private ?int $duration = null;

    
    #[ORM\Column] 
    #[Assert\NotBlank(message:"You cannot leave this field Empty")]
    #[Assert\Length(min:7,minMessage:" 7 characters minimum")]
    #[Assert\Length(max:40)]
    private ?string $description = null;

  
    #[ORM\Column]
    #[Assert\NotBlank(message:"You cannot leave this field Empty")]
    private ?string $goal = null;

  
    #[ORM\Column]
    private ?bool $verified = false;

   
    #[ORM\ManyToOne(inversedBy: 'regimes')]
    #[ORM\JoinColumn(name:"clientId", referencedColumnName:"id", nullable:true)]
    private ?User $clientId ;

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

    public function getVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(?bool $verified): static
    {
        $this->verified = $verified;

        return $this;
    }


    public function getClientId(): ?User
    {
        return $this->clientId;
    }

    public function setClientId(?User $clientId): static
    {
        $this->clientId = $clientId;

        return $this;
    }


}