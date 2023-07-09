<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/posts', name: 'admin_posts_')]
class PostsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $posts=$entityManager->getRepository(Post::class)->findAll();
        
        return $this->render('admin/posts/index.html.twig', compact('posts'));
    }

    

    #[Route('/post/{id}/edit', name: 'edit_post')]
    public function edit(EntityManagerInterface $entityManager, Post $post = null, FileUploader $fileUploader, Request $request): Response 
    {
        
        // on crée le formulaire 
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        //quand on sousmet le formulaire 
        if($form->isSubmitted() && $form->isValid()){
           
            $image = $form->get('image')->getData(); 
            if ($image) { 
                $imageFileName = $fileUploader->upload($image); 
                $post->setImage($imageFileName);
            }else {
                // Aucun fichier d'image n'a été soumis, conserve l'ancienne valeur
                $post->setImage($post->getImage());
            }
            
            // $post->setUser($user);
            $post->setCreationDate(new \DateTime());

            $post = $form->getData();
            $entityManager->persist($post);// = prepare
            $entityManager->flush();// execute, on envoie les données dans la db 

            return $this->redirectToRoute('admin_posts_index');

        }

        // vue pour afficher le formulaire 
        return $this->render('post/addpost.html.twig', [
           'formAddpost' => $form->createView(),
           'edit'=> $post->getId(),
            
        ]);

    }
}