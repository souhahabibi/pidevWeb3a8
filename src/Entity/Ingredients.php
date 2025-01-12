<?php

namespace App\Entity;

use App\Repository\IngredientsRepository;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass:IngredientsRepository::class)]
class Ingredients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $name = null;
   

    
    #[ORM\Column]
    private ?int $calories = null;

    

    
    #[ORM\Column]
    private ?int $totalFat = null;

   
    
    #[ORM\Column]
    private ?int $protein = null;

    
    #[ORM\Column(length: 255)]
    private ?string $imgurl = null;

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

    public function getCalories(): ?int
    {
        return $this->calories;
    }

    public function setCalories(int $calories): static
    {
        $this->calories = $calories;

        return $this;
    }

    public function getTotalFat(): ?int
    {
        return $this->totalFat;
    }

    public function setTotalFat(int $totalFat): static
    {
        $this->totalFat = $totalFat;

        return $this;
    }

    public function getProtein(): ?int
    {
        return $this->protein;
    }

    public function setProtein(int $protein): static
    {
        $this->protein = $protein;

        return $this;
    }

    public function getImgurl(): ?string
    {
        return $this->imgurl;
    }

    public function setImgurl(string $imgurl): static
    {
        $this->imgurl = $imgurl;

        return $this;
    }


}