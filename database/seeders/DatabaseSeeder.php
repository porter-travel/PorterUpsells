<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Product;
use App\Models\User;
use App\Models\Variation;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'alex@gluestudio.co.uk',
            'password' => 'password',
        ]);


        // integration id 82cb4c8c-fd81-11ee-baef-02a3e6f84031
        Hotel::factory(1)
            ->has(Product::factory()
                ->has(Variation::factory()->count(3))
                ->count(3))
            ->create(['id_for_integration' => "82cb4c8c-fd81-11ee-baef-02a3e6f84031"]);

        //Booking::factory(5)->create();

    }
}
