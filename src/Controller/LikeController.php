<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LikeController extends AbstractController
{
    #[Route('/like/post/{id}', name: 'app_like')]
    public function index(EntityManagerInterface $entityManager, Post $post): Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
        
        if ($post->isLikedByUser($user)) { // Vérifier si l'utilisateur a déjà liké le post
            
            $post->removeLike($user); // Si oui, supprimer le like
            $entityManager->flush();// execute, on envoie les données dans la db 

            // Retourner une réponse JSON indiquant que le like a été supprimé, avec le nombre total de likes mis à jour
            return $this->json([
                'message' => 'Like has been deleted',
                'nbLike' => $post->howManyLikes()
            ]);
        }
        // Si l'utilisateur n'a pas liké le post, ajouter le like
        $post->addLike($user);
        // execute, on envoie les données dans la db 
        $entityManager->flush(); 
        
        return $this->json([// Retourner une réponse JSON indiquant que le like a été ajouté, avec le nombre total de likes mis à jour
            'message' => 'Like has been added',
            'nbLike' => $post->howManyLikes()
        ]);
    }

    #[Route('/like/comment/{id}', name: 'comment_like')]
    public function commentLike(EntityManagerInterface $entityManager, Comment $comment): Response
    {
        $user = $this->getUser();
        
        if ($comment->isLikedByUser($user)) {

            $comment->removeUsersLike($user);
            $entityManager->flush();

            return $this->json([
                'message' => 'Like has been deleted',
                'nbLike' => $comment->howManyLikes()
            ]);
        }

        $post->addUsersLike($user);
        
        $entityManager->flush();
        
        return $this->json([
            'message' => 'Like has been added',
            'nbLike' => $post->howManyLikes()
        ]);
    }

    #[Route('/favorite/post/{id}', name: 'favorite_post')]
    public function favoritePost(EntityManagerInterface $entityManager,  Post $post)
    {
        $user = $this->getUser(); // get user in session

        // if the user has like the song the remove the like
        if ($user->isFavoriteByUser($post)) {

            $user->removefavoritePost($post);
            $entityManager->flush();

            // return a json response
            return $this->json(['message' => 'the favorites has been removed']);
        }
        // else add the like
        $user->addfavoritePost($post);
        $entityManager->persist($user);
        // dd($post);
        $entityManager->flush();

        // return a json response
        return $this->json(['message' => 'the favorites has been added']);
        
    }

    

    


}
