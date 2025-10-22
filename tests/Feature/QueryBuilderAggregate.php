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

    public function insertProductsLaptop()
    {
        $this->insertProducts();

        DB::table("products")
            ->insert(["id" => "3", "name" => "Acr i500", "category_id" => "LAPTOP", "price" => "4000000"]);
        DB::table("products")
            ->insert(["id" => "4", "name" => "Acr i600k", "category_id" => "LAPTOP", "price" => "5000000"]);
    }

    public function testQueryBuilderGrouping()
    {
        $this->insertProductsLaptop();

        $collection = DB::table("products")
            ->select("category_id", DB::raw("count(*) as total_product"))
            ->groupBy("category_id")
            ->orderBy("category_id", "desc")
            ->get();

        self::assertCount(2, $collection);
        self::assertEquals("LAPTOP", $collection[0]->category_id);
        self::assertEquals("HP", $collection[1]->category_id);
        self::assertEquals(2, $collection[0]->total_product);
        self::assertEquals(2, $collection[1]->total_product);

        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testQueryBuilderHaving()
    {
        $this->insertProductsLaptop();

        $collection = DB::table("products")
            ->select("category_id", DB::raw("count(*) as total_product"))
            ->groupBy("category_id")
            ->orderBy("category_id", "desc")
            ->having(DB::raw("count(*)"), ">", 2)
            ->get();

        self::assertCount(0, $collection);
    }

    public function testQueryBuilderLocking()
    {
        $this->insertProducts();

        DB::transaction(function () {
            $collection = DB::table("products")
                ->where("id", "=", "1")
                ->lockForUpdate()
                ->get();
            self::assertCount(1, $collection);
        });
    }
}
