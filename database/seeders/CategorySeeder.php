<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            ['slug' => 'web-development', 'name' => 'Web Development'],
            ['slug' => 'mobile-apps', 'name' => 'Mobile Apps'],
            ['slug' => 'data-science', 'name' => 'Data Science'],
            ['slug' => 'ui-ux', 'name' => 'UI/UX Design'],
            ['slug' => 'devops', 'name' => 'DevOps'],
        ];

        foreach ($categories as $cat) {
            DB::table('entity_labels')->insert([
                'target_type' => 'portfolio',
                'target_id'   => 1, // as an example
                'kind'        => 'category',
                'slug'        => $cat['slug'],
                'name'        => $cat['name'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
