<?php

namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    private string $model = Bus::class;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->model->create([
            'name' => 'A New Hope',
        ]);

        $this->model->create([
            'name' => 'The Empire',
        ]);
    }
}
