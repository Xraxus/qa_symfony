<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationType;
use App\Form\UserEditType;
use App\Form\UserPasswordChangeType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/users')]
class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    // Rejestracja nowego użytkownika (publicznie dostępne)
    #[Route('/register', name: 'user_register', methods: ['GET', 'POST'])]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPlainPassword());
            $user->setPassword($hashedPassword);
            $user->eraseCredentials();

            // Domyślnie rola ROLE_USER jest dodawana w User::getRoles()

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Registration successful. You can now login.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    // Lista użytkowników — dostępna tylko dla admina
    #[Route('/', name: 'user_list', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function list(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }

    // Edycja użytkownika przez administratora (np. zmiana ról, blokada)
    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserEditType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Sprawdzenie, czy nie odbieramy roli admin ostatniemu adminowi
            if (!in_array('ROLE_ADMIN', $user->getRoles(), true)) {
                $adminCount = $this->userRepository->countAdmins();
                if ($adminCount === 0) {
                    $this->addFlash('error', 'Nie możesz odebrać roli administratora ostatniemu adminowi.');
                    return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
                }
            }

            $this->em->flush();
            $this->addFlash('success', 'User updated successfully.');
            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', [
            'userForm' => $form->createView(),
            'userEntity' => $user,
        ]);
    }

    // Edycja własnego profilu (email, nickname)
    #[Route('/profile/edit', name: 'user_profile_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function editProfile(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UserEditType::class, $user, ['is_admin' => false]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Profile updated.');
            return $this->redirectToRoute('user_profile_edit');
        }

        return $this->render('user/edit_profile.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

    // Zmiana hasła użytkownika
    #[Route('/profile/change-password', name: 'user_change_password', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function changePassword(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UserPasswordChangeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $newPassword = $data['plainPassword'];

            $hashed = $this->passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashed);
            $this->em->flush();

            $this->addFlash('success', 'Password changed successfully.');
            return $this->redirectToRoute('user_profile_edit');
        }

        return $this->render('user/change_password.html.twig', [
            'passwordForm' => $form->createView(),
        ]);
    }
}
