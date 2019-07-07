<?php

use App\Models\Table;
use Illuminate\Database\Seeder;

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
