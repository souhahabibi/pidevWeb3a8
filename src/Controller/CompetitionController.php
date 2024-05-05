<?php

namespace App\Controller;

use Twilio\Rest\Client;
use App\Entity\Competition;
use App\Entity\Reservation;
use App\Form\CompetitionType;
use App\Service\ProfanityFilter;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompetitionRepository;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompetitionController extends AbstractController
{
    private $profanityFilter;
    private $entityManager;

    public function __construct(ProfanityFilter $profanityFilter, EntityManagerInterface $entityManager)
    {
        $this->profanityFilter = $profanityFilter;
        $this->entityManager = $entityManager;
    }   
    #[Route('/competitionAdmin', name: 'app_competition_Admin')]
    public function index(CompetitionRepository $repo): Response
    {
        
        $list = $repo->findAll(); 
        return $this->render('competition/Admin_index.html.twig', [
            'list' => $list
        ]);
    }
    #[Route('/competition/add', name: 'app_competition_add')]
    public function add(Request $req, ManagerRegistry $manager, ProfanityFilter $profanityFilter): Response 
    {
        
        $competition = new Competition();
        $form = $this->createForm(CompetitionType::class, $competition);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            // Apply profanity filter to necessary fields
            $filteredName = $profanityFilter->filterText($competition->getNom());
            $filteredDescription = $profanityFilter->filterText($competition->getDescription());
            
            $competition->setNom($filteredName);
            $competition->setDescription($filteredDescription);

            $em = $manager->getManager();
            $em->persist($competition);
            $em->flush();
            //$this->sendSmsAction("a new competition has been Added : ".$competition->getNom(),'+21623061687');
            // Optionally, add a flash message to indicate successful addition
            $this->addFlash('success', 'Competition added successfully without any inappropriate language.');

            return $this->redirectToRoute('app_competition_Admin');
        }

        return $this->renderForm('competition/CompetitionForm.html.twig', ['f' => $form]);
    }

    #[Route('/competition/modify{id}', name: 'app_competition_modify')]
    public function modify(Request $req, ManagerRegistry $manager, CompetitionRepository $repo, ProfanityFilter $profanityFilter, $id): Response
    {
        $competition = $repo->find($id);
        if (!$competition) {
            throw $this->createNotFoundException('No competition found for id '.$id);
        }

        $form = $this->createForm(CompetitionType::class, $competition);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            // Apply profanity filter to necessary fields
            $competition->setNom($profanityFilter->filterText($competition->getNom()));
            $competition->setDescription($profanityFilter->filterText($competition->getDescription()));

            $em = $manager->getManager();
            $em->persist($competition);
            $em->flush();

            $this->addFlash('success', 'Competition updated successfully with filtered content.');
            return $this->redirectToRoute('app_competition_Admin');
        }

        return $this->renderForm('competition/competitionForm.html.twig', [
            'f' => $form
        ]);
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
    public function stat(CompetitionRepository $competitionRepository): Response
    {
        $statistics = $competitionRepository->getAverageReservationsPerDayOfWeek();
        $monthlyStats = $competitionRepository->getAverageReservationsPerMonth();

        return $this->render('competition/stat.html.twig', [
            'statistics' => $statistics,
            'monthlyStats' => $monthlyStats,

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
public function indexClient(Request $request, CompetitionRepository $competitionRepository, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
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
    $competitions = $competitionRepository->findAll(); // Default to all competitions

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        // Fetch filtered results
        $query = $competitionRepository->findBySearchCriteria($data['name'], $data['ongoing']);
    } else {
        $query = $competitionRepository->findAll();
    }

    // Pagination
    $pagination = $paginator->paginate(
        $query, // query NOT result
        $request->query->getInt('page', 1), // Default page number is 1
        5 // Number of results per page
    );

    return $this->render('competition/client_index.html.twig', [
        'form' => $form->createView(),
        'list' => $pagination, // Pass the pagination object instead of the list
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


    
/////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////STATISTICS FOR COMPETITION //////////////////////////////////

#[Route('/competition/{id}/distribution', name: 'competition_distribution')]
public function competitionDistribution(int $id, CompetitionRepository $competitionRepository): Response
{
    $scores = $competitionRepository->findScoresByCompetition($id);
    if (empty($scores)) {
        throw $this->createNotFoundException('No scores available for this competition.');
    }

    $mean = array_sum($scores) / count($scores);
    $variance = array_sum(array_map(function ($score) use ($mean) {
        return pow($score - $mean, 2);
    }, $scores)) / count($scores);

    if ($variance == 0) {
        $this->addFlash('warning', 'All scores are the same. No distribution can be displayed.');
        return $this->redirectToRoute('your_fallback_route'); // Adjust the fallback route as necessary
    }

    $stdDeviation = sqrt($variance);
    $dataSet = [];

    for ($x = 0; $x <= 100; $x++) {
        $pdf = (1 / ($stdDeviation * sqrt(2 * M_PI))) * exp(-pow($x - $mean, 2) / (2 * $variance));
        $dataSet[] = ['x' => $x, 'y' => $pdf];
    }

    return $this->render('competition/distribution.html.twig', [
        'dataSet' => $dataSet,
        'mean' => $mean,  // Include this line to pass the mean value
        'competitionId' => $id
    ]);
}













 }

