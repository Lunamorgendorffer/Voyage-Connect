<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Service\FileUploader;
use App\Service\CallApiService;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    private $callApiService;

    public function __construct(CallApiService $callApiService)
    {
        $this->callApiService = $callApiService;
    }

    #[Route('/post', name: 'app_post')] // Définir l'URL de la route et le nom de la route
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    { 
        // Récupérer tous les posts depuis la base de données
        $posts = $entityManager->getRepository(Post::class)->findAll();

        // Récupérer les posts les plus populaires pour afficher dans la section des tendances
        $trendingPosts = $entityManager->getRepository(Post::class)->findTrendingPosts(3);

        // Pagination des posts pour n'afficher que 3 posts par page
        $pagination = $paginator->paginate( //C'est une méthode du service $paginator. Elle est utilisée pour paginer les données. 
            $entityManager->getRepository(Post::class)->paginationQuery(), //ici on récupère les données à paginer
            $request->query->get('page', 1),// le numéro de page que nous voulons afficher.
            3 // le nombre d'éléments à afficher par page. 
        );
        
        // Retourne sur la vue 'post/index.html.twig' en lui passant les posts en tant que variable 'posts'
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'trendingPosts' => $trendingPosts,
            'pagination' => $pagination
        ]);
    }
    
    // fonction pour la barre de recherche 
    #[Route("/post/search", name: "search", methods: ["GET"])] 
    public function search(Request $request, PostRepository $postRepository): Response
    {
        $searchTerm = $request->query->get('searchTerm'); // Récupérer le terme de recherche à partir de la requête
        
        $posts = $postRepository->findBySearch($searchTerm); // Utiliser le PostRepository pour trouver les posts correspondant au terme de recherche

        return $this->json($posts); //Renvoyer les posts au format JSON (utile pour une recherche en temps réel)
    }

    // fonction ajout + edit une post
    #[Route('/post/add', name: 'add_post')]
    #[Route('/post/{id}/edit', name: 'edit_post')]
    public function add(EntityManagerInterface $entityManager, Post $post = null, FileUploader $fileUploader, Request $request): Response 
    {
        $user= $this->getUser(); // Récupère l'utilisateur connecté
        $trendingPosts = $entityManager->getRepository(Post::class)->findTrendingPosts(3);
        if (!$post){ // si la post n'existe pas 
            $post = new post();  // alors crée un nouvel objet post 
        }
        // on crée le formulaire 
        $form = $this->createForm(PostType::class, $post, [
            
        ]);

        $form->handleRequest($request); //On gere la soumission du formulaire et la validation des données

        //quand on sousmet le formulaire 
        if($form->isSubmitted() && $form->isValid()){
        
            $image = $form->get('image')->getData(); // Récupère les données de l'image téléchargée dans le formulaire
            
            // Vérifie si une image a été téléchargée
            if ($image) { 
                
                // Si oui, télécharge l'image à l'aide du service FileUploader
                $imageFileName = $fileUploader->upload($image); 
                // Définit le nom du fichier de l'image dans l'entité Post
                $post->setImage($imageFileName);
            }else {
                // Aucun fichier d'image n'a été soumis, conserve l'ancienne valeur
                $post->setImage($post->getImage());
            }
            
            // Définit l'utilisateur connecté comme propriétaire du post
            $post->setUser($user);

             // Définit la date de création du post comme la date et l'heure actuelles
            $post->setCreationDate(new \DateTime());

            $post = $form->getData(); // Récupère les données du formulaire (y compris les modifications de l'image) dans l'entité Post
            $entityManager->persist($post);// Persiste l'entité Post pour qu'elle soit préparée à être enregistrée en base de donnée
            $entityManager->flush();// execute, on envoie les données dans la db 

            return $this->redirectToRoute('app_post');  // Redirige vers la route 'app_post' (index des posts) après l'ajout ou l'édition du post

        }

        // vue pour afficher le formulaire 
        return $this->render('post/addpost.html.twig', [
           'formAddpost' => $form->createView(),
           'edit'=> $post->getId(),
           'trendingPosts' => $trendingPosts,
            
        ]);

    }

    // fonction delete d'une post 
    #[Route('/post/{id}/delete', name: 'delete_post')]
    public function delete(EntityManagerInterface $entityManager, Post $post): Response
    {
        $entityManager->remove($post); // Utilisez l'EntityManager pour supprimer l'entité Post de la base de données
        $entityManager->flush(); // Enregistrez les modifications dans la base de données

        return $this->redirectToRoute('app_post'); // Redirige vers la route 'app_post' (index des posts) après la suppression du post

    }

    // fonction pour afficher la page detail de la post 
    #[Route('/post/{id}', name: 'show_post')] // Définir l'URL de la route et le nom de la route
    public function show(EntityManagerInterface $entityManager, Post $post): Response
    {
        // Récupérer les posts les plus populaires pour afficher dans la section des tendances
        $trendingPosts = $entityManager->getRepository(Post::class)->findTrendingPosts(3);
        // Retourne sur la vue 'post/detailpost.html.twig' avec 
        return $this->render('post/detailpost.html.twig', [
        //les données de l'entité Post en tant que variable 'post'
        'post' => $post, 
        'trendingPosts' => $trendingPosts,
        ]);
    }


    

}

