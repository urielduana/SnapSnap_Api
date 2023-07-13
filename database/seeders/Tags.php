<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Tags extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tags')->insert(['tag_name' => 'public', 'color' => "#122416"]);
        DB::table('tags')->insert(['tag_name' => 'private', 'color' => "#191724"]);

        DB::table('tags')->insert(['tag_name' => 'food', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'travel', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'fashion', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'fitness', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'art', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'music', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'photography', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'technology', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'sports', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'movies', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'books', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'health', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'quotes', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'cars', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'beauty', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'business', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'humor', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'education', 'color' => "#191724"]);
        DB::table('tags')->insert(['tag_name' => 'animals', 'color' => "#191724"]);
    }
}
