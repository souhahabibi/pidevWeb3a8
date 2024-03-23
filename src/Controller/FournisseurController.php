<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use App\Repository\FournisseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FournisseurController extends AbstractController
{
    #[Route('/fournisseur', name: 'app_fournisseur')]
    public function index(): Response
    {
        return $this->render('fournisseur/index.html.twig', [
            'controller_name' => 'FournisseurController',
        ]);
    }
    #[Route('/fournisseur/add', name: 'app_fournisseur_add')]
    public function add(Request $request): Response
    {
        $fournisseur = new Fournisseur();
        $form = $this->createForm(FournisseurType::class, $fournisseur);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le numéro depuis l'entité Fournisseur
            $numero = $fournisseur->getNumero();
            
            // Valider que le numéro contient exactement 8 chiffres
            if (strlen($numero) !== 8 || !ctype_digit($numero)) {
                $this->addFlash('error', 'Le numéro doit contenir exactement 8 chiffres.');
                return $this->render('fournisseur/add.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($fournisseur);
            $entityManager->flush();
    
            // Rediriger vers la page Display.html.twig après l'ajout réussi
            return $this->redirectToRoute('fournisseur_liste');
        }
    
        return $this->render('fournisseur/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/fournisseur/list', name: 'fournisseur_liste')]
    public function listeFournisseur(FournisseurRepository $fournisseurRepository)
    {
        $fournisseurs = $fournisseurRepository->findAll();

        return $this->render('fournisseur/Display.html.twig', [
            'fournisseurs' => $fournisseurs,
        ]);
    }
    #[Route('/fournisseur/{id}/edit', name: 'edit_fournisseur')]
    public function edit(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $fournisseur = $entityManager->getRepository(Fournisseur::class)->find($id);
    
        if (!$fournisseur) {
            throw $this->createNotFoundException('Fournisseur non trouvé');
        }
    
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            // Rediriger vers une page de confirmation ou ailleurs
            return $this->redirectToRoute('fournisseur_liste');
        }
    
        return $this->render('fournisseur/edit.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form->createView(),
        ]);
    }

#[Route('/fournisseur/{id}/delete', name: 'delete_fournisseur')]
public function delete(Request $request, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $fournisseur = $entityManager->getRepository(Fournisseur::class)->find($id);

    if (!$fournisseur) {
        throw $this->createNotFoundException('Fournisseur non trouvé');
    }

    $entityManager->remove($fournisseur);
    $entityManager->flush();

    // Rediriger vers une page de confirmation ou ailleurs
    return $this->redirectToRoute('fournisseur_liste');
}

//recherche
#[Route('/fournisseur/search', name: 'search_fournisseur')]
public function search(Request $request, FournisseurRepository $fournisseurRepository): Response
{
    $searchTerm = $request->query->get('search_term');
    $fournisseurs = $fournisseurRepository->search($searchTerm);

    return $this->render('fournisseur/Display.html.twig', [
        'fournisseurs' => $fournisseurs,
    ]);
}


}
