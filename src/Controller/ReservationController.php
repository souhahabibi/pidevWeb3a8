<?php

namespace App\Controller;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Competition;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\UserRepository;
use App\Repository\CompetitionRepository;
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
    #[Route('/reservation/add{id}', name: 'app_reservation_add')]
    public function add(ManagerRegistry $manager,CompetitionRepository $repo,UserRepository $repou,ReservationRepository $repor,MailerInterface $mailer,$id) : Response
    {
        $competition = $repo->find($id);
        $client = $repou->find(7);
       
        $reservation = new Reservation();
        $reservation->setFkCompetition($competition);
        $reservation->setFkClient($client);
        $reservation->setScore(0);
        $em = $manager->getManager();
        $em->persist($reservation);
        $em->flush();
        // Send email
        $email = (new Email())
        ->from('Khemiri.Oussema@esprit.tn') // Replace with your email
        ->to($client->getEmail()) // Assuming getClient() returns the email
        ->subject('Reservation Confirmation'."for".$competition->getNom())
        ->text('Your reservation has been confirmed.');

        $mailer->send($email);
        $exist=1;

       $topReservations = $repor->findTopReservationsByCompetition($id);

       return $this->render('competition/competitionView.html.twig', [
        'competition' => $competition,
        'exist' => $exist,
        'topReservations' => $topReservations
    ]);
    }
    #[Route('/reservation/delete{id}', name: 'app_reservation_delete')]
    public function delete(ManagerRegistry $manager,CompetitionRepository $repo,UserRepository $repou,ReservationRepository $repor,$id) : Response
    {
        $competition = $repo->find($id);
        $client = $repou->find(7);
       
        $reservationToDelete = $repor->findOneBy([
            'fkCompetition' => $competition,
            'fkClient' => $client
        ]);
        if ($reservationToDelete) {
            $em = $manager->getManager();
            $em->remove($reservationToDelete);
            $em->flush();
            $exist = 0;
        }
       $topReservations = $repor->findTopReservationsByCompetition($id);

       return $this->render('competition/competitionView.html.twig', [
        'competition' => $competition,
        'exist' => $exist,
        'topReservations' => $topReservations
    ]);
    }
}
