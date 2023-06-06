<?php

namespace App\Controller;


use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    #[Route('/comment', name: 'app_comment')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $comments = $entityManager->getRepository(Comment::class)->findAll();

        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    // fonction ajout + edit une comment
    #[Route('/comment/add/{postId}', name: 'add_comment')]
    #[Route('/comment/{id}/edit', name: 'edit_comment')]
    public function add(EntityManagerInterface $entityManager, Comment $comment = null, $postId = null, Request $request): Response 
    {
        
        $user= $this->getUser(); 
        // dd($this->getUser());
        if (!$comment){ // si la comment n'existe pas 
            $comment = new comment();  // alors crée un nouvel objet comment 
        }
        
        $post=$entityManager->getRepository(Post::class)->find($postId);
        
        // on crée le formulaire 
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        
        //quand on sousmet le formulaire 
        if($form->isSubmitted() && $form->isValid()){
            $comment->setUser($user);
            $comment->setPost($post);
            $comment->setCreationDate(new \DateTime());
            $comment = $form->getData();
            $entityManager->persist($comment);// = prepare
            $entityManager->flush();// execute, on envoie les données dans la db 

            return $this->redirectToRoute('show_post', ['id'=> $postId]);

        }

        // vue pour afficher le formulaire 
        return $this->render('comment/addcomment.html.twig', [
           'formAddcomment' => $form->createView(),
           'edit'=> $comment->getId(),
            
        ]);

    }

    // fonction delete d'une comment 
    #[Route('/comment/{id}/delete', name: 'delete_comment')]
    public function delete(EntityManagerInterface $entityManager, Comment $comment): Response
    {
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_comment');

    }

    // fonction pour afficher la page detail de la comment 
    #[Route('/comment/{id}', name: 'show_comment')]
    public function show(Comment $comment): Response
    {
       

        // Retourne sur la vue 'comment/detailcomment.html.twig' avec les données suivantes
        return $this->render('comment/detailcomment.html.twig', [
        'comment' => $comment,             // La comment à afficher
        ]);
    }
}
