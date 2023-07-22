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
        // Création d'un client HTTP pour effectuer des requêtes
        $this->httpClient = HttpClient::create();
        // Injection du service de sérialisation pour décoder les réponses en JSON
        $this->serializer = $serializer;
    }

    // Récupère la liste de tous les pays depuis l'API
    public function getAllCountries()
    {
        // Effectue une requête GET vers l'API pour récupérer tous les pays
        $response = $this->httpClient->request('GET', 'https://restcountries.com/v3.1/all');
        // Récupère le contenu JSON de la réponse
        $content = $response->getContent();

        // Décode le contenu JSON en tableau associatif
        return $this->serializer->decode($content, 'json');
    }

    // Récupère la liste des noms des pays pour les utiliser comme choix dans un formulaire
    public function getCountry()
    {
        // Appelle la méthode getAllCountries pour récupérer tous les pays
        $countries = $this->getAllCountries();
        $countryChoices = [];

        // Parcourt les pays pour récupérer leurs noms communs et les ajouter à la liste des choix
        foreach ($countries as $country) {
            $countryChoices[] = $country['name']['common'];
        }

        return $countryChoices;
    }

    // Récupère la capitale d'un pays en fonction de son code
    public function getCountryCapital(string $countryCode)
    {
        // Effectue une requête GET vers l'API pour récupérer les informations du pays avec le code spécifié
        $response = $this->httpClient->request('GET', "https://restcountries.com/v3.1/alpha/{$countryCode}");
        // Récupère le contenu JSON de la réponse
        $content = $response->getContent();

        // Décode le contenu JSON en tableau associatif
        $countryData = $this->serializer->decode($content, 'json');
        
        // Vérifie si la clé 'capital' est définie dans les données du pays
        if (isset($countryData[0]['capital'][0])) {
            // Si oui, retourne la capitale du pays
            return $countryData[0]['capital'][0];
        }

        // Si la clé 'capital' n'est pas définie ou est vide, retourne null
        return null;
    }

    // Récupère la liste des pays d'une région spécifiée
    public function getCountriesByRegion(string $region)
    {
        // Effectue une requête GET vers l'API pour récupérer les pays de la région spécifiée
        $response = $this->httpClient->request('GET', "https://restcountries.com/v3.1/region/{$region}");
        // Récupère le contenu JSON de la réponse
        $content = $response->getContent();

        // Décode le contenu JSON en tableau associatif
        return $this->serializer->decode($content, 'json');
    }
}
