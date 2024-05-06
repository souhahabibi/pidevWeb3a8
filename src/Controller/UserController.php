<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

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
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
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
        return $this->render('client_base.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }



    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setMotdepasse(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('motdepasse')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('esprat@esprit.tn', 'Esprat mailing'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }
            return $this->renderForm('user/new.html.twig', [
                'user' => $user,
                'form' => $form,
            ]);}

    #[Route('/user/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $motdepasse = $form->get('motdepasse')->getData();

            // Vérifiez si le champ motdepasse est défini et n'est pas vide
            if (!empty($motdepasse)) {
                // Traitez le mot de passe uniquement s'il n'est pas vide
                $hashedPassword = $userPasswordHasher->hashPassword($user, $motdepasse);
                $user->setMotdepasse($hashedPassword);
            }
            else
                if ($motdepasse === null || empty($motdepasse)) {

                    // If the password field is empty or null, keep the existing password unchanged
                // Retrieve the current user entity from the database to get the current password
                $currentUser = $entityManager->getRepository(User::class)->find($user->getId());
                $user->setMotdepasse($currentUser->getMotdepasse());
            }

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


    #[Route('/profile', name: 'app_profil')]
    public function profile(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $userId = $this->getUser()->getId(); // Exemple avec la méthode getUser() si vous utilisez Symfony Security

        // Récupérer l'utilisateur à partir de l'ID
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Créer le formulaire et le gérer
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer la soumission du formulaire
            $hashedPassword = $userPasswordHasher->hashPassword($user, $form->get('motdepasse')->getData());
            $user->setMotdepasse($hashedPassword);
            $entityManager->flush();

            return $this->redirectToRoute('app_profilclient', [], Response::HTTP_SEE_OTHER);
        }

        // Rendre le formulaire et les données de l'utilisateur
        return $this->render('security/profil.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profileclient', name: 'app_profilclient', methods: ['GET', 'POST'])]
    public function profile2(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        // Récupérer l'ID de l'utilisateur à partir de la session ou de la requête
        $userId = $this->getUser()->getId();

        // Récupérer l'utilisateur à partir de l'ID
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Créer le formulaire et le gérer
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer la soumission du formulaire
            $hashedPassword = $userPasswordHasher->hashPassword($user, $form->get('motdepasse')->getData());
            $user->setMotdepasse($hashedPassword);
            $entityManager->flush();

            return $this->redirectToRoute('app_profilclient', [], Response::HTTP_SEE_OTHER);
        }

        // Rendre le formulaire et les données de l'utilisateur
        return $this->render('security/profilclient.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);

    }
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
            $user->setIsVerified(true);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_user_new');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }



}
