<?php

namespace Tests\Feature;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class QueryBuilderTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from categories");
    }
    public function testQueryBuilder()
    {
        DB::table('categories')->insert([
            'id' => 'LAPTOP',
            'name' => 'Laptop',
            'description' => 'Laptop Category'
        ]);

        DB::table('categories')->insert([
            'id' => 'HP',
            'name' => 'Hewlett Packard',
            'description' => 'HP Category'
        ]);

        $result = DB::select("select count(id) as total from categories");
        self::assertEquals(2, $result[0]->total);
    }

    public function testQueryBulderSelect()
    {

        $this->testQueryBuilder();

        $collection = DB::table("categories")->select(['id', 'name'])->get();
        self::assertNotNull($collection);

        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function insertCategories()
    {
        DB::table('categories')->insert([
            'id' => 'LAPTOP',
            'name' => 'Laptop',
            'description' => 'Laptop Category'
        ]);

        DB::table('categories')->insert([
            'id' => 'HP',
            'name' => 'Hewlett Packard',
            'description' => 'HP Category'
        ]);

        DB::table('categories')->insert([
            'id' => 'ACER',
            'name' => 'Acer',
            'description' => 'Laptop Category'
        ]);

        DB::table('categories')->insert([
            'id' => 'SAMSUNG',
            'name' => 'Samsung',
            'description' => 'HP Category'
        ]);
    }

    public function testQueryBuilderWhere()
    {
        $this->insertCategories();

        $collection = DB::table("categories")->where(function (Builder $builder) {
            $builder->where('id', '=', 'LAPTOP');
            $builder->orWhere('id', '=', 'HP');
        })->get(['name', 'description']);

        self::assertCount(2, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testWhereBetween()
    {
        $this->insertCategories();

        $collection = DB::table("categories")
            ->whereBetween('created_at', ['2025-10-17 00:00:00', '2025-10-17 23:59:59'])
            ->get();
        self::assertCount(4, $collection);
        for ($i = 0; $i  < count($collection); $i++) {
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testQueryBuilderWhereIn()
    {
        $this->insertCategories();

        $collection = DB::table("categories")->whereIn('id', ['ACER', 'HP'])->get();

        self::assertCount(2, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testQueryBuilderWhereNull()
    {
        $this->insertCategories();

        $collection = DB::table("categories")->whereNotNull('description')->get();

        self::assertCount(4, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testQueryBuilderWhereDateMethod()
    {
        $this->insertCategories();

        $collection1 = DB::table("categories")->whereDate('created_at', '2025-10-17')->get();
        $collection2 = DB::table("categories")->whereDay('created_at', '17')->get();
        $collection3 = DB::table("categories")->whereMonth('created_at', '10')->get();

        self::assertCount(4, $collection1);
        self::assertCount(4, $collection2);
        self::assertCount(4, $collection3);
    }
}
