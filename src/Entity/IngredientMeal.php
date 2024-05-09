<?php

namespace App\Entity;

use App\Repository\IngredientMealRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass:IngredientMealRepository::class)]

class IngredientMeal
{
   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\ManyToOne(inversedBy:'ingredientMeals')]
    private ?Ingredients $ingredients = null;

    #[ORM\ManyToOne (inversedBy :'ingredientMeals')]
    private ?Meal $meal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIngredient(): ?Ingredients
    {
        return $this->ingredients;
    }

    public function setIngredient(?Ingredients $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getMeal(): ?Meal
    {
        return $this->meal;
    }

    public function setMeal(?Meal $meal): static
    {
        $this->meal = $meal;

        return $this;
    }

    public function getIngredients(): ?Ingredients
    {
        return $this->ingredients;
    }

    public function setIngredients(?Ingredients $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }


}