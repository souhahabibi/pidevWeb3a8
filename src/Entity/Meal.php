<?php

namespace App\Entity;

use App\Repository\MealRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass:MealRepository::class)]
class Meal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

  

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"You need to fill all the fields")]
    #[Assert\Length(min:3,minMessage:" 3 characters minimum")]
    #[Assert\Length(max:15)]
    private ?string $name = null;

    #[ORM\Column(name:"image_url",length: 255,nullable:false)]
    private ?string  $imageUrl;

  
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"You need to fill all the fields")]
    #[Assert\Length(min:30,minMessage:" 100 characters minimum")]
    #[Assert\Length(max:200)]
    private ?string $recipe = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"You need to fill all the fields")]
    #[Assert\Positive(message:"Please enter a valid number")]
    private ?int $calories = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getRecipe(): ?string
    {
        return $this->recipe;
    }

    public function setRecipe(string $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getCalories(): ?int
    {
        return $this->calories;
    }

    public function setCalories(int $calories): static
    {
        $this->calories = $calories;

        return $this;
    }


}
