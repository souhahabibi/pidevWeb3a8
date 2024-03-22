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
}
