<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Salle;
use App\Form\AbonnementType;
use App\Repository\SalleRepository;
use App\Repository\AbonnementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AbonnementController extends AbstractController
{
    #[Route('/abonnement', name: 'app_abonnement')]
    public function index(AbonnementRepository $repo): Response
    {
        $list = $repo->findAll(); 
        return $this->render('abonnement/index.html.twig', [
            'list' => $list
        ]);
    }

    #[Route('/Abonnement/add{id}', name: 'app_abonnement_add')]
    public function add(Request $req,ManagerRegistry $manager,$id,SalleRepository $repos): Response
    {
       $abonnement = new Abonnement(); 
       $salle = $repos->find($id); 
       $form = $this->createForm(AbonnementType::class,$abonnement);

       $em = $manager->getManager();

       $form->handleRequest($req);

       if($form->isSubmitted() && $form->isValid())
       {
        $abonnement->setFkIdsalle($salle);
        $em->persist($abonnement);
        $em->flush();
       return $this->redirectToRoute('app_salle_abonnement', ['id' => $id]);
       }

        return $this->renderForm('abonnement/abonnementForm.html.twig',
         ['f' => $form]     
        );
    }
    #[Route('/Abonnement/modify{id}', name: 'app_abonnement_modify')]
    public function modify(Request $req,ManagerRegistry $manager,AbonnementRepository $repo,$id): Response
    {
        $abonnement = new Abonnement();
        $abonnement = $repo->find($id);
        $form = $this->createForm(AbonnementType::class,$abonnement);
 
        $em = $manager->getManager();
 
        $form->handleRequest($req); 
 
        if($form->isSubmitted() && $form->isValid())
        { 
        $em->persist($abonnement);
        $em->flush();
        return $this->redirectToRoute('app_salle_abonnement', ['id' => $abonnement->getFkIdsalle()->getId()]);
        }
 
        return $this->renderForm('abonnement/abonnementForm.html.twig',
        ['f' => $form]     
      );
    }

    #[Route('/Abonnement/delete{id}', name: 'app_abonnement_delete')]
    public function delete(ManagerRegistry $manager,AbonnementRepository $repo,$id): Response
    {
        $abonnement = $repo->find($id);
        $em = $manager->getManager();
 
        $em->remove($abonnement);
        $em->flush();
        return $this->redirectToRoute('app_abonnement');
    }
}
