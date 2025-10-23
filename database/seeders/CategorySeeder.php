<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'id' => 'LAPTOP',
            'name' => 'Acer',
            'description' => 'Laptop Category'
        ]);

        DB::table('categories')->insert([
            'id' => 'HP',
            'name' => 'Samsung',
            'description' => 'HP Category'
        ]);

        DB::table('categories')->insert([
            'id' => 'SMART WATCH',
            'name' => 'Mi',
            'description' => 'SW Category'
        ]);

        DB::table('categories')->insert([
            'id' => 'TAB',
            'name' => 'Ipad',
            'description' => 'TAB Category'
        ]);
    }
}
