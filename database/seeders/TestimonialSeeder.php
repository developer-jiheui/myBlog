<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = DB::table('users')->where('email', 'user1@example.com')->first();
        $user2 = DB::table('users')->where('email', 'user2@example.com')->first();

        $rows = [
            [
                'author_user_id'    => $user1?->id,
                'author_name'       => ($user1?->first_name ?? 'User1').' '.($user1?->last_name ?? 'Example'),
                'author_avatar_url' => $user1?->avatar,
                'author_title'      => 'Teammate',
                'body'              => 'Working with Jiheui was smooth—great attention to detail and quality.',
                'status'            => 1,
                'pinned'            => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'author_user_id'    => $user2?->id,
                'author_name'       => ($user2?->first_name ?? 'User2').' '.($user2?->last_name ?? 'Example'),
                'author_avatar_url' => $user2?->avatar,
                'author_title'      => 'Client',
                'body'              => 'Timely delivery and solid communication throughout the project.',
                'status'            => 1,
                'pinned'            => 0,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'author_user_id'    => null, // snapshot-only example
                'author_name'       => 'Anonymous',
                'author_avatar_url' => null,
                'author_title'      => null,
                'body'              => 'Loved the React dashboard project!',
                'status'            => 1,
                'pinned'            => 0,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ];

        DB::table('testimonials')->insert($rows);
    }
}
