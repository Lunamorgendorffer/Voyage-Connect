<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileType;
use App\Service\FileUploader;
use App\Form\EditPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        // Utilisez le User Repository pour récupérer les utilisateurs correspondants à la requête
        $users = $userRepository->findUsersWithLikesCount(3);
        
        return $this->render('user/index.html.twig',[
            'users' => $users
        ]);
    }

    // fonction ajout + edit une post
    #[Route('/user/edit', name: 'edit_user')]
    public function editUser(EntityManagerInterface $entityManager, FileUploader $fileUploader, Request $request): Response 
    {
        $user = $this->getUser();
        // on crée le formulaire 
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        //quand on sousmet le formulaire 
        if($form->isSubmitted() && $form->isValid()){
            
            $image = $form->get('avatar')->getData(); 
            if ($image) { 
                $imageFileName = $fileUploader->upload($image); 
                $user->setAvatar($imageFileName); 
            }

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

    // fonction ajout + edit une post
    #[Route('/user/editPassword/{id}', name: 'edit_password', methods:['GET', 'POST'])]
    public function editPassword(User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher,Request $request): Response 
    {
        $form = $this->createForm(EditPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($hasher->isPasswordValid($user, $form->getData()['plainPassword'])) {
                $user->setUpdatedAt(new \DateTimeImmutable());
                $user->setPassword(
                    $hasher->hashPassword($user,
                    $form->getData()['newPassword']
                    )
                );

                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifié.'
                );

                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('app_user');
            }else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect.'
                );
            }

        }

        // vue pour afficher le formulaire 
        return $this->render('user/editPassword.html.twig', [
           'form' => $form->createView(),
            
        ]);

    }

    // fonction delete d'une user 
    #[Route('/user/{id}/delete', name: 'delete_user')]
    public function delete(EntityManagerInterface $entityManager, User $user): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_post');

    }




    // fonction pour afficher la page detail de la user 
    #[Route('/user/{id}', name: 'show_user')]
    public function show(User $user, UserRepository $userRepository): Response {
      
        // Retourne sur la vue 'user/detailuser.html.twig' avec les données suivantes
        return $this->render('user/detailuser.html.twig', [
        'user' => $user,  
        'like' =>  $userRepository->findUsersWithLikesCount(3)          
        ]);
    }


    

}


