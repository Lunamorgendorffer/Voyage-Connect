<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\SerializerInterface;

class CallApiService
{
    private $httpClient;
    private $serializer;

 
    public function __construct(SerializerInterface $serializer)
    {
        $this->httpClient = HttpClient::create();
        $this->serializer = $serializer;
    }

 

    public function getAllCountries()
    {
        $response = $this->httpClient->request('GET', 'https://restcountries.com/v3.1/all');
        $content = $response->getContent();

 

        return $this->serializer->decode($content, 'json');
    }

 

    public function getCountryCapital(string $countryCode)
    {
        $response = $this->httpClient->request('GET', "https://restcountries.com/v3.1/alpha/{$countryCode}");
        $content = $response->getContent();

 

        $countryData = $this->serializer->decode($content, 'json');
        if (isset($countryData[0]['capital'][0])) {
            return $countryData[0]['capital'][0];
        }

 

        return null;
    }

    public function getCountriesByRegion(string $region)
    {
        $response = $this->httpClient->request('GET', "https://restcountries.com/v3.1/region/{$region}");
        $content = $response->getContent();

        return $this->serializer->decode($content, 'json');
        
    }
    
}