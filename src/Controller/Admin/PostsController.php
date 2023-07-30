<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Service\FileUploader;
use App\Repository\PostRepository;
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

    // Cette fonction permet de bloquer/débloquer un post
    #[Route('/post/lock', name: 'lock', methods: ['POST'])]
    public function lockPost(EntityManagerInterface $em, Request $request, PostRepository $postRepository): Response
    {
        // Vérifier si la requête est de type POST
        if ($request->isMethod('POST')) {
            // Récupérer l'ID du post à bloquer/débloquer depuis la requête
            $postId = $request->request->get('postId');
            // Trouver le post correspondant dans la base de données
            $post = $postRepository->find($postId);

            // Si le post existe
            if ($post) {
                // Vérifier si le post doit être bloqué ou débloqué
                $isLock = $request->request->has('isLock');
                 // Définir l'état de verrouillage du post
                $post->setLocked($isLock);
                // Enregistrer les changements dans la base de données
                $em->flush();
            }
            // Rediriger vers la page d'index des posts de l'administration
            return $this->redirectToRoute('admin_posts_index');
        }
    }


}