<?php

namespace App\Controller;

use App\Service\CallApiService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    
    #[Route('/api', name: 'app_api')]
    public function index(CallApiService $restCountriesService): Response
    {
  
        $regions = ["europe","asia","americas", "africa", "oceania" ];
 
        return $this->render('api/index.html.twig', [
            'regions' => $regions,
        ]);
    }


    #[Route('/detailRegion', name: 'detailRegion')]
    public function detailRegion(CallApiService $restCountriesService): Response {


       $region = $_GET['region'];

        $countries = $restCountriesService->getCountriesByRegion($region);
        
        return $this->render('api/detailRegion.html.twig', [
            'countries' => $countries,
         
        ]);

    }


    #[Route('/api/detail', name: 'show_api')]
    public function show(CallApiService $restCountriesService): Response
    {
        $countries = $restCountriesService->getAllCountries();

 

        foreach ($countries as &$country) {
            $country['capital'] = $restCountriesService->getCountryCapital($country['cca3']);
        }

 

        return $this->render('api/detail.html.twig', [
            'countries' => $countries,
        ]);
    }
    
}

