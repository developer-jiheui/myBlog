<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin (USER_TYPE :1)
        $adminId = DB::table('USER')->insertGetId([
            'EMAIL'        => 'admin@example.com',
            'PW'           => Hash::make('password123'),
            'USER_TYPE'    => 0,
            'FIRST_NAME'   => 'Super',
            'LAST_NAME'    => 'Admin',
            'REGISTER_TYPE'=> 0,
            'REGISTER_DT'  => now(),
            'ADDRESS'      => 'Vancouver, BC',
            'PHONE_NUM'    => '+1 (000) 000-0000',
            'BIO'          => 'Site owner',
            'JOB_TITLE'    => 'Full Stack Developer',
            'BIRTHDAY'     => null,
            'INSTAGRAM_URL'=> null,
            'LINKEDIN_URL' => 'https://www.linkedin.com/in/jiheuilee/',
            'GITHUB_URL'   => 'https://github.com/developer-jiheui',
        ]);

        // 3 sample users
        for ($i = 1; $i <= 3; $i++) {
            DB::table('USER')->insert([
                'EMAIL'        => "user{$i}@example.com",
                'PW'           => Hash::make('secret123'),
                'USER_TYPE'    => 1,
                'FIRST_NAME'   => "User{$i}",
                'LAST_NAME'    => 'Example',
                'REGISTER_TYPE'=> 0,
                'REGISTER_DT'  => now(),
                'ADDRESS'      => null,
                'PHONE_NUM'    => null,
                'BIO'          => null,
                'JOB_TITLE'    => null,
                'BIRTHDAY'     => null,
            ]);
        }
    }

}
