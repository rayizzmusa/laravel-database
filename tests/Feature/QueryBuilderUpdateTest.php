<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class QueryBuilderUpdateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from categories");

        DB::listen(function ($query) {
            Log::channel('test1')->info($query->sql);
        });
    }

    public function insertCategories()
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
    }

    public function testQueryBuilderUpdate()
    {
        $this->insertCategories();

        DB::table("categories")->where("id", "=", "HP")->update([
            'name' => 'iPhone'
        ]);
        DB::table("categories")->where("id", "=", "LAPTOP")->update([
            'name' => 'MSI'
        ]);

        $collection = DB::table("categories")->whereIn("name", ["iPhone", "MSI"])->get();
        self::assertCount(2, $collection);

        $collection->each(function ($item) {
            Log::channel('test1')->info(json_encode($item));
        });
    }
}
