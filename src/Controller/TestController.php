<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
    #[Route('/Client_Acceuil', name: 'app_Acceuil')]
    public function Acceuil(): Response
    {
        return $this->render('./client_index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
