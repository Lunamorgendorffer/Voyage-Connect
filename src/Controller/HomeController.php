<?php

namespace App\Controller;

use App\Service\CallApiService;
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
    public function index(CallApiService $restCountriesService): Response
    {
        // Liste des régions pour afficher les liens vers les détails de chaque région
        $regions = [
        "Asia" => "img/b.jpg",
        "Americas" => "img/am.jpg" , 
        "Africa" => "img/a.jpg",
        "Europe"=> "img/home-mountain.jpg" ,
        "Oceania" => "img/home-beach.jpg"];


        return $this->render('home/index.html.twig', [
            'regions' => $regions,
        ]);
    }
}
