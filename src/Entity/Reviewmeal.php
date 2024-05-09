<?php

namespace App\Entity;
use Doctrine\DBAL\Types\Types;
use App\Repository\ReviewmealRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass:ReviewmealRepository::class)]
 
class Reviewmeal
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    
    #[ORM\Column]
    private ?float $rate;

    
     #[ORM\Column]
    private ?string $comment;

    
     #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdDate;

    
      #[ORM\ManyToOne(targetEntity:User::class, inversedBy:"Reviewmeals")]
    private $user;

   
     #[ORM\ManyToOne(targetEntity:Meal::class, inversedBy:"Reviews")]
    private $idmeal;

    
      #[ORM\Column]
    private ?string $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

   

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function __construct()
    {
        $this->createdDate = new \DateTime();
    }

    public function getIdmeal(): ?Meal
    {
        return $this->idmeal;
    }

    public function setIdmeal(?Meal $idmeal): self
    {
        $this->idmeal = $idmeal;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}