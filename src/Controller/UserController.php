<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {


        return $this->render('user/index.html.twig');
    }

    // fonction ajout + edit une post
    #[Route('/user/add', name: 'add_user')]
    #[Route('/user/edit', name: 'edit_user')]
    public function editUser(EntityManagerInterface $entityManager, Request $request): Response 
    {
        $user = $this->getUser();
        // on crée le formulaire 
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        //quand on sousmet le formulaire 
        if($form->isSubmitted() && $form->isValid()){
        
            $user = $form->getData();
            $entityManager->persist($user);// = prepare
            $entityManager->flush();// execute, on envoie les données dans la db 

            $this->addFlash('success','User updated');

            return $this->redirectToRoute('app_user');

        }

        // vue pour afficher le formulaire 
        return $this->render('user/edit.html.twig', [
           'form' => $form->createView(),
            
        ]);

    }

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


