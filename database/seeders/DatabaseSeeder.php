<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'first_name' => 'Mujtaba',
            'last_name'  => 'Ali',
            'email'      => 'test@example.com',
            'number'     => '03001234567',
        ]);

        $this->call([
            VehicleCategorySeeder::class,
        ]);
    }
}
