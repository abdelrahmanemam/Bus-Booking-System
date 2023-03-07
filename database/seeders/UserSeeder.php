<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private string $model = User::class;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->model->create([
            'name' => 'user',
            'main_station' => 'user@test.com',
            'password' => bcrypt('123456')
        ]);
    }
}
