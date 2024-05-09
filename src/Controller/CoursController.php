<?php

namespace App\Controller;
use App\Entity\Cours;
use App\Service\SmsGenerator;
use App\Form\CoursType;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Imagine\Gd\Imagine;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Imagine\Image\Box;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Translation\LocaleSwitcher;
class CoursController extends AbstractController
{
    #[Route('/cours', name: 'app_cours')]
    public function index(): Response
    {
        return $this->render('client_cours.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }
  
    #[Route('/client/cours', name: 'app_translate')]
    public function translate(TranslatorInterface $translator, Request $request, CoursRepository $coursRepository): Response
    {
        $lang = $request->query->get('lang', 'fr');
    
        $cours = $coursRepository->findAll();
    
        $translated = [];
    
        foreach ($cours as $unCours) {
            $translated[] = [
                'nom' => $translator->trans($unCours->getNom(), [], null, $lang),
                'description' => $translator->trans($unCours->getDescription(), [], null, $lang),
                'niveau' => $translator->trans($unCours->getNiveau(), [], null, $lang),
                'image' => $unCours->getImage(),
                'id' => $unCours->getId(),
            ];
        }
    
        // Translate additional phrases
        $translations = [
            'voir_calendrier' => $translator->trans('Voir le Calendrier', [], null, $lang),
            'voir_exercices' => $translator->trans('Voir les Exercices', [], null, $lang),
        ];
    
        return $this->render('client_cours.html.twig', [
            'translated' => $translated,
            'labels' => [
                'nom' => $translator->trans('Nom', [], null, $lang),
                'description' => $translator->trans('Description', [], null, $lang),
                'niveau' => $translator->trans('Niveau', [], null, $lang),
            ],
            'translations' => $translations, // Pass the translated phrases to the template
        ]);
    }

    
    
    

#[Route('/cours/list', name: 'cours_liste')]
public function courspag (Request $request, CoursRepository $coursRepository, PaginatorInterface $paginator): Response
{
    $pagination = $paginator->paginate(
        $coursRepository->findAll(), 
        $request->query->getInt('page', 1), 
        2
    );

    return $this->render('cours/liste.html.twig', [
        'pagination' => $pagination,
    ]);
}

/*
    #[Route('/cours/list', name: 'cours_liste')]
    public function listeCours(CoursRepository $coursRepository)
    {
        $cours = $coursRepository->findAll();
    
        return $this->render('cours/liste.html.twig', [
            'cours' => $cours,
        ]);
    }
*/
    #[Route('/client', name: 'client_app')]
    public function clientCours(CoursRepository $coursRepository)
    {
        $cours = $coursRepository->findAll();
    
        return $this->render('client_base.html.twig', [
            'cours' => $cours,
        ]);
    }
/*
    #[Route('/client/cours', name: 'client_cours2')]
    public function clientCours2(CoursRepository $coursRepository)
    {
        $cours = $coursRepository->findAll();
    
        return $this->render('client_cours.html.twig', [
            'cours' => $cours,
        ]);
    }
*/

    #[Route('/Client_Acceuil', name: 'app_Acceuil')]
    public function Acceuil(): Response
    {
        return $this->render('client_index.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }



    #[Route('/coach' ,name: 'cours_coach')]
    public function coach(): Response
    {
        return $this->render('coach_base.html.twig', [
            'controller_name' => 'CoursController']);
    }
   // private $smsGenerator;

   /* public function __construct(SmsGenerator $smsGenerator)
    {
        $this->smsGenerator = $smsGenerator;
    }*/
    

    #[Route('/ajoutercours', name: 'ajouter_cours')]
public function ajoutercours(Request $request, SmsGenerator $smsGenerator): Response
{
    $cours = new Cours();

    // Create the form using CoursType
    $form = $this->createForm(CoursType::class, $cours);

    // Handle the submission (if any)
    $form->handleRequest($request);

    // Check if the form is submitted and valid
    if ($form->isSubmitted() && $form->isValid()) {
        // Convert the "planning" property to a string
        $planning = $cours->getPlanning();
        $cours->setPlanning($planning);

        // Handle the image upload
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('image')->getData();
     
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            // This ensures that the filename is unique
            $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            // Move the file to the directory where images are stored
            try {
                $imageFile->move(
                    $this->getParameter('images_directory_p'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle exception if something happens during file upload
            }

            // Update the image path in the Cours entity
            $cours->setImage($newFilename);
        }

        // Persist and flush the entity
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cours);
        $entityManager->flush();

        // SMS sending logic remains the same
        $name = 'ESPRAT';
        $text = 'Un nouveau cours a été ajouté : ' . $cours->getNom();
        //$smsGenerator->SendSms('+21624019297',$name, $text);
        $smsGenerator->SendSms('+21624019297',$name, $text);

        // Redirect to another page or display a success message, for example
        return $this->redirectToRoute('cours_liste');
    }

    // If the form is not submitted or not valid, just display the form
    return $this->render('cours/AjouterCours.html.twig', [
        'form' => $form->createView(),
    ]);
}

    
    





    #[Route('/cours/{id}/modifier', name: 'modifier_cours')]
    public function edit(Request $request, $id, CoursRepository $coursRepository): Response
    {
        $cours = $coursRepository->find($id);
    
        if (!$cours) {
            throw $this->createNotFoundException('Le cours demandé n\'existe pas');
        }
    
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the image upload
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // This ensures that the filename is unique
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory_p'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
    
                // Update the image path in the Cours entity
                $cours->setImage($newFilename);
            }
    
            $this->getDoctrine()->getManager()->flush();
    
            return $this->redirectToRoute('cours_liste');
        }
    
        return $this->render('cours/modifierCours.html.twig', [
            'form' => $form->createView(),
        ]);
    }


 


    #[Route('/cours/search', name: 'app_cours_search')]
    public function search(Request $request, CoursRepository $coursRepository, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('query');
        if ($query) {
            $cours = $coursRepository->createQueryBuilder('a')
                ->where('a.nom LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->getQuery();


            $pagination = $paginator->paginate(
                $cours, 
                $request->query->getInt('page', 1), 
                10 
            );
        } else {

            $pagination = null; 
        }

        return $this->render('cours/liste.html.twig', [
            'pagination' => $pagination,
        ]);
    }


    #[Route('/cours/tri', name: 'app_cours_tri')]
    public function tri(Request $request, CoursRepository $coursRepository, PaginatorInterface $paginator): Response
    {
        $order = $request->query->get('order', 'asc'); 
        $field = $request->query->get('field', 'nom'); 

        if (!in_array(strtolower($order), ['asc', 'desc'])) {
            $order = 'asc'; 
        }

        if (!in_array($field, ['nom', 'date'])) {
            $field = 'nom'; 
        }

        $queryBuilder = $coursRepository->createQueryBuilder('a')
            ->orderBy('a.' . $field, $order);

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10 
        );
        return $this->render('cours/liste.html.twig', [
            'pagination' => $pagination,

        ]);
    }


    #[Route('/cours/{id}', name: 'cours_supprimer')]
    public function supprimerCours(?int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cours = $entityManager->getRepository(Cours::class)->find($id);
    
        if (!$cours) {
            throw $this->createNotFoundException('cours non trouvé');
        }
    
        $entityManager->remove($cours);
        $entityManager->flush();
    
         // Rediriger vers une page de confirmation ou ailleurs
         return $this->redirectToRoute('cours_liste');
    
    }


    #[Route('/calendar', name: 'app_calendar')]
    public function index10(): Response
    {
        return $this->render('calendar.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }


    #[Route('/calendar_index', name: 'app_calendar')]
    public function index20(CoursRepository $coursRepository,Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $events = $coursRepository->findAll();

        $rdvs = [
        ];

        foreach($events as $event){
            
            
            $rdvs[] = [
                'id' => $event->getId(),
                'title' =>$event->getNom(),
                'start' => $event->getPlanning()->format('Y-m-d H:i:s'),
                //'end' => date_modify($event->getPlanning(),"30 minutes")->format('Y-m-d H:i:s'),
               

            ];
        }

        $data = json_encode($rdvs);
        
        // Fetching all Cours entities from the database
        $cours = $entityManager->getRepository(Cours::class)->findAll();

        return $this->render('calendar_index.html.twig', [
            'courss' => $cours,
            'data'=>$data,
            // Passing the fetched Cours entities to the Twig template
        ]);
    }




}
