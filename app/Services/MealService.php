<?php

namespace App\Services;

use App\Models\DTO\DTO;
use App\Models\DTO\Meal;
use stdClass;

class MealService extends ApiService
{
    /**
     * @return Meal[]
     */
    public function all(string $search = ''): array
    {
        $options = [
            'query' => [
                's' => $search,
            ], ];
        // todo: better search
        // todo: pagination
        $response = $this->client->get('search.php', $options);

        $content = $response->getBody()->getContents();

        return $this->mapArray($content);
    }

    /**
     * @return Meal
     */
    public function get(int $id): DTO
    {
        $options = [
            'query' => [
                'i' => $id,
            ], ];

        $response = $this->client->get('lookup.php', $options);

        /** @var Meal $meal */
        $meal = $this->map($response->getBody()->getContents());

        return $meal;
    }

    protected function transform(stdClass $data): Meal
    {
        return new Meal($data->idMeal, $data->strMeal, $data->strArea);
    }
}
