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
use Knp\Snappy\Pdf;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
 use App\Entity\PdfGeneratorService;
 use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function ajouterProduit(Request $request , MailerInterface $mailer): Response
    {
        $produit = new Produit();

        // Créez le formulaire en utilisant ProduitType
        $form = $this->createForm(ProduitType::class, $produit);

        // Gérez la soumission (s'il y en a une)
        $form->handleRequest($request);

        // Vérifiez si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
        
            // Vérifie si un fichier a été téléchargé
            if ($imageFile) {
                // Vérifie si c'est une image
                if (!in_array($imageFile->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                    $this->addFlash('error', 'Le fichier doit être une image (JPEG, PNG, GIF)');
                    return $this->redirectToRoute('produit_ajouter');
                }
        
                try {
                    // Générez un nom de fichier unique
                    $newFilename = md5(uniqid()) . '.' . $imageFile->guessExtension();
                    // Déplacez le fichier téléchargé vers le répertoire approprié
                    $imageFile->move($this->getParameter('images_directory'), $newFilename);
                    // Redimensionnez l'image si nécessaire (ajoutez cette partie si nécessaire)
                    // Mettez à jour le chemin de l'image dans l'entité Produit
                    $produit->setImage($newFilename);
                } catch (FileException $e) {
                    // Gérer l'exception en cas d'erreur de téléchargement
                    $this->addFlash('error', 'Une erreur s\'est produite lors du téléchargement du fichier');
                    return $this->redirectToRoute('produit_ajouter');
                }
            }
        
            // Récupérez l'EntityManager
            $entityManager = $this->getDoctrine()->getManager();
        
            // Persistez et flush l'entité
            $entityManager->persist($produit);
            $entityManager->flush();
         // Récupérer les détails du produit
$nomProduit = $produit->getNom();
$descriptionProduit = $produit->getDescription();
$coutProduit = $produit->getCout();

// Construire le corps de l'e-mail avec les informations du produit
$emailBody = "<p>Un nouveau produit a été ajouté :</p>";
$emailBody .= "<p><strong>Nom du produit :</strong> $nomProduit</p>";
$emailBody .= "<p><strong>Description :</strong> $descriptionProduit</p>";
$emailBody .= "<p><strong>Coût :</strong> $coutProduit</p>";

// Créer l'e-mail avec le corps mis à jour
$email = (new Email())
    ->from('trabelsi.dali484@gmail.com')
    ->to('souhahabibi.ing@gmail.com')
    ->subject('Nouveau produit ajouté')
    ->html($emailBody);

// Envoyer l'e-mail
$mailer->send($email);

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

                // Redimensionner l'image si nécessaire
                // Note : Vous pouvez ajouter cette fonctionnalité ici

                // Mettre à jour le chemin de l'image dans l'entité Produit
                $produit->setImage($nouveauNomFichier);

                // Supprimer l'ancienne image si nécessaire
                if ($ancienCheminImage) {
                    unlink($this->getParameter('images_directory').'/'.$ancienCheminImage);
                }
            } catch (FileException $e) {
                // Gérer l'exception si le fichier ne peut pas être déplacé
                // ...
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
//recherche client
#[Route('/produit/searchClient', name: 'search_produitClient')]
public function searchClient(Request $request, ProduitRepository $produitRepository): Response
{
    $searchTerm = $request->query->get('search_term');
    $produits = $produitRepository->search($searchTerm);

    return $this->render('produit/listeClient.html.twig', [
        'produits' => $produits,
        
    ]);
}
//triii
#[Route('/produitClient/list', name: 'produitsClient_liste')]
public function listeProduitsTriesParCout(ProduitRepository $produitRepository)
{
    $produits = $produitRepository->trierParCoutDecroissant();
    return $this->render('produit/listeClient.html.twig', [
        'produits' => $produits,
    ]);
}



//client 
#[Route('/produitClient/list', name: 'produitsClient_liste')]
public function listeProduitsClient(ProduitRepository $produitRepository)
{
    
    $produits = $produitRepository->findAll();
    // Appliquer la promotion pour chaque produit
    foreach ($produits as $produit) {
        if ($produit instanceof Produit) {
            $produit->applyPromotionIfNeeded();
        }
    }

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
//pdf

#[Route('/pdf/produit', name: 'generator_service')]
    public function pdfproduit(): Response
    { 
        // Récupère tous les produits depuis la base de données
        $produit= $this->getDoctrine()
        ->getRepository(Produit::class)
        ->findAll();
        // Rend la vue Twig en passant les produits en tant que variable 'produit'
        $html =$this->renderView('pdf/index.html.twig', ['produit' => $produit]);
        // Initialise un nouveau service PdfGeneratorService
        $pdfGeneratorService=new PdfGeneratorService();
        // Génère le PDF à partir du HTML rendu
        $pdf = $pdfGeneratorService->generatePdf($html);
        // Renvoie le PDF en tant que réponse HTTP
        return new Response($pdf, 200, [
            //Cela indique au navigateur que le contenu de la réponse est un fichier PDF.
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"',
        ]);
       
    }
}
