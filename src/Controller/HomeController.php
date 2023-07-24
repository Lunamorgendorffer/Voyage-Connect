<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\CallApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $callApiService;

    public function __construct(CallApiService $callApiService)
    {
        $this->callApiService = $callApiService;
    }
    
    #[Route('/', name: 'app_home')]
    public function index(CallApiService $restCountriesService, EntityManagerInterface $entityManager): Response
    {
        // Liste des régions pour afficher les liens vers les détails de chaque région
        $regions = [
        "Asia" => "img/b.jpg",
        "Americas" => "img/am.jpg" , 
        "Africa" => "img/a.jpg",
        "Europe"=> "img/home-mountain.jpg" ,
        "Oceania" => "img/home-beach.jpg"];

        $posts = $entityManager->getRepository(Post::class)->findAll();

        $trendingPosts = $entityManager->getRepository(Post::class)->findTrendingPosts(3);


        return $this->render('home/index.html.twig', [
            'regions' => $regions,
            'posts' => $posts,
            'trendingPosts' => $trendingPosts,
        ]);
    }
}
