<?php

namespace App\Controller;

use App\Service\CallApiService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    
    

    #[Route('/api', name: 'app_api')]
    public function index(CallApiService $callApiService): Response
    {

        $data = $callApiService->getCountries();
        // dd($data);
        foreach ($data as $country){
            $countries[] = $country;
        }
        // dd($countries);
        foreach ($data as $country){
           $country;
        }
        // foreach($data as $cap){
        //     var_dump($cap['capital']);
        // }
        // $data = $callApiService->getRestData();
        // $capital = $data[0]['capital'][0];
        // dd($data[0]['capital']);
        // dd($callApiService->getCountries());

        return $this->render('api/index.html.twig', [
            'data' => $callApiService->getCountries(),
            'countries' => $countries,
            'country' => $country
        ]);
    }
}
