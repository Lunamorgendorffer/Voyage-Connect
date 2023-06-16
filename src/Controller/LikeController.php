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

    


}
