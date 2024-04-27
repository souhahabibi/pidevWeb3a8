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
    #[ORM\Column(name:"id_commande")]
   private ?int $idCommande=null;

   #[ORM\Column(type: "date")]
   private ?\DateTimeInterface $date = null;

   #[ORM\Column]
   private ?int $etat = null;

 
    #[ORM\ManyToOne(inversedBy: 'commandes')] 
    private ?User $idUser=null;

    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: 'commandes')]
    #[ORM\JoinTable(name:"produit_commande")]
    #[JoinColumn(name:"commande_id", referencedColumnName:"id_commande")]
    #[InverseJoinColumn(name:"produit_id", referencedColumnName:"id_produit")]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
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
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
        }

        return $this;
   }

    public function removeProduit(Produit $produit): static
    {
       $this->produits->removeElement($produit);

        return $this;
    }

   

}
