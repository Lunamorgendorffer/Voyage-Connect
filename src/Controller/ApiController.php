<?php

namespace App\Controller;

use App\Service\CallApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    
    // Route pour la page d'accueil de l'API
    #[Route('/api', name: 'app_api')]
    public function index(CallApiService $restCountriesService): Response
    {
        // Liste des régions pour afficher les liens vers les détails de chaque région
       $regions = [
        "Asia" => "img/b.jpg",
        "Americas" => "img/am.jpg" , 
        "Africa" => "img/a.jpg",
        "Europe"=> "img/home-mountain.jpg" ,
        "Oceania" => "img/home-beach.jpg"];

        return $this->render('api/index.html.twig', [
            'regions' => $regions,
        ]);
    }

    // Route pour afficher les détails des pays d'une région spécifique
    #[Route('/detailRegion', name: 'detailRegion')]
    public function detailRegion(CallApiService $restCountriesService): Response
    {
        // Récupère la région spécifiée depuis les paramètres GET de la requête
        $region = $_GET['region'];

        // Appelle le service pour récupérer la liste des pays de la région spécifiée
        $countries = $restCountriesService->getCountriesByRegion($region);
        
        // Trie les pays par ordre alphabétique en utilisant leur nom commun
        usort($countries, function($a, $b) {
            return strcmp($a['name']['common'], $b['name']['common']);
        });
        
        return $this->render('api/detailRegion.html.twig', [
            'countries' => $countries,
        ]);
    }

    #[Route('/country/{countryCode}', name: 'country_details')]
    public function countryDetails(string $countryCode, CallApiService $restCountriesService, EntityManagerInterface $entityManager): Response
    {
        // Appelle le service pour récupérer les détails du pays en utilisant le code du pays
        $countryDetails = $restCountriesService->getCountryDetails($countryCode);

        // Appelle le service pour récupérer les posts liés au pays en utilisant le nom du pays
        $countryName = $countryDetails[0]['name']['common'];
        $countryPosts = $restCountriesService->getPostsByCountry($countryName);

       
        // $countryPosts = $restCountriesService->getPostsByCountry($entityManager, $countryDetails['name']['common']);
        // dd($countryDetails);

        return $this->render('api/countryDetails.html.twig', [
            'countryDetails' => $countryDetails,
            'countryPosts' => $countryPosts,
        ]);
    }


    // Route pour afficher les détails de tous les pays
    #[Route('/detailCountry', name: 'detailCountry')]
    public function show(CallApiService $restCountriesService): Response
    {
        // Appelle le service pour récupérer la liste de tous les pays
        $countries = $restCountriesService->getAllCountries();

        // Pour chaque pays, appelle le service pour récupérer sa capitale et l'ajoute à ses détails
        foreach ($countries as &$country) {
            $country['capital'] = $restCountriesService->getCountryCapital($country['cca3']);
        }

        return $this->render('api/detail.html.twig', [
            'countries' => $countries,
        ]);
    }
}
