<?php

namespace App\Models\DTO;

class Beer extends DTO
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var float
     */
    public $abv;

    public function __construct(int $id, string $name, string $description, float $abv)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->abv = $abv;
    }
}
