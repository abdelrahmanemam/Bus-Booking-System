<?php

namespace Database\Seeders;

use App\Models\Trip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    private string $model = Trip::class;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->model->create([
            'name' => 'Cairo - Asyut',
            'start_station' => 1,
            'end_station' => 4,
            'bus_id' => 1,
        ]);
    }
}
