<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class JobOffers
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchJobOffers(): array
    {
		$token = $this->fetchToken();

        $response = $this->client->request(
            'GET',
            'https://api.emploi-store.fr/partenaire/offresdemploi/v2/offres/search', [
				'headers' => [
					'Content-Type' => 'application/x-www-form-urlencoded',
					'Authorization' => 'Bearer ' . $token,
				],
				'query' => [
					'commune' => '35238',
				],
			]
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;
    }

	public function fetchToken(): string
	{
		$response = $this->client->request(
            'POST',
            'https://entreprise.pole-emploi.fr/connexion/oauth2/access_token?realm=%2Fpartenaire', [
				'headers' => [
					'Content-Type' => 'application/x-www-form-urlencoded',
				],
				'body' => [
					'grant_type' => 'client_credentials',
					'client_id' => 'PAR_joboffers_260ad00e7b64043f6f5fd105098f3068df3c272e54d2c4f8b0cd194e9516f929',
					'client_secret' => '65c8bd095c062f351c85e953ad637569c08db1d599b6e52fb865aa5134598bbb',
					'scope' => 'application_PAR_joboffers_260ad00e7b64043f6f5fd105098f3068df3c272e54d2c4f8b0cd194e9516f929 api_offresdemploiv2 o2dsoffre',
				],
			]
        );

		$token = $response->toArray()['access_token'];

        return $token;
	}
}