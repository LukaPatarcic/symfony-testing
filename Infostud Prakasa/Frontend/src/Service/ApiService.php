<?php


namespace App\Service;


use GuzzleHttp\Client;

class ApiService
{
    private $client;

    public function __construct(string $url, string $key = null)
    {

        $this->client = new Client([
            'base_uri' =>  $url
        ]);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}