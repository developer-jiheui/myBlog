<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Find super admin (USER_TYPE = 0); fallback to first user if missing
        $admin  = DB::table('USER')->where('USER_TYPE', 0)->first();
        $userId = $admin?->USER_ID ?? DB::table('USER')->value('USER_ID');

        $posts = [
            [
                'TITLE'     => 'Welcome to my blog',
                'CONTENTS'  => '<p>Seeded post with <strong>HTML</strong> and a small image.</p><p><img src="/images/portfolio/sample1.jpg" alt="sample" style="max-width:100%"></p>',
                'IMAGE_URL' => 'images/portfolio/sample1.jpg',
            ],
            [
                'TITLE'     => 'Tips & Tricks',
                'CONTENTS'  => '<p>Seeders for dev, clean prod, and other notes.</p>',
                'IMAGE_URL' => 'images/portfolio/sample2.jpg',
            ],
            [
                'TITLE'     => 'Quill + Laravel',
                'CONTENTS'  => '<p>Quill editor stores HTML; you can embed small images.</p>',
                'IMAGE_URL' => 'images/portfolio/sample3.jpg',
            ],
        ];

        foreach ($posts as $p) {
            DB::table('BLOG')->insert([
                'TITLE'      => $p['TITLE'],
                'CONTENTS'   => $p['CONTENTS'],
                'USER_ID'    => $userId,
                'CREATED_AT' => now(),
                'UPDATED_AT' => now(),
                'IMAGE_URL'  => $p['IMAGE_URL'],
            ]);
        }
    }
}
