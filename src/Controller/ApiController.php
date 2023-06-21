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
        $countries = $restCountriesService->getAllCountries();

 

        foreach ($countries as &$country) {
            $country['capital'] = $restCountriesService->getCountryCapital($country['cca3']);
        }

 

        return $this->render('api/index.html.twig', [
            'countries' => $countries,
        ]);
    }
    
}

