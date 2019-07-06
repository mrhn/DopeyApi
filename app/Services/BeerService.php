<?php

namespace App\Services;

use App\Models\DTO\Beer;

class BeerService extends ApiService
{
    /**
     * @return Beer[]
     */
    public function all(string $search = ''): array
    {
        $options = [];

        if ($search) {
            $options['query'] = [
                'beer_name' => $search,
            ];
        }
        // todo: better search
        // todo: pagination
        $response = $this->client->get('beers', $options);

        $content = $response->getBody()->getContents();

        return $this->mapArray($content);
    }

    public function get(int $id): Beer
    {
        $response = $this->client->get("beers/{$id}");

        /** @var Beer $beer */
        $beer = $this->map($response->getBody()->getContents());

        return $beer;
    }

    protected function transform(array $data): Beer
    {
        return new Beer($data['id'], $data['name'], $data['description'], $data['abv']);
    }
}
