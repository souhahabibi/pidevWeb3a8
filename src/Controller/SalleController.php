<?php

namespace App\Controller;
use App\Entity\Salle;
use App\Form\SalleType;
use App\Entity\Materiel;
use App\Entity\Abonnement;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\SalleRepository;
use App\Repository\CouponRepository;
use App\Repository\MaterielRepository;
use App\Repository\AbonnementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



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
    #[Route('/salleClient', name: 'app_salleClient')]
    public function indexC(SalleRepository $repo): Response
    {
        $list = $repo->findAll(); 
        return $this->render('salle/salleClient.html.twig', [
            'list' => $list
        ]);
    }
    #[Route('/salle/add', name: 'app_salle_add')]
    public function add(Request $req, ManagerRegistry $manager,MailerInterface $mailer,UserRepository $repou): Response
    {
        $clients=$repou->findAll();
        $salle = new Salle();
        $form = $this->createForm(SalleType::class, $salle);
        $em = $manager->getManager();
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            if ($file instanceof UploadedFile) {
                // Vérifie si c'est une image
                if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                    $this->addFlash('error', 'Le fichier doit être une image (JPEG, PNG, GIF)');
                    return $this->redirectToRoute('app_salle_add');
                }
    
                
    
                try {
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();
                    $file->move($this->getParameter('images_directory'), $fileName);
                    $salle->setImage($fileName);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur s\'est produite lors du téléchargement du fichier');
                    return $this->redirectToRoute('app_salle_add');
                }
            } else {
                $this->addFlash('error', 'Veuillez sélectionner un fichier');
                return $this->redirectToRoute('app_salle_add');
            }
    
            $em->persist($salle);
            $em->flush();
            
            foreach ($clients as $client) {
                $email = (new Email())
                    ->from('cyrine.chalghoumi@esprit.tn') // Replace with your email
                    ->to($client->getEmail()) // Assuming getClient() returns the email
                    ->subject('New GYM Opening!')
                    ->text('We are excited to announce the opening of our new gym: ' . $salle->getNom() . '. We look forward to welcoming you soon!'); // Assuming salle has a getNom() method
                $mailer->send($email);
            }

            return $this->redirectToRoute('app_salleAdmin');
        }
    
        return $this->renderForm('salle/salleForm.html.twig', ['f' => $form]);
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
            'list' => $list,
            'id' => $id
        ]);
    }
    #[Route('/salle/materiel{id}', name: 'app_salle_materiel')]
    public function goToMateriels(MaterielRepository $repo, $id): Response 
    {
        $list = $repo->findMaterielByGymId($id); 
        return $this->render('materiel/materiel.html.twig', [
            'list' => $list,
            'id' => $id
        ]);
    } 

#[Route('/salle/stats', name: 'app_salle_stats')]
public function goToStats(SalleRepository $salleRepository, MaterielRepository $materielRepository): Response
{
    $salles = $salleRepository->findAll();
    $barChartData = [];
    $pieChartData = [];
    $totalPrix = 0;

    // Aggregate data for the bar chart
    foreach ($salles as $salle) {
        $quantites = $materielRepository->findSumQuantitesBySalle($salle->getId());
        $barChartData[] = [
            'salle' => $salle->getNom(),
            'quantites' => $quantites,
        ];
    }

    // Collecting all materiels for the pie chart
    $allMateriels = $materielRepository->findAll();

    foreach ($allMateriels as $materiel) {
        $prixUnitaire = $materiel->getPrix() / $materiel->getQuantite();
        $totalPrix += $prixUnitaire;
    }

    foreach ($allMateriels as $materiel) {
        $prixUnitaire = $materiel->getPrix() / $materiel->getQuantite();
        $percentage = ($prixUnitaire / $totalPrix) * 100;
        $pieChartData[] = [
            'label' => $materiel->getNom() . sprintf(": %.2f%% (%.2f dt)", $percentage, $prixUnitaire),
            'value' => $prixUnitaire
        ];
    }

    return $this->render('salle/salleStats.html.twig', [
        'barChartData' => $barChartData,
        'pieChartData' => $pieChartData
    ]);
}
///////////////////////////////////////////////////CLIENT/////////////////////////////////////////


#[Route('/salleClient/abonnementC{id}', name: 'app_salleClient_abonnement')]
public function goToAbonnementsC(CouponRepository $repoc,AbonnementRepository $repo,$id): Response
{
    $promo = $repoc->findOneBy(['user' => 7]);
    $list = $repo->findAbonnementsByGymId($id); 
    if($promo==NULL)
      $promo=0;
    else
      $promo=$promo->getValeur();
    return $this->render('abonnement/abonnementClient.html.twig', [
        'list' => $list,
        'id' => $id,
        'valeur' => $promo
    ]);
}
    #[Route('/salleClient/materielC{id}', name: 'app_salleClient_materiel')]
    public function goToMaterielsC(MaterielRepository $repo, $id): Response
    {
        $list = $repo->findMaterielByGymId($id); 
        return $this->render('materiel/materielClient.html.twig', [
            'list' => $list,
            'id' => $id
        ]);
    }


    #[Route('/salle/map/{id}', name: 'app_salle_map')]
    public function showMap(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $salle = $entityManager->getRepository(Salle::class)->find($id);

        if (!$salle) {
            throw $this->createNotFoundException('Salle non trouvée');
        }

        return $this->render('salle/map.html.twig', [
            'salle' => $salle,
        ]);
    }

    

}
