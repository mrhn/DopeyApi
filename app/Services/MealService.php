<?php

namespace App\Services;

use App\Models\DTO\Beer;
use stdClass;
use App\Models\DTO\Meal;

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
            ]];
        // todo: better search
        // todo: pagination
        $response = $this->client->get('search.php', $options);

        $content = $response->getBody()->getContents();

        return $this->mapArray($content);
    }

    public function get(int $id): Meal
    {
        $options = [
            'query' => [
                'i' => $id,
            ]];

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
