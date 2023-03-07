<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    private string $model = Admin::class;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->model->create([
            'name' => 'admin',
            'main_station' => 'admin@test.com',
            'password' => bcrypt('123456')
        ]);
    }
}
