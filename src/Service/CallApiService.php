<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getRestData(): array
    {
        return $this->getApi('all');
    }

    public function getAllData(): array
    {
        return $this->getApi('All');
    }

    private function getApi(string $var)
    {
        $response = $this->client->request(
            'GET',
            'https://restcountries.com/v3.1/' . $var
        );

        return $response->toArray();
    }
    
}