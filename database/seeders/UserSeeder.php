<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin (user_type = 0)
        DB::table('users')->insertGetId([
            'email'      => 'developer.jiheuilee@gmail.com',
            'password'   => Hash::make('password123'),
            'user_type'  => 0,
            'first_name' => 'Jiheui',
            'last_name'  => 'Lee',
            'bio'        => "Hi I'm a full-stack developer who loves to build stuff",
            'job_title'  => 'Full Stack Developer',
            'address'    => 'Vancouver, BC',
            'phone_num'  => '+1 (000) 000-0000',
            'linkedin_url' => 'https://www.linkedin.com/in/jiheuilee/',
            'github_url'   => 'https://github.com/developer-jiheui',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // Sample regular users (user_type = 1)
        foreach ([1,2,3] as $i) {
            DB::table('users')->insert([
                'email'      => "user{$i}@example.com",
                'password'   => Hash::make('secret123'),
                'user_type'  => 1,
                'first_name' => "User{$i}",
                'last_name'  => 'Example',
                'register_type' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
