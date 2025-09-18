<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class TechSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            ['slug' => 'laravel', 'name' => 'Laravel', 'logo_url' => '/images/tech/laravel.svg'],
            ['slug' => 'react',   'name' => 'React',   'logo_url' => '/images/tech/react.svg'],
            ['slug' => 'mysql',   'name' => 'MySQL',   'logo_url' => '/images/tech/mysql.svg'],
            ['slug' => 'tailwind','name' => 'Tailwind','logo_url' => '/images/tech/tailwind.svg'],
        ];

        foreach ($rows as $r) {
            DB::table('techs')->updateOrInsert(
                ['slug' => $r['slug']],
                $r + ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
