<?php

namespace App\Services;

class BeverageService extends ApiService
{
    public function all(): array
    {
        // todo: simple search
        // todo: pagination
        $response = $this->client->get('beers');

        $models = $response->getBody()->getContents();

        return json_decode($models);
    }

    public function get(int $id)
    {
        $response = $this->client->get("beers/$id");

        $model = $response->getBody()->getContents();

        return json_decode($model);
    }
}
