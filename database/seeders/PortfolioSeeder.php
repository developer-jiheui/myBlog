<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        // Find super admin (USER_TYPE = 0); fallback to first user if missing
        $admin = DB::table('USER')->where('USER_TYPE', 0)->first();
        $userId = $admin?->USER_ID ?? DB::table('USER')->value('USER_ID');

        $items = [
            [
                'TITLE'       => 'Personal Portfolio Site',
                'DESCRIPTION' => 'Laravel-based site with custom Blade theme and Google Places autocomplete.',
                'CATEGORY'    => 'Web',
                'PROJECT_URL' => 'https://example.com/portfolio',
                'IMAGE_URL'   => 'images/portfolio/sample1.jpg',
            ],
            [
                'TITLE'       => 'Blog Engine',
                'DESCRIPTION' => 'Quill-powered blog editor, base64-to-HTML content with thumbnail extraction.',
                'CATEGORY'    => 'Web',
                'PROJECT_URL' => 'https://example.com/blog',
                'IMAGE_URL'   => 'images/portfolio/sample2.jpg',
            ],
            [
                'TITLE'       => 'API Microservice',
                'DESCRIPTION' => 'Small REST demo with tests.',
                'CATEGORY'    => 'Backend',
                'PROJECT_URL' => 'https://example.com/api',
                'IMAGE_URL'   => 'images/portfolio/sample3.jpg',
            ],
        ];

        foreach ($items as $it) {
            DB::table('PORTFOLIO')->insert([
                'USER_ID'    => $userId,
                'TITLE'      => $it['TITLE'],
                'DESCRIPTION'=> $it['DESCRIPTION'],
                'CATEGORY'   => $it['CATEGORY'],
                'PROJECT_URL'=> $it['PROJECT_URL'],
                'CREATED_AT' => now(),
                'UPDATED_AT' => now(),
                'IMAGE_URL'  => $it['IMAGE_URL'],
                'LIKE_COUNT' => 0,
            ]);
        }
    }
}
