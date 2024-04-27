<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session,ProduitRepository $produitRepository): Response
    {
        $panier=$session->get('panier',[]);
        // Vérifier si le produit avec l'ID 2 est présent dans le panier
if (isset($panier[2])) {
    // Supprimer le produit avec l'ID 2 du panier
    unset($panier[2]);
}

// Mettre à jour la session avec le nouveau panier
$session->set('panier', $panier);
        $data=[]; //bch nhotou donnees mtaa l produit +quantite
        $total=0; //soum final 
        foreach($panier as $id=>$quantite)// for
        {
            $produit=$produitRepository->find($id);
            
            //naaby tab teena
            $data[]=[
                'produit'=>$produit,  //houny ijib f les donnes taa produits kol
                'quantite'=>$quantite
            ];
            $total+=($produit->getCout())*$quantite;


        }
        return $this->render('panier/index.html.twig', [
            'data'=>$data,
            'total'=>$total
        ]);
    }
    #[Route('/addToPanier/{id}', name: 'add_panier')]
    public function add(int $id,EntityManagerInterface $entityManager,SessionInterface $session):Response //sesssion pour stocker panier 
    {
        $produit=$entityManager->getRepository(Produit::class)->find($id);
        $id=$produit->getIdProduit();
        // houny kn aandou panier bch ijibha w [] yaany kn m aandouch bch ijib panier vide
        $panier=$session->get('panier',[]);
         //bch naaml l add lel produit , kn mch mawjoud bch ihoutou f panier
         //kn deja mawjoud bch incremente nbre mtaa prod
         if(empty($panier[$id]))
         {
            //nzydou w quantite 1 khatr panier tab feha 2 collones id,quantite
            $panier[$id]=1; 
         }
         else 
         {
            $panier[$id]++;
         }
         //nch naawd nestoki panier 
         $session->set('panier',$panier);
         

        // Rediriger l'utilisateur vers la liste des produits
    return $this->redirectToRoute('produitsClient_liste');
    }
 //suppression
 #[Route('/panier/supprimer/{id}', name: 'supprimer_produit_panier')]
 public function supprimerProduitDuPanier(SessionInterface $session , int $id): Response
 {
     // Récupérer le panier de la session
     $panier = $session->get('panier', []);

     // Vérifier si le produit existe dans le panier
     if (isset($panier[$id])) {
         // Supprimer le produit du panier
         unset($panier[$id]);

         // Mettre à jour la session avec le nouveau panier
         $session->set('panier', $panier);

         // Rediriger l'utilisateur vers la page précédente ou une autre page selon vos besoins
         return $this->redirectToRoute('app_panier');
     } else {
         // Produit non trouvé dans le panier, peut-être afficher un message d'erreur ou rediriger vers une autre page
         // Dans cet exemple, nous redirigeons simplement l'utilisateur vers la page de consultation du panier
         return $this->redirectToRoute('app_panier');
     }
 }
 //modiiif
 #[Route('/panier/minimiser-quantite/{id}', name: 'minimiser_quantite_panier')]
 public function modifierQuantitePanier(SessionInterface $session , int $id): Response
 {
     // Récupérer le panier de la session
     $panier = $session->get('panier', []);
 
     // Vérifier si le produit existe dans le panier
     if (isset($panier[$id])) {

         // Mettre à jour la quantité du produit dans le panier
         
         if($panier[$id]>1)
         {
            $panier[$id]--;
         }
         else 
         {unset($panier[$id]);}
 
         // Mettre à jour la session avec le nouveau panier
         $session->set('panier', $panier);
     }
 
     // Rediriger l'utilisateur vers la page de consultation du panier
     return $this->redirectToRoute('app_panier');
 }
 //augmenter 
 #[Route('/panier/augmenter-quantite/{id}', name: 'augmenter_quantite_panier')]
 public function augmenterQuantitePanier(SessionInterface $session , int $id): Response
 {
     // Récupérer le panier de la session
     $panier = $session->get('panier', []);
 
     // Vérifier si le produit existe dans le panier
     if (isset($panier[$id])) {

         // Mettre à jour la quantité du produit dans le panier
         
         $panier[$id]++;
 
         // Mettre à jour la session avec le nouveau panier
         $session->set('panier', $panier);
     }
 
     // Rediriger l'utilisateur vers la page de consultation du panier
     return $this->redirectToRoute('app_panier');
 }
 //passer commande
 #[Route('/passer-commande', name: 'passer_commande')]
 public function confirmationCommande(): Response
 {
     // Afficher une page de confirmation de commande
     return $this->render('panier/passer_commande.html.twig');
 }
}