<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('users')->where('user_type', 0)->value('id');

        // Portfolios
        $p1Id = DB::table('portfolios')->insertGetId([
            'user_id'     => $adminId,
            'title'       => 'Personal Portfolio (Laravel + Tailwind)',
            'slug'        => Str::slug('Personal Portfolio Laravel Tailwind'),
            'description' => 'My portfolio site built with Laravel, Tailwind, and a custom CMS.',
            'project_url' => 'https://example.com/portfolio',
            'image_url'   => '/images/portfolio/sample1.jpg',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        $p2Id = DB::table('portfolios')->insertGetId([
            'user_id'     => $adminId,
            'title'       => 'React Dashboard',
            'slug'        => Str::slug('React Dashboard'),
            'description' => 'A responsive admin dashboard built with React.',
            'project_url' => 'https://example.com/react-dashboard',
            'image_url'   => '/images/portfolio/sample2.jpg',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        // link techs (assuming TechSeeder ran and these slugs exist)
        $laravelId  = DB::table('techs')->where('slug', 'laravel')->value('id');
        $tailwindId = DB::table('techs')->where('slug', 'tailwind')->value('id');
        $reactId    = DB::table('techs')->where('slug', 'react')->value('id');

        // Images (cover + gallery)
        DB::table('portfolio_images')->insert([
            // p1 cover
            [
                'portfolio_id' => $p1Id, 'url' => '/images/portfolio/sample1.jpg',
                'alt_text' => 'Portfolio cover', 'position' => 0, 'is_cover' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
            // p1 extra
            [
                'portfolio_id' => $p1Id, 'url' => '/images/portfolio/sample1b.jpg',
                'alt_text' => 'Screenshot', 'position' => 1, 'is_cover' => 0,
                'created_at' => now(), 'updated_at' => now(),
            ],
            // p2 cover
            [
                'portfolio_id' => $p2Id, 'url' => '/images/portfolio/sample2.jpg',
                'alt_text' => 'Dashboard cover', 'position' => 0, 'is_cover' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // Attach techs via pivot
        $techIds = DB::table('techs')->pluck('id', 'slug'); // ['laravel'=>id, ...]
        $attach = function ($portfolioId, array $slugs) use ($techIds) {
            $rows = [];
            $pos  = 0;
            foreach ($slugs as $slug) {
                if (!isset($techIds[$slug])) continue;
                $rows[] = [
                    'portfolio_id' => $portfolioId,
                    'tech_id'      => $techIds[$slug],
                    'level'        => null,
                    'version'      => null,
                    'is_primary'   => $pos === 0 ? 1 : 0,
                    'sort_order'   => $pos++,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }
            if ($rows) DB::table('portfolio_tech')->insert($rows);
        };

        $attach($p1Id, ['laravel', 'tailwind', 'mysql']);
        $attach($p2Id, ['react', 'tailwind']);
    }
}
