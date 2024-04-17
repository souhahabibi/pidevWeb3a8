<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsermealRepository;

#[ORM\Entity(repositoryClass:UsersmealRepository::class)]
class Usersmeal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\ManyToOne(inversedBy: 'usersmeals')]
    private ?Meal $mealid = null;

   
    #[ORM\ManyToOne(inversedBy: 'usersmeals')]
    private ?User $userid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMealid(): ?Meal
    {
        return $this->mealid;
    }

    public function setMealid(?Meal $mealid): static
    {
        $this->mealid = $mealid;

        return $this;
    }

    public function getUserid(): ?User
    {
        return $this->userid;
    }

    public function setUserid(?User $userid): static
    {
        $this->userid = $userid;

        return $this;
    }


}