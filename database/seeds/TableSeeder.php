<?php

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        factory(Table::class, 5)->create();
    }
}
