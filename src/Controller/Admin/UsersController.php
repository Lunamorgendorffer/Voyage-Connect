<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/users', name: 'admin_users_')]
class UsersController extends AbstractController
{

    #[Route('/', name: 'index')] 
    // Fonction pour afficher les utilisateurs dans le panneau d'administration
    public function index(EntityManagerInterface $em):Response
    {
        // Récupère tous les utilisateurs à partir de la base de données
        $users = $em->getRepository(User::class)->findAll();

        // Renvoie la vue 'admin/users/index.html.twig' avec les utilisateurs récupérés
        return $this->render('admin/users/index.html.twig', compact('users'));
    }

    
    #[Route('/users/ban', name: 'ban', methods: ['POST'])]
    // Fonction pour bannir/débannir un utilisateur
    public function banUser(EntityManagerInterface $em, Request $request, UserRepository $userRepository): Response
    {

        if ($request->isMethod('POST')) 
        {
            // Récupère l'ID de l'utilisateur à bannir/débannir depuis la requête POST
            $userId = $request->request->get('userId');
            // Trouve l'utilisateur correspondant dans la base de données
            $user = $userRepository->find($userId);

            if ($user) {
                // Vérifie si la requête contient le paramètre 'isBanned' pour déterminer s'il doit être banni ou débanni
                $isBanned = $request->request->has('isBanned');
               // Définit l'état de bannissement de l'utilisateur en fonction de la valeur de 'isBanned'
                $user->setIsBanned($isBanned);
                // Enregistre les changements dans la base de données
                $em->flush();
            }
            // Redirige l'utilisateur vers la page d'index des utilisateurs de l'administration
            return $this->redirectToRoute('admin_users_index');
        }

    }


}