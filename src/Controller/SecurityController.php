<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/setup-admin', name: 'app_setup_admin')]
    public function setupAdmin(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        KernelInterface $kernel,
    ): Response {
        if ($kernel->getEnvironment() === 'prod') {
            throw $this->createNotFoundException();
        }

        if ($em->getRepository(User::class)->findOneBy(['username' => 'admin'])) {
            return new Response('ادمین قبلاً ساخته شده است!');
        }

        $user = new User();
        $user->setUsername('admin');
        $user->setRoles(['ROLE_ADMIN']);

        $user->setPassword($hasher->hashPassword($user, '123456'));

        $em->persist($user);
        $em->flush();

        return new Response('کاربر مدیر با موفقیت ساخته شد! نام کاربری: admin | رمز عبور: 123456');
    }
}
