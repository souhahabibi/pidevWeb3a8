<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }



    #[Route('/admin', name: 'app_admin_index', methods: ['GET'])]
    public function index7(UserRepository $userRepository): Response
    {
        return $this->render('admin_base.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/coach', name: 'app_coach_index', methods: ['GET'])]
    public function index2(UserRepository $userRepository): Response
    {
        return $this->render('coach_base.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/client', name: 'app_client_index', methods: ['GET'])]
    public function index3(UserRepository $userRepository): Response
    {
        return $this->render('client_index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }



    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager , UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $userPasswordHasher->hashPassword($user, $form->get('motdepasse')->getData());
            $user->setMotdepasse($hashedPassword);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/user/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager , UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $userPasswordHasher->hashPassword($user, $form->get('motdepasse')->getData());
            $user->setMotdepasse($hashedPassword);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

//    /**
//     * @throws TransportExceptionInterface
//     */
//    #[Route('/mailer', name: 'app_mailer')]
//    public function sendmail(MailerInterface $mailer): Response
//    {
//        $email = (new Email())
//            ->from('greenmenu2024@outlook.com')
//            ->to('moslemhaddadi@gmail.com')
//            ->subject('Signed up ')
//            ->text('Your account has been confirmed.');
//
//        $mailer->send($email);
//
//        return new Response(
//            'Email sent successfully'
//        );
//    }
    #[Route('/profile', name: 'app_profil')]
    public function profile(): Response
    {
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            // Gérer le cas où l'utilisateur n'est pas connecté
            // Rediriger vers la page de connexion par exemple
            return $this->redirectToRoute('app_login');
        }

        // Passer les données de l'utilisateur à la vue Twig
        return $this->render('security/profil.html.twig', [
            'user' => $user,
        ]);
    }

        #[Route('/profileclient', name: 'app_profilclient')]
    public function profile2(): Response
    {
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            // Gérer le cas où l'utilisateur n'est pas connecté
            // Rediriger vers la page de connexion par exemple
            return $this->redirectToRoute('app_login');
        }

        // Passer les données de l'utilisateur à la vue Twig
        return $this->render('security/profilclient.html.twig', [
            'user' => $user,
        ]);


    }


}
