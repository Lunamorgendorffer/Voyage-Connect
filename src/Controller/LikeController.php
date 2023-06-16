<?php

namespace App\Controller;

use App\Entity\Post;
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
        $user = $this->getUser();
        
        if ($post->isLikedByUser($user)) {

            $post->removeLike($user);
            $entityManager->flush();

            return $this->json([
                'message' => 'Like has been deleted',
                'nbLike' => $post->howManyLikes()
            ]);
        }

        $post->addLike($user);
        
        $entityManager->flush();
        
        return $this->json([
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
    public function favorite(EntityManagerInterface $entityManager,  Post $post)
    {
        $user = $this->getUser(); // get user in session

        // if the user has like the song the remove the like
        if ($post->isFavoriteByUser($user)) {

            $post->removeUserFavorite($user);
            $entityManager->flush();

            // return a json response
            return $this->json(['message' => 'the favorites has been removed']);
        }
        // else add the like
        $post->addUserFavorite($user);
        // dd($post);
        $entityManager->flush();

        // return a json response
        return $this->json(['message' => 'the favorites has been added']);
        
    }

    

    


}
