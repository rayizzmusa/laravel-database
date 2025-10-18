<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class QueryBuilderJoinTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from categories");
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

    public function insertProducts()
    {
        $this->insertCategories();

        DB::table("products")->insert(["id" => "1", "name" => "Sam A17", "price" => 1000000, "category_id" => "HP"]);
        DB::table("products")->insert(["id" => "2", "name" => "Sam A50", "price" => 1500000, "category_id" => "HP"]);

        // $collection = DB::table("products")->where("category_id", "=", "HP")->get();
        // self::assertCount(2, $collection);
        // foreach ($collection as $item) {
        //     Log::info(json_encode($item));
        // }
    }

    public function testJoinQueryBuilder()
    {
        $this->insertProducts();

        $collection = DB::table("products")
            ->join("categories", "products.category_id", "=", "categories.id")
            ->select("products.id", "products.name", "categories.name as category_name", "products.price")
            ->get();

        self::assertCount(2, $collection);
        foreach ($collection as $item) {
            Log::info(json_encode($item));
        }
    }
}
