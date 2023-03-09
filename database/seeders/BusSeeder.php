<?php

namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bus::create([
            'name' => 'A New Hope',
        ]);

        Bus::create([
            'name' => 'The Empire',
        ]);
    }
}
