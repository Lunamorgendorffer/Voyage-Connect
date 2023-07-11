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

    #[Route('/', name: 'index')] // fonction pour afficher les users dans le panel admin

    public function index(EntityManagerInterface $em):Response
    {
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/users/index.html.twig', compact('users'));
    }

    
    #[Route('/users/ban', name: 'ban', methods: ['POST'])]
    public function banUser(EntityManagerInterface $em, Request $request, UserRepository $userRepository): Response
    {

        if ($request->isMethod('POST')) {
            $userId = $request->request->get('userId');
            $user = $userRepository->find($userId);

            if ($user) {

                $isBanned = $request->request->has('isBanned');
                $user->setIsBanned($isBanned);
                $em->flush();
            }
            return $this->redirectToRoute('admin_users_index');
        }

    }


}