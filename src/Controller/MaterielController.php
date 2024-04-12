<?php 

namespace App\Controller;

use App\Entity\Materiel;
use App\Form\MaterielType;
use App\Repository\SalleRepository;
use App\Repository\MaterielRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MaterielController extends AbstractController
{
    #[Route('/materiel', name: 'app_materiel')]
    public function index(MaterielRepository $repo): Response
    {
        $list = $repo->findAll(); 
        return $this->render('materiel/materiel.html.twig', [
            'list' => $list
        ]);
    }
    #[Route('/materiel/add{id}', name: 'app_materiel_add')]
    public function add(Request $req,ManagerRegistry $manager,$id,SalleRepository $repos): Response
    {
       $materiel = new Materiel(); 
       $salle = $repos->find($id); 
       $form = $this->createForm(MaterielType::class,$materiel);

       $em = $manager->getManager();

       $form->handleRequest($req); 

       if ($form->isSubmitted() && $form->isValid())
       {
           $file = $form->get('image')->getData();
           if ($file)
           {
               $fileName = md5(uniqid()).'.'.$file->guessExtension();

               // Move the file to the desired directory
               $file->move(
                   $this->getParameter('images_directory'), // Specify your target directory
                   $fileName
               );

               // Update the image property of salle entity 
               $materiel->setFkIdsalle($salle);     
               $materiel->setImage($fileName); 
               $em->persist($materiel); 
               $em->flush();
               return $this->redirectToRoute('app_salle_materiel', ['id' => $id]);
           }
       }
        return $this->renderForm('materiel/materielForm.html.twig',
         ['f' => $form]     
        );
    }
    #[Route('/materiel/modify{id}', name: 'app_materiel_modify')]
    public function modify(Request $req,ManagerRegistry $manager,MaterielRepository $repo,$id): Response
    {
        $materiel = new Materiel();
        $materiel = $repo->find($id);
        $salle = $repo->find($id);
        $form = $this->createForm(MaterielType::class,$materiel);
 
        $em = $manager->getManager();
 
        $form->handleRequest($req); 
 
        if ($form->isSubmitted() && $form->isValid())
        {
            $file = $form->get('image')->getData();
            if ($file) 
            {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                // Move the file to the desired directory
                $file->move(
                    $this->getParameter('images_directory'), // Specify your target directory
                    $fileName
                );

                // Update the image property of salle entity
                $materiel->setImage($fileName);
                $em->persist($materiel);
                $em->flush();
                return $this->redirectToRoute('app_salle_materiel',['id' => $materiel->getFkIdsalle()->getId()]);
            }
        }
 
        return $this->renderForm('materiel/materielForm.html.twig',
        ['f' => $form]     
      );
    }

    #[Route('/materiel/delete{id}', name: 'app_materiel_delete')]
    public function delete(ManagerRegistry $manager,MaterielRepository $repo,$id): Response
    {
        $materiel = $repo->find($id);
        $em = $manager->getManager();
 
        $em->remove($materiel);
        $em->flush();
        return $this->redirectToRoute('app_salle_materiel', ['id' => $materiel->getFkIdsalle()->getId()]); 
    }

} 