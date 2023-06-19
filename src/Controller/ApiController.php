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
        $data = $callApiService->getRestData();
        // dd($data[0]['capital']);

        return $this->render('api/index.html.twig', [
            'data' => $callApiService->getRestData()
        ]);
    }
}
