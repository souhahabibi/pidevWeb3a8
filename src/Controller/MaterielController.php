<?php 

namespace App\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Materiel;
use App\Form\MaterielType;
use App\Repository\SalleRepository;
use App\Repository\MaterielRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MaterielController extends AbstractController
{
    #[Route('/materiel', name: 'app_materiel')]
    public function index(MaterielRepository $repo): Response
    {
        $list = $repo->findAll(); 
        return $this->render('materiel/materiel.html.twig', [
            'list' => $list
        ]);
    }
    #[Route('/materiel/add{id}', name: 'app_materiel_add')]
    public function add(Request $req,ManagerRegistry $manager,$id,SalleRepository $repos): Response
    {
       $materiel = new Materiel(); 
       $salle = $repos->find($id); 
       $form = $this->createForm(MaterielType::class,$materiel);

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
               $materiel->setFkIdsalle($salle);     
               $materiel->setImage($fileName); 
               $em->persist($materiel); 
               $em->flush();
               return $this->redirectToRoute('app_salle_materiel', ['id' => $id]);
           }
       }
        return $this->renderForm('materiel/materielForm.html.twig',
         ['f' => $form]     
        );
    }
    #[Route('/materiel/modify{id}', name: 'app_materiel_modify')]
    public function modify(Request $req,ManagerRegistry $manager,MaterielRepository $repo,$id): Response
    {
        $materiel = $repo->find($id);
        $salle = $repo->find($id);
        $form = $this->createForm(MaterielType::class,$materiel);
 
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
                $materiel->setImage($fileName);
                $em->persist($materiel);
                $em->flush();
                return $this->redirectToRoute('app_salle_materiel',['id' => $materiel->getFkIdsalle()->getId()]);
            }
        }
 
        return $this->renderForm('materiel/materielForm.html.twig',
        ['f' => $form]     
      );
    }

    #[Route('/materiel/delete{id}', name: 'app_materiel_delete')]
    public function delete(ManagerRegistry $manager,MaterielRepository $repo,$id): Response
    {
        $materiel = $repo->find($id);
        $em = $manager->getManager();
 
        $em->remove($materiel);
        $em->flush();
        return $this->redirectToRoute('app_salle_materiel', ['id' => $materiel->getFkIdsalle()->getId()]); 
    }

    #[Route('/materiel/pdf/{gymId}', name: 'export_materiel_pdf')]
    public function exportMaterialsPdf(MaterielRepository $repository, $gymId, Request $request): Response
    {
        $materials = $repository->findBy(['fkIdsalle' => $gymId]);
    
        // Create full URL for images
        $host = $request->getSchemeAndHttpHost();
    
        $html = $this->renderView('materiel/pdf.html.twig', [
            'materials' => $materials,
            'host' => $host
        ]);
    
        // Configure Dompdf options
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isRemoteEnabled', true); // Important for loading images via full URL
    
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
    
        // Output the generated PDF to Browser
        $dompdf->stream("materials_gym_".$gymId.".pdf", [
            "Attachment" => true
        ]);
    
        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
} 