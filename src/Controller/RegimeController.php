<?php

namespace App\Controller;

use App\Entity\Regime;
use App\Entity\User;
use App\Form\RegimeType;
use App\Repository\RegimeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/regime')]
class RegimeController extends AbstractController
{
    #[Route('/', name: 'app_regime_index', methods: ['GET'])]
    public function index(RegimeRepository $regimeRepository): Response
    {
         // $user=$this->getUser();
        $user = $this->getDoctrine()->getRepository(User::class)->find(5);
        return $this->render('regime/index.html.twig', [
            'regimes' => $regimeRepository->findByClient( $user),
        ]);
    }

    #[Route('/coach', name: 'app_regime_index_coach', methods: ['GET'])]
    public function coachByRegimes(RegimeRepository $regimeRepository): Response
    {
        return $this->render('regime/coachRegimes.html.twig', [
            'regimes' => $regimeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_regime_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $regime = new Regime();
        $form = $this->createForm(RegimeType::class, $regime);
        $form->handleRequest($request);
          // $user=$this->getUser();
          $user = $this->getDoctrine()->getRepository(User::class)->find(5);

        if ($form->isSubmitted() && $form->isValid()) {
            $regime->setClientId($user);
            $entityManager->persist($regime);
            $entityManager->flush();

            return $this->redirectToRoute('app_regime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('regime/new.html.twig', [
            'regime' => $regime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_regime_show', methods: ['GET'])]
    public function show(Regime $regime): Response
    {
        return $this->render('regime/show.html.twig', [
            'regime' => $regime,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_regime_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Regime $regime, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegimeType::class, $regime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_regime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('regime/edit.html.twig', [
            'regime' => $regime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_regime_delete', methods: ['POST'])]
    public function delete(Request $request, Regime $regime, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$regime->getId(), $request->request->get('_token'))) {
            $entityManager->remove($regime);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_regime_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/client', name: 'client_app', methods: ['GET'])]
   
    public function clientByRegimes(RegimeRepository $regimeRepository): Response
    {
        return $this->render('regime/client-BMI.html.twig', [
            'regimes' => $regimeRepository->findAll(),
        ]);
    }


    #[Route('/show/stats', name: 'stats', methods: ['GET'])]
    public function statistiques()
    { 
        $repository = $this->getDoctrine()->getRepository(Regime::class);
        $regime = $repository->findAll();

        /* you can also inject "FooRepository $repository" using autowire */

       /* $count = $repository->count();
        dd($count); */

           /*  $countbydate= $repository->createQueryBuilder('a')
            ->select('SUBSTRING(datefin,1,10) As datedufin, COUNT(a) as count')
            ->groupby('datedufin')
            ->getQuery()
            ->getResult(); */
       //
      
       $count= $repository->createQueryBuilder('u')
            ->select('count(u.goal)')
            ->groupby('u.goal')
            ->getQuery()
            ->getResult();

            $countdate= $repository->createQueryBuilder('a')
            ->select('(a.goal)')
            ->groupby('a.goal')
            ->getQuery()
            ->getResult();
        foreach($regime as $regime){

            $date[] = $regime->getGoal();

        }


            for ($i = 0; $i < count($count); ++$i){

                $count1[] = $count[$i][1] ;
                $countdate1[] = $countdate[$i][1];
            }


        return $this->render('regime/stats.html.twig', [
            'date' => json_encode($date ),
            'count1' => json_encode($count1),
            'countdate1' => json_encode($countdate1),
            


        ]);
    }

    
    #[Route('/coach/show', name: 'regime-coach', methods: ['GET'])]
    public function indexCoach(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Regime::class);
        $regime = $repository->findAll();
        return $this->render('regime/coach.html.twig', [
            'regimes' => $regime,
        ]);
    }

   
    #[Route('/coach/{id}', name: 'regime-validate', methods: ['GET'])]
    public function validate(RegimeRepository $regimeRepository, $id, EntityManagerInterface $entityManager): Response
    {
        $regime = $this->getDoctrine()->getRepository(Regime::class)->find($id);
       

        $regime->setVerified(true);
        $entityManager->persist($regime);
        $entityManager->flush();

        return $this->redirectToRoute('regime-coach');
    }

    #[Route('/bmi/show', name: 'bmi-calcul', methods: ['GET'])]
    public function indexBMI(): Response
    {
        return $this->render('regime/client-BMI.html.twig', [
        ]);
    }


}
