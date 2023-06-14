<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LikeController extends AbstractController
{
    #[Route('/like', name: 'app_like')]
    public function index(EntityManagerInterface $entityManager, Post $post = null): Response
    {
        $user = $this->getUser();
        
        if ($post->isLikeByUser($user)) {

            $post->removeLike($user);
            $$entityManager->flush();
        }

        $post->addLike($user);
        $entityManager->flush();
    }

    


}
