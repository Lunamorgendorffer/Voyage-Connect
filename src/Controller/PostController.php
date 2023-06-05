<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $posts = $entityManager->getRepository(Post::class)->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    // fonction ajout + edit une post
    #[Route('/post/add', name: 'add_post')]
    #[Route('/post/{id}/edit', name: 'edit_post')]
    public function add(EntityManagerInterface $entityManager, Post $post = null, Request $request): Response 
    {
        if (!$post){ // si la post n'existe pas 
            $post = new post();  // alors crée un nouvel objet post 
        }
        // on crée le formulaire 
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        //quand on sousmet le formulaire 
        if($form->isSubmitted() && $form->isValid()){
            $images = $form->get('image')->getData();

            $post = $form->getData();
            $entityManager->persist($post);// = prepare
            $entityManager->flush();// execute, on envoie les données dans la db 

            return $this->redirectToRoute('app_post');

        }

        // vue pour afficher le formulaire 
        return $this->render('post/addpost.html.twig', [
           'formAddpost' => $form->createView(),
           'edit'=> $post->getId(),
            
        ]);

    }

    // fonction delete d'une post 
    #[Route('/post/{id}/delete', name: 'delete_post')]
    public function delete(EntityManagerInterface $entityManager, Post $post): Response
    {
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute('app_post');

    }

    // fonction pour afficher la page detail de la post 
    #[Route('/post/{id}', name: 'show_post')]
    public function show(Post $post): Response
    {
       

        // Retourne sur la vue 'post/detailpost.html.twig' avec les données suivantes
        return $this->render('post/detailpost.html.twig', [
        'post' => $post,             // La post à afficher
        ]);
    }


    

}
