<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Security\RegisterFormAuthenticator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Config\Security\FirewallConfig;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->getCategories();
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'categories' => $categories]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(FirewallConfig $security)
    {
        return $security->logout();
    }

    #[Route(path: '/register', name: 'app_register')]
    public function register(Request $request, CategoryRepository $categoryRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, RegisterFormAuthenticator $authenticator, UserAuthenticatorInterface $userAuthenticator)
    {
        if ($request->isMethod('POST')) {
            $user = new User();
            $user
                ->setPhone($request->request->get('phone'))
                ->setEmail($request->request->get('mail'))
                ->setPassword($passwordHasher->hashPassword($user, $request->request->get('password')));

            $em->persist($user);
            $em->flush();

            return $userAuthenticator->authenticateUser($user, $authenticator, $request);
        }
        $categories = $categoryRepository->getCategories();
        return $this->render('security/register.html.twig', ['categories' => $categories]);
    }
}
