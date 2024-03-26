<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Entity\Reservation;
use App\Form\CompetitionType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompetitionRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twilio\Rest\Client;
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
       //$this->sendSmsAction("a new competition has been Added : "+$competition->getNom(),'+21623061687');
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

    //////////////////////////////////////////////////////////////////////////////
    ///////////////////^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^////////////////
    ////////////////////////////////ADMIN_ADMIN_ADMIN/////////////////////////////
    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////CLIENT_CLIENT_CLIENT////////////////////////////
    //////////////////////////////////////////////////////////////////////////////

    #[Route('/competition', name: 'app_competition_Client')]
    public function indexClient(Request $request,CompetitionRepository $competitionRepository, EntityManagerInterface $entityManager): Response
    {
        
        $form = $this->createFormBuilder(null)
            ->setMethod('GET')
            ->add('name', TextType::class, [
                'required' => false,
            ])
            ->add('ongoing', CheckboxType::class, [
                'label' => 'Ongoing',
                'required' => false,
            ])
            ->add('search', SubmitType::class, [
                'attr' => ['class' => 'primary-btn']
            ])
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                // Now pass the name and ongoing status to findBySearchCriteria
                $competitions = $competitionRepository->findBySearchCriteria($data['name'], $data['ongoing']);
            } else {
                $competitions = $competitionRepository->findAll();
            }
    
            return $this->render('competition/client_index.html.twig', [
                'form' => $form->createView(),
                'list' => $competitions,
            ]);
    }
    #[Route('/competition/view{id}', name: 'app_competition_view')]
    public function view(CompetitionRepository $repo, ReservationRepository $reservationRepo, $id): Response
    {
        $clientId = 7; 
        $competition = $repo->find($id);
        $topReservations = $reservationRepo->findTopReservationsByCompetition($id);
    
        // Check if the client has a reservation
        $exist = $reservationRepo->clientHasReservation($clientId, $id) ? 1 : 0;
    
        return $this->render('competition/competitionView.html.twig', [
            'competition' => $competition,
            'exist' => $exist,
            'topReservations' => $topReservations
        ]);
    }
    //////////////////////////////////////////////////////////////////////////////
    ///////////////////^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^////////////////
    //////////////////////////////CLIENT_CLIENT_CLIENT////////////////////////////
    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////METIER//////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////

    public function sendSmsAction($msg,$to): void
    {
        $accountSid = $_ENV['TWILIO_ACCOUNT_SID']; // Use environment variable
        $authToken = $_ENV['TWILIO_AUTH_TOKEN']; // Use environment variable
        $fromNumber = $_ENV['TWILIO_NUMBER']; // Use environment variable
        $toNumber = $to; // Replace with the recipient's phone number
        $message = $msg;

        $client = new Client($accountSid, $authToken);

        try {
            $client->messages->create(
                $toNumber,
                [
                    'from' => $fromNumber,
                    'body' => $message,
                ]
            );
            $response = 'SMS sent successfully.';
        } catch (\Exception $e) {
            $response = 'Failed to send SMS: ' . $e->getMessage();
        }
    }
 }

