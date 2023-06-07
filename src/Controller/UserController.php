<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $users,
        ]);
    }

    // // fonction ajout + edit une post
    // #[Route('/post/add', name: 'add_post')]
    // #[Route('/post/{id}/edit', name: 'edit_post')]
    // public function add(EntityManagerInterface $entityManager, User $user = null, Request $request): Response 
    // {
    //     $user= $this->getUser(); 
    //     if (!$user){ // si la user n'existe pas 
    //         $user = new user();  // alors crée un nouvel objet user 
    //     }
    //     // on crée le formulaire 
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     //quand on sousmet le formulaire 
    //     if($form->isSubmitted() && $form->isValid()){
    //         // $images = $form->get('image')->getData();
    //         $user->setUser($user);
    //         $user->setCreationDate(new \DateTime());

    //         $user = $form->getData();
    //         $entityManager->persist($user);// = prepare
    //         $entityManager->flush();// execute, on envoie les données dans la db 

    //         return $this->redirectToRoute('app_user');

    //     }

    //     // vue pour afficher le formulaire 
    //     return $this->render('user/adduser.html.twig', [
    //        'formAdduser' => $form->createView(),
    //        'edit'=> $user->getId(),
            
    //     ]);

    // }

    // fonction delete d'une user 
    #[Route('/user/{id}/delete', name: 'delete_user')]
    public function delete(EntityManagerInterface $entityManager, User $user): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user');

    }

    // fonction pour afficher la page detail de la user 
    #[Route('/user/{id}', name: 'show_user')]
    public function show(User $user): Response {
        
        // Retourne sur la vue 'user/detailuser.html.twig' avec les données suivantes
        return $this->render('user/detailuser.html.twig', [
        'user' => $user,             // La user à afficher
        ]);
    }


    

}


