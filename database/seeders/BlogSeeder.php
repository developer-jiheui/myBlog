<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('users')->where('user_type', 0)->value('id');

        $b1Id = DB::table('blogs')->insertGetId([
            'user_id'    => $adminId,
            'title'      => 'Welcome to my blog',
            'slug'       => Str::slug('Welcome to my blog'),
            'contents'   => '<p>Seeded post with <strong>HTML</strong> content and a small image.</p><p><img src="/images/blog/sample-blog-1.jpg" alt="sample" style="max-width:100%"></p>',
            'image_url'  => '/images/blog/sample-blog-1.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $b2Id = DB::table('blogs')->insertGetId([
            'user_id'    => $adminId,
            'title'      => 'Building with Laravel 10',
            'slug'       => Str::slug('Building with Laravel 10'),
            'contents'   => '<p>Notes on upgrading and structuring a Laravel app.</p>',
            'image_url'  => '/images/blog/sample-blog-2.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('blog_images')->insert([
            [
                'blog_id' => $b1Id, 'url' => '/images/blog/sample-blog-1.jpg',
                'alt_text' => 'cover', 'position' => 0, 'is_cover' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'blog_id' => $b2Id, 'url' => '/images/blog/sample-blog-2.jpg',
                'alt_text' => 'cover', 'position' => 0, 'is_cover' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}
