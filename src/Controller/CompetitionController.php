<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Entity\Reservation;
use App\Form\CompetitionType;
use App\Repository\CompetitionRepository;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompetitionController extends AbstractController
{
    #[Route('/competitionAdmin', name: 'app_competition_Admin')]
    public function index(CompetitionRepository $repo): Response
    {
        
        $list = $repo->findAll(); 
        return $this->render('competition/Admin_index.html.twig', [
            'list' => $list
        ]);
    }
    #[Route('/competition/add', name: 'app_competition_add')]
    public function add(Request $req,ManagerRegistry $manager): Response
    {
        $competition = new Competition();

       $form = $this->createForm(CompetitionType::class,$competition);

       $em = $manager->getManager();

       $form->handleRequest($req);
       if($form->isSubmitted() && $form->isValid())
       {
       $em->persist($competition);
       $em->flush();
       return $this->redirectToRoute('app_competition_Admin');
       }

        return $this->renderForm('competition/CompetitionForm.html.twig',
         ['f' => $form]     
        );
    }
    #[Route('/competition/modify{id}', name: 'app_competition_modify')]
    public function modify(Request $req,ManagerRegistry $manager,CompetitionRepository $repo,$id): Response
    {
        $competition = $repo->find($id);

        $form = $this->createForm(CompetitionType::class,$competition);
 
        $em = $manager->getManager();
 
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid())
        {
        $em->persist($competition);
        $em->flush();
        return $this->redirectToRoute('app_competition_Admin');
        }
        return $this->renderForm('competition/competitionForm.html.twig',
        ['f' => $form]     
      );
    }
    #[Route('/competition/delete{id}', name: 'app_competition_delete')]
    public function delete(ManagerRegistry $manager,CompetitionRepository $repo,$id): Response
    {
        $competition = $repo->find($id);
        $em = $manager->getManager();
 
        $em->remove($competition);
        $em->flush();
        return $this->redirectToRoute('app_competition_Admin');
    }
    #[Route('/competition/stat', name: 'app_competition_stat')]
    public function stat(): Response
    {
        return $this->render('competition/stat.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
    #[Route('/competition/scores{id}', name: 'app_competition_scores')]
    public function scores(ReservationRepository $repo,$id): Response
    {
        $competition = $repo->find($id);
        $list = $repo->findByCompetition($id); 
        return $this->render('reservation/index.html.twig', [
            'competition'=>$competition,
            'list' => $list
        ]);
    }
}
