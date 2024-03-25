<?php

namespace App\Controller;

use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/reservationAdmin', name: 'app_reservation_admin')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
    #[Route('/reservation/modify{id}', name: 'app_reservation_modify')]
    public function modify(Request $req,ManagerRegistry $manager,ReservationRepository $repo,$id): Response
    {
        $reservation = $repo->find($id);

        $form = $this->createForm(ReservationType::class,$reservation);
 
        $em = $manager->getManager();
 
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid())
        {
        $em->persist($reservation);
        $em->flush();
        return $this->redirectToRoute('app_competition_scores', [
            'id' => $reservation->getFkCompetition()->getId() // Ensure this method exists and returns the ID
        ]);
        }
        return $this->renderForm('reservation/reservationForm.html.twig',
        ['f' => $form]     
      );
    }
}
