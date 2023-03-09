<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Station::create([
            'name' => 'Cairo'
        ]);

        Station::create([
            'name' => 'AlFayyum',
            'main_station' => 1,
            'order' => 1
        ]);

        Station::create([
            'name' => 'AlMinya',
            'main_station' => 1,
            'order' => 2
        ]);

        Station::create([
            'name' => 'Asyut',
            'main_station' => 1,
            'order' => 3
        ]);
    }
}
