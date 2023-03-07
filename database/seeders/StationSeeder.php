<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    private string $model = Station::class;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->model->create([
            'name' => 'Cairo'
        ]);

        $this->model->create([
            'name' => 'AlFayyum',
            'main_station' => 1,
            'order' => 1
        ]);

        $this->model->create([
            'name' => 'AlMinya',
            'main_station' => 1,
            'order' => 2
        ]);

        $this->model->create([
            'name' => 'Asyut',
            'main_station' => 1,
            'order' => 3
        ]);
    }
}
