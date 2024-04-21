<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
#[ORM\Entity(repositoryClass: CommandeRepository::class)]
 
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
   private ?int $idCommande=null;

   #[ORM\Column(type: "date")]
   private ?\DateTimeInterface $date = null;

   #[ORM\Column]
   private ?int $etat = null;

 
    #[ORM\ManyToOne(inversedBy: 'commandes')] 
    private ?User $idUser=null;


    
    #[ORM\ManyToMany(targetEntity: Produit::class, mappedBy: 'commandes')]
#[ORM\JoinTable(name: "commande_produit")]
private Collection $produit;


    public function __construct()
    {
        $this->produit = new ArrayCollection();
    }

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
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

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produit->contains($produit)) {
            $this->produit->add($produit);
            $produit->addCommande($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produit->removeElement($produit)) {
            $produit->removeCommande($this);
        }

        return $this;
    }

}
