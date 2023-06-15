<?php

namespace App\Controller;

use App\Entity\Post;
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
            $$entityManager->flush();

            return $this->json(['message' => 'Like has been deleted']);
        }

        $post->addLike($user);
        
        $entityManager->flush();
        
        return $this->json(['message' => 'Like has been added']);
    }

    


}
