<?php

namespace App\Controller;

use App\Entity\Organisateur;
use App\Form\OrganisateurType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\OrganisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrganisateurController extends AbstractController
{
    #[Route('/organisateur', name: 'app_organisateur')]
    public function index(OrganisateurRepository $repo): Response
    {
        
        $list = $repo->findAll(); 
        return $this->render('organisateur/index.html.twig', [
            'list' => $list
        ]);
    }
    #[Route('/organisateur/add', name: 'app_organisateur_add')]
    public function add(Request $req,ManagerRegistry $manager): Response
    {
        $organisateur = new Organisateur();

       $form = $this->createForm(OrganisateurType::class,$organisateur);

       $em = $manager->getManager();

       $form->handleRequest($req);
       if($form->isSubmitted() && $form->isValid())
       {
       $em->persist($organisateur);
       $em->flush();
       return $this->redirectToRoute('app_organisateur');
       }

        return $this->renderForm('organisateur/OrganisateurForm.html.twig',
         ['f' => $form]     
        );
    }
    #[Route('/organisateur/modify{id}', name: 'app_organisateur_modify')]
    public function modify(Request $req,ManagerRegistry $manager,OrganisateurRepository $repo,$id): Response
    {
        $organisateur = $repo->find($id);

        $form = $this->createForm(OrganisateurType::class,$organisateur);
 
        $em = $manager->getManager();
 
        $form->handleRequest($req);
        if($form->isSubmitted())
        {
        $em->persist($organisateur);
        $em->flush();
        return $this->redirectToRoute('app_organisateur');
        }
        return $this->renderForm('organisateur/organisateurForm.html.twig',
        ['f' => $form]     
      );
    }
    #[Route('/organisateur/delete{id}', name: 'app_organisateur_delete')]
    public function delete(ManagerRegistry $manager,OrganisateurRepository $repo,$id): Response
    {
        $organisateur = $repo->find($id);
        $em = $manager->getManager();
 
        $em->remove($organisateur);
        $em->flush();
        return $this->redirectToRoute('app_organisateur');
    }
}
