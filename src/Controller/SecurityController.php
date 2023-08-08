<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        // Vérifier si l'utilisateur est déjà connecté, dans ce cas, redirigez-le où vous le souhaitez
        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_home'); // Remplacez "route_after_login" par le nom de la route vers laquelle vous souhaitez rediriger l'utilisateur connecté.
        }

        // Vérifier si l'utilisateur est banni, s'il l'est, empêcher la connexion
        if ($security->isGranted('IS_BANNED_FALSE')) {
            return $this->redirectToRoute('app_login'); // Remplacez "route_user_banned" par le nom de la route vers laquelle vous souhaitez rediriger un utilisateur banni.
        }

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
}
