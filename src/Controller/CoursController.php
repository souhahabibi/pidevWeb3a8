<?php

namespace App\Controller;
use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Imagine\Gd\Imagine;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Imagine\Image\Box;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class CoursController extends AbstractController
{
    #[Route('/cours', name: 'app_cours')]
    public function index(): Response
    {
        return $this->render('cours/index.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }

    #[Route('/client', name: 'client_app')]
    public function clientCours(CoursRepository $coursRepository)
    {
        $cours = $coursRepository->findAll();
    
        return $this->render('client_base.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/client/cours', name: 'client_cours')]
    public function clientCours2(CoursRepository $coursRepository)
    {
        $cours = $coursRepository->findAll();
    
        return $this->render('client_cours.html.twig', [
            'cours' => $cours,
        ]);
    }


    #[Route('/Client_Acceuil', name: 'app_Acceuil')]
    public function Acceuil(): Response
    {
        return $this->render('client_index.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }



    #[Route('/coach' ,name: 'cours_coach')]
    public function coach(): Response
    {
        return $this->render('coach_base.html.twig', [
            'controller_name' => 'CoursController']);
    }





    #[Route('/cours/list', name: 'cours_liste')]
    public function listeCours(CoursRepository $coursRepository)
    {
        $cours = $coursRepository->findAll();
    
        return $this->render('cours/liste.html.twig', [
            'cours' => $cours,
        ]);
    }


    #[Route('/ajoutercours', name: 'ajouter_cours')]
    public function ajoutercours(Request $request): Response
    {
        $cours = new Cours();
    
        // Create the form using CoursType
        $form = $this->createForm(CoursType::class, $cours);
    
        // Handle the submission (if any)
        $form->handleRequest($request);
    
        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the image upload
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // This ensures that the filename is unique
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
    
                // Update the image path in the Cours entity
                $cours->setImage($newFilename);
            }
    
            // Get the EntityManager
            $entityManager = $this->getDoctrine()->getManager();
    
            // Persist and flush the entity
            $entityManager->persist($cours);
            $entityManager->flush();
    
            // Redirect to another page or display a success message, for example
            return $this->redirectToRoute('cours_liste');
        }
    
        // If the form is not submitted or not valid, just display the form
        return $this->render('cours/AjouterCours.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    





    #[Route('/cours/{id}/modifier', name: 'modifier_cours')]
    public function edit(Request $request, $id, CoursRepository $coursRepository): Response
    {
        $cours = $coursRepository->find($id);
    
        if (!$cours) {
            throw $this->createNotFoundException('Le cours demandé n\'existe pas');
        }
    
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the image upload
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // This ensures that the filename is unique
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
    
                // Update the image path in the Cours entity
                $cours->setImage($newFilename);
            }
    
            $this->getDoctrine()->getManager()->flush();
    
            return $this->redirectToRoute('cours_liste');
        }
    
        return $this->render('cours/modifierCours.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/cours/{id}', name: 'cours_supprimer')]
public function supprimerCours(?int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $cours = $entityManager->getRepository(Cours::class)->find($id);

    if (!$cours) {
        throw $this->createNotFoundException('cours non trouvé');
    }

    $entityManager->remove($cours);
    $entityManager->flush();

     // Rediriger vers une page de confirmation ou ailleurs
     return $this->redirectToRoute('cours_liste');

}





}
