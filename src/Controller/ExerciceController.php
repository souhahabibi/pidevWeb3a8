<?php

namespace App\Controller;
use App\Entity\Exercice;
use App\Form\ExerciceType;
use App\Repository\ExerciceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Imagine\Gd\Imagine;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Imagine\Image\Box;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ExerciceController extends AbstractController
{
    #[Route('/exercice', name: 'app_exercice')]
    public function index(): Response
    {
        return $this->render('exercice/index.html.twig', [
            'controller_name' => 'ExerciceController',
        ]);
    }

#[Route('/client/exercice/{courseId}', name: 'client_exercices')]
public function clientCours2(ExerciceRepository $exerciceRepository, int $courseId)
{
    $exercices = $exerciceRepository->findBy(['cours' => $courseId]);

    return $this->render('client_exercice.html.twig', [
        'exercices' => $exercices,
    ]);
}



    #[Route('/exercice/list', name: 'exercice_liste')]
    public function listeExercice(ExerciceRepository $exerciceRepository)
    {
        $exercice = $exerciceRepository->findAll();
    
        return $this->render('exercice/liste.html.twig', [
            'exercice' => $exercice,
        ]);
    }
     
    
    #[Route('/ajouterexercice', name: 'ajouter_exercice')]
    public function ajouterexercice(Request $request): Response
    {
        $exercice = new Exercice();
    
        // Create the form using ExerciceType
        $form = $this->createForm(ExerciceType::class, $exercice);
    
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
                        $this->getParameter('images_directory_p'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
    
                // Update the image path in the exercice entity
                $exercice->setImage($newFilename);
            }
    
            // Get the EntityManager
            $entityManager = $this->getDoctrine()->getManager();
    
            // Persist and flush the entity
            $entityManager->persist($exercice);
            $entityManager->flush();
    
            // Redirect to another page or display a success message, for example
            return $this->redirectToRoute('exercice_liste');
        }
    
        // If the form is not submitted or not valid, just display the form
        return $this->render('exercice/Ajouterexercice.html.twig', [
            'form' => $form->createView(),
        ]);
    } 





    #[Route('/exercice/{id}/modifier', name: 'modifier_exercice')]
    public function edit(Request $request, $id, ExerciceRepository $exerciceRepository): Response
    {
      
        $exercice = $exerciceRepository->find($id);
    
        if (!$exercice) {
            throw $this->createNotFoundException('L exercice demandé n\'existe pas');
        }
    
        $form = $this->createForm(ExerciceType::class, $exercice);
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
                        $this->getParameter('images_directory_p'),
                        $newFilename 
                    
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
        
                // Update the image path in the Cours entity
                $exercice->setImage($newFilename);
            }
        
            $this->getDoctrine()->getManager()->flush();
        
            return $this->redirectToRoute('exercice_liste');
        }
        

    
        return $this->render('exercice/modifierExercice.html.twig', [
            'exercice' => $exercice,
            'form' => $form->createView(),
        ]);




        
    }




    #[Route('/exercice/{id}', name: 'exercice_supprimer')]
    public function supprimerExercice(?int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $exercice = $entityManager->getRepository(Exercice::class)->find($id);
    
        if (!$exercice) {
            throw $this->createNotFoundException('exercice non trouvé');
        }
    
        $entityManager->remove($exercice);
        $entityManager->flush();
    
         // Rediriger vers une page de confirmation ou ailleurs
         return $this->redirectToRoute('exercice_liste');
    
    }






}
