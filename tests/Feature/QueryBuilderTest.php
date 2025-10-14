<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
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
}
