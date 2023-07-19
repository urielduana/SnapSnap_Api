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
        DB::table('tags')->insert(['tag_name' => 'public', 'color' => "#FF0000"]);
        DB::table('tags')->insert(['tag_name' => 'private', 'color' => "#00FF00"]);

        DB::table('tags')->insert(['tag_name' => 'food', 'color' => "#FFA500"]);
        DB::table('tags')->insert(['tag_name' => 'travel', 'color' => "#008080"]);
        DB::table('tags')->insert(['tag_name' => 'fashion', 'color' => "#800080"]);
        DB::table('tags')->insert(['tag_name' => 'fitness', 'color' => "#FF00FF"]);
        DB::table('tags')->insert(['tag_name' => 'art', 'color' => "#800000"]);
        DB::table('tags')->insert(['tag_name' => 'music', 'color' => "#000080"]);
        DB::table('tags')->insert(['tag_name' => 'photography', 'color' => "#00FFFF"]);
        DB::table('tags')->insert(['tag_name' => 'technology', 'color' => "#008000"]);
        DB::table('tags')->insert(['tag_name' => 'sports', 'color' => "#FFD700"]);
        DB::table('tags')->insert(['tag_name' => 'movies', 'color' => "#C0C0C0"]);
        DB::table('tags')->insert(['tag_name' => 'books', 'color' => "#0000FF"]);
        DB::table('tags')->insert(['tag_name' => 'health', 'color' => "#FFC0CB"]);
        DB::table('tags')->insert(['tag_name' => 'quotes', 'color' => "#800000"]);
        DB::table('tags')->insert(['tag_name' => 'cars', 'color' => "#FFA500"]);
        DB::table('tags')->insert(['tag_name' => 'beauty', 'color' => "#FF1493"]);
        DB::table('tags')->insert(['tag_name' => 'business', 'color' => "#800080"]);
        DB::table('tags')->insert(['tag_name' => 'humor', 'color' => "#FFFF00"]);
        DB::table('tags')->insert(['tag_name' => 'education', 'color' => "#008000"]);
        DB::table('tags')->insert(['tag_name' => 'animals', 'color' => "#808080"]);
    }
}
