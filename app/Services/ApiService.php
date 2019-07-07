<?php

namespace App\Services;

use App\Models\DTO\DTO;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use stdClass;

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
        return array_map([$this, 'transform'], $this->unpack($content));
    }

    protected function map(string $content): DTO
    {
        $model = $this->unpack($content)[0];

        return $this->transform($model);
    }

    protected function unpack($content): array
    {
        $content = json_decode($content);

        if (isset($content->meals)) {
            $content = $content->meals;
        }

        return Arr::wrap($content);
    }

    /**
     * @return DTO
     */
    abstract protected function transform(stdClass $data);

    abstract public function all(string $search = ''): array ;

    abstract public function get(int $id): DTO;
}
