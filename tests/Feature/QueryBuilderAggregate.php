<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class QueryBuilderAggregate extends TestCase
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

    public function testQueryBuilderAggregate()
    {
        $this->insertProducts();

        $collection = DB::table("products")->count("id");
        self::assertEquals(2, $collection);

        $collection = DB::table("products")->max("price");
        self::assertEquals(1500000, $collection);

        $collection = DB::table("products")->min("price");
        self::assertEquals(1000000, $collection);

        $collection = DB::table("products")->avg("price");
        self::assertEquals(1250000, $collection);

        $collection = DB::table("products")->sum("price");
        self::assertEquals(2500000, $collection);
    }

    public function testQueryBuilderRawAggregate()
    {
        $this->insertProducts();

        $collection = DB::table("products")
            ->select(
                DB::raw("count(*) as total_products"),
                DB::raw("min(price) as min_price"),
                DB::raw("max(price) as max_price")
            )->get();

        self::assertEquals(2, $collection[0]->total_products);
        self::assertEquals(1000000, $collection[0]->min_price);
        self::assertEquals(1500000, $collection[0]->max_price);

        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }
}
