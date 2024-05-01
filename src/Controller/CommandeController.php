<?php

namespace App\Controller;
use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'commande')]
    public function addCommande(SessionInterface $session,ProduitRepository $produitRepository,UserRepository $userRepository,EntityManagerInterface $entityManager): Response
    {
        $panier=$session->get('panier',[]);
        if($panier ===[])
        {
            $this->addFlash('message','Votre panier est vide');
            return $this->redirectToRoute('app_panier');
        }
        else
        {
            $user=$userRepository->find(1);
        $commande=new Commande();
            foreach($panier as $id=>$quantite)
            {
                $produit =$produitRepository->find($id); 
                $commande->addProduit($produit);


            }

$commande->setEtat(1);
$commande->setDate(new DateTime(\now));
$commande->setIdUser($user);
$entityManager->persist($commande);
$entityManager->flush();

        }
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }
}
