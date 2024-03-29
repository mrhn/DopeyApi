<?php

namespace App\Services;

use App\Models\DTO\Beer;
use App\Models\DTO\DTO;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use stdClass;

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

    /**
     * @return Beer
     */
    public function get(int $id): DTO
    {
        try {
            $response = $this->client->get("beers/{$id}");
        } catch (BadResponseException $exception) {
            // fairly optimistic
            throw (new ModelNotFoundException())->setModel(Beer::class, [$id]);
        }

        /** @var Beer $beer */
        $beer = $this->map($response->getBody()->getContents());

        return $beer;
    }

    protected function transform(stdClass $data): Beer
    {
        return new Beer($data->id, $data->name, $data->description, $data->abv);
    }
}
