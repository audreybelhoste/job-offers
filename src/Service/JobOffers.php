<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JobOffers
{
    private $client;
    private $grantType;
    private $clientId;
    private $clientSecret;
    private $scope;

    public function __construct(HttpClientInterface $client, string $grantType, string $clientId, string $clientSecret, string $scope)
    {
        $this->client = $client;
        $this->grantType = $grantType;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->scope = $scope;
    }

    public function fetchJobOffers(): array
    {
		$token = $this->fetchToken();

        //TODO Manage multiple calls when API max is reach to retrieve all offers
        //TODO Manage calls errors

        $response = $this->client->request(
            'GET',
            'https://api.emploi-store.fr/partenaire/offresdemploi/v2/offres/search', [
				'headers' => [
					'Content-Type' => 'application/x-www-form-urlencoded',
					'Authorization' => 'Bearer ' . $token,
				],
				'query' => [
					'commune' => '35238,33063,75101',
				],
			]
        );

        $content = $response->getContent();
        $content = $response->toArray();

        return $content;
    }

	public function fetchToken(): string
	{
        //TODO Manage calls errors
        //TODO Store token when created and renew when expired

		$response = $this->client->request(
            'POST',
            'https://entreprise.pole-emploi.fr/connexion/oauth2/access_token?realm=%2Fpartenaire', [
				'headers' => [
					'Content-Type' => 'application/x-www-form-urlencoded',
				],
				'body' => [
					'grant_type' => $this->grantType,
					'client_id' => $this->clientId,
					'client_secret' => $this->clientSecret,
					'scope' => $this->scope,
				],
			]
        );

		$token = $response->toArray()['access_token'];

        return $token;
	}
}