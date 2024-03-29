<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Form\SalleType;
use App\Entity\Materiel;
use App\Entity\Abonnement;
use App\Repository\SalleRepository;
use App\Repository\MaterielRepository;
use App\Repository\AbonnementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SalleController extends AbstractController
{    
    #[Route('/salleAdmin', name: 'app_salleAdmin')]
    public function index(SalleRepository $repo): Response
    {
        $list = $repo->findAll(); 
        return $this->render('salle/salleAdmin.html.twig', [
            'list' => $list
        ]);
    }
    #[Route('/salle/add', name: 'app_salle_add')]
    public function add(Request $req,ManagerRegistry $manager): Response
    {
       $salle = new Salle();

       $form = $this->createForm(SalleType::class,$salle);

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
                $salle->setImage($fileName);
                $em->persist($salle);
                $em->flush();
                return $this->redirectToRoute('app_salleAdmin');
            }
        }
        return $this->renderForm('salle/salleForm.html.twig',
         ['f' => $form]     
        );
    }
    #[Route('/salle/modify{id}', name: 'app_salle_modify')]
    public function modify(Request $req,ManagerRegistry $manager,SalleRepository $repo,$id): Response
    {
        $salle = $repo->find($id);

        $form = $this->createForm(SalleType::class,$salle);
 
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
                $salle->setImage($fileName);
                $em->persist($salle);
                $em->flush();
                return $this->redirectToRoute('app_salleAdmin');
            }
        }
        return $this->renderForm('salle/salleForm.html.twig',
        ['f' => $form]     
      );
    }
    #[Route('/salle/delete{id}', name: 'app_salle_delete')]
    public function delete(ManagerRegistry $manager,SalleRepository $repo,$id): Response
    {
        $salle = $repo->find($id);
        $em = $manager->getManager();
 
        $em->remove($salle);
        $em->flush();
        return $this->redirectToRoute('app_salleAdmin');
    }
    #[Route('/salle/abonnement{id}', name: 'app_salle_abonnement')]
    public function goToAbonnements(AbonnementRepository $repo, $id): Response
    {
        $list = $repo->findAbonnementsByGymId($id); 
        return $this->render('abonnement/index.html.twig', [
            'list' => $list
        ]);
    }
    #[Route('/salle/materiel{id}', name: 'app_salle_materiel')]
    public function goToMateriels(MaterielRepository $repo, $id): Response
    {
        $list = $repo->findMaterielByGymId($id); 
        return $this->render('materiel/materiel.html.twig', [
            'list' => $list
        ]);
    }
}
