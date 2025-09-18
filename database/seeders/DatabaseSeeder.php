<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
            $this->call([
                UserSeeder::class,
                TechSeeder::class,
                PortfolioSeeder::class,
                BlogSeeder::class,
                TestimonialSeeder::class,
                CategorySeeder::class,
            ]);

            // Production: run adminSeeder and basic blog, portfolio.
            // $this->call([ AdminSeeder::class ]);

    }
}
