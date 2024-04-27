<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column(name:"id_produit")]
    private ?int $idProduit=null;
    
    #[ORM\Column(length: 150)] 
    #[Assert\NotBlank(message: 'Veuillez fournir un nom.')]
     #[Assert\Regex(
         pattern: '/^[a-zA-Z\s]*$/',
         message: 'Le nom  ne doit contenir que des lettres .'
     )]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez fournir une quantité.')]
    #[Assert\Type(type: 'integer', message: 'La quantité doit être un nombre entier.')]
    #[Assert\Range(min: 20, max: 100, minMessage: 'La quantité doit être au moins {{ 20 }}.', maxMessage: 'La quantité ne peut pas dépasser {{ limit }}.')]
    private ?int $quantite = null;
   
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez fournir un coût.')]
    #[Assert\Type(type: 'integer', message: 'Le coût doit être un nombre entier.')]
    private ?int $cout = null;

    #[ORM\Column(type: "date")]
    #[Assert\NotBlank(message: 'Veuillez fournir une date d\'expiration.')]
   
    private ?\DateTimeInterface $dateExpiration = null;
   
    #[ORM\Column(length: 150)] 
    #[Assert\NotBlank(message: 'Veuillez fournir une description.')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z\s]*$/',
        message: 'Le description ne doit contenir que des lettres .'
    )]
    private ?string $description = null;
   

    
    #[ORM\Column(length: 150)] 

    private ?string $image = null;


    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(name: 'id_fournisseur', referencedColumnName: 'id_fournisseur')]
    private ?Fournisseur $idFournisseur=null;

    #[ORM\ManyToMany(targetEntity: Commande::class, mappedBy: 'produits')]
private Collection $commandes;

    public function __construct()
    {
      $this->commandes = new ArrayCollection();
    }

// Ajoutez un nouvel attribut pour stocker le prix initial
private ?float $prixInitial = null;

// Ajoutez un nouvel attribut pour stocker le prix promotionnel
private ?float $prixPromo = null;
    
// Ajoutez les méthodes getter et setter pour prixPromo
public function getPrixPromo(): ?float
{
    return $this->prixPromo;
}

public function setPrixPromo(?float $prixPromo): self
{
    $this->prixPromo = $prixPromo;
    return $this;
}




    

   
    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getCout(): ?float
    {
        return $this->cout;
    }

    public function setCout(float $cout): static
    {
        $this->cout = $cout;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(\DateTimeInterface $dateExpiration): static
    {
        $this->dateExpiration = $dateExpiration;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getIdFournisseur(): ?Fournisseur
    {
        return $this->idFournisseur;
    }

    public function setIdFournisseur(?Fournisseur $idFournisseur): static
    {
        $this->idFournisseur = $idFournisseur;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->addProduit($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
       if ($this->commandes->removeElement($commande)) {
            $commande->removeProduit($this);
        }

        return $this;
    }

    public function getPrixInitial(): ?float
    {
        return $this->prixInitial;
    }

    public function setPrixInitial(float $prixInitial): self
    {
        $this->prixInitial = $prixInitial;
        return $this;
    }

    public function applyPromotionIfNeeded(): void
    {
        $expirationDate = $this->getDateExpiration();
        $today = new \DateTime();
        $threeDaysLater = (clone $today)->modify('+3 days');
    
        if ($expirationDate >= $today && $expirationDate <= $threeDaysLater) {
            // Appliquer la réduction de 30%
            $currentPrice = $this->getCout();
            $reductionPercentage = 0.3; // 30%
            $newPrice = $currentPrice * (1 - $reductionPercentage);
            // Arrondir le prix avec la promotion à l'entier le plus proche
            $newPrice = round($newPrice);
            $this->setPrixInitial($currentPrice); // Stocker le prix initial
            $this->setPrixPromo($newPrice); // Stocker le prix avec la promotion
        } else {
            // Si aucune promotion n'est appliquée
            $this->setPrixInitial($this->getCout()); // Stocker le cout original dans prixInitial
            $this->setPrixPromo(null); // Laisser le champ prixPromo vide
        }
    }
    


    



}
