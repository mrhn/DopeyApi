<?php

namespace App\Services;

use App\Models\DTO\DTO;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;

abstract class ApiService
{
    /** @var Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function mapArray(string $content): array
    {
        return array_map([$this, 'transform'], json_decode($content, true));
    }

    protected function map(string $content): DTO
    {
        $model = json_decode($content, true);

        // punk api returns find requests as arrays
        if (\is_array(json_decode($content, true))) {
            $model = Arr::first($model);
        }

        return $this->transform($model);
    }

    /**
     * @return DTO
     */
    abstract protected function transform(array $data);
}
