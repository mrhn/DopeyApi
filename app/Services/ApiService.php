<?php

namespace App\Services;

use GuzzleHttp\Client;

class ApiService
{
    /** @var Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
