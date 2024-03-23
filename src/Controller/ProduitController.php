<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Imagine\Gd\Imagine;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Imagine\Image\Box;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    #[Route('/produit/list', name: 'produits_liste')]
    public function listeProduits(ProduitRepository $produitRepository)
    {
        $produits = $produitRepository->findAll();
    
        return $this->render('produit/liste.html.twig', [
            'produits' => $produits,
        ]);
    }
    

    #[Route('/produit/ajouter', name: 'produit_ajouter', methods: ['GET', 'POST'])]
    public function ajouterProduit(Request $request): Response
    {
        $produit = new Produit();

        // Créez le formulaire en utilisant ProduitType
        $form = $this->createForm(ProduitType::class, $produit);

        // Gérez la soumission (s'il y en a une)
        $form->handleRequest($request);

        // Vérifiez si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
             // Gérer le téléchargement de l'image
             $imageFile = $form->get('image')->getData();
             if ($imageFile) {
                 $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                 // Cela garantit que le nom du fichier est unique
                 $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
     
                 // Déplacer le fichier dans le répertoire ousont stockées les images
                 try {
                     $imageFile->move(
                         $this->getParameter('images_directory'),
                         $newFilename
                     );
                    // Redimensionner l'image
                 $imagine = new Imagine();
                 $image = $imagine->open($this->getParameter('images_directory').'/'.$newFilename);
                 $image->resize(new Box(50, 50))->save($this->getParameter('images_directory').'/'.$newFilename);
                 } catch (FileException $e) {
                     // Gérer l'exception 
                 }
     
                 // Mettre à jour le chemin de l'image dans l'entité Produit
                 $produit->setImage($newFilename);
                }
            // Récupérez l'EntityManager
            $entityManager = $this->getDoctrine()->getManager();

            // Persistez et flush l'entité
            $entityManager->persist($produit);
            $entityManager->flush();

            // Redirigez vers une autre page ou affichez un message de réussite, par exemple
            return $this->redirectToRoute('produits_liste');
        }

        // Si le formulaire n'est pas soumis ou n'est pas valide, affichez simplement le formulaire
        return $this->render('produit/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    // Méthode pour modifier un produit
    #[Route('/produit/modifier/{id}', name: 'modifier_produit')]
    public function modifierProduit(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(Produit::class)->find($id);
    
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
    
        // Récupérer le chemin de l'image actuel
        $ancienCheminImage = $produit->getImage();
    
        // Créer le formulaire
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si un nouveau fichier image a été soumis
            $nouvelleImage = $form->get('image')->getData();
            if ($nouvelleImage) {
                // Télécharger le nouveau fichier image
                $nouveauNomFichier = uniqid().'.'.$nouvelleImage->guessExtension();
                try {
                    $nouvelleImage->move(
                        $this->getParameter('images_directory'),
                        $nouveauNomFichier
                    );
    
                    // Redimensionner l'image
                    $imagine = new Imagine();
                    $image = $imagine->open($this->getParameter('images_directory').'/'.$nouveauNomFichier);
                    $image->resize(new Box(50, 50))->save($this->getParameter('images_directory').'/'.$nouveauNomFichier);
                } catch (FileException $e) {
                    // Gérer l'exception si le fichier ne peut pas être déplacé ou redimensionné
                    // ...
                }
    
                // Mettre à jour le chemin de l'image dans l'entité Produit
                $produit->setImage($nouveauNomFichier);
    
                // Supprimer l'ancienne image si nécessaire
                if ($ancienCheminImage) {
                    unlink($this->getParameter('images_directory').'/'.$ancienCheminImage);
                }
            }
    
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();
    
            // Rediriger vers la liste des produits
            return $this->redirectToRoute('produits_liste');
        }
    
        return $this->render('produit/modifier.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit,
        ]);
    
    
}

// Méthode pour supprimer un produit
#[Route('/produit/{id}', name: 'produit_supprimer')]
public function supprimerProduit(?int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $produit = $entityManager->getRepository(Produit::class)->find($id);

    if (!$produit) {
        throw $this->createNotFoundException('Produit non trouvé');
    }

    $entityManager->remove($produit);
    $entityManager->flush();

     // Rediriger vers une page de confirmation ou ailleurs
     return $this->redirectToRoute('produits_liste');

}
//recherche
#[Route('/produit/search', name: 'search_produit')]
public function search(Request $request, ProduitRepository $produitRepository): Response
{
    $searchTerm = $request->query->get('search_term');
    $produits = $produitRepository->search($searchTerm);

    return $this->render('produit/liste.html.twig', [
        'produits' => $produits,
    ]);
}

//client 
#[Route('/produitClient/list', name: 'produitsClient_liste')]
public function listeProduitsClient(ProduitRepository $produitRepository)
{
    $produits = $produitRepository->findAll();

    return $this->render('produit/listeClient.html.twig', [
        'produits' => $produits,
    ]);
}
 //stat

 #[Route('/produit/piechart', name: 'prod_piechart')]
public function pieChart(ProduitRepository $produitRepository): Response
{
    // Récupérer les fournisseurs avec la quantité totale de produits pour chaque fournisseur
    $data = [];
    $produitsParFournisseur = $produitRepository->getQuantiteProduitsParFournisseur();

    foreach ($produitsParFournisseur as $result) {
        $fournisseurNom = $result['nom'];
        $quantiteProduits = $result['quantite'];
        $data[] = ['fournisseur' => $fournisseurNom, 'quantite' => $quantiteProduits];
    }

    return $this->render('produit/piechart.html.twig', [
        'data' => $data
    ]);
}

}
