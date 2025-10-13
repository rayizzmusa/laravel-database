<?php

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from categories");
    }

    public function testDbTransactionSucces()
    {
        DB::transaction(function () {
            DB::insert("insert into categories(id, name, description, created_at) values(:id,:name,:description,:created_at)", [
                'LAPTOP',
                'Laptop',
                'Laptop Category ku',
                '2025-10-11 00:00:00'
            ]);

            DB::insert("insert into categories(id, name, description, created_at) values(:id,:name,:description,:created_at)", [
                'HP',
                'Samsung',
                'HP Samsung',
                '2025-10-11 00:00:00'
            ]);
        });

        $result = DB::select("select * from categories");

        self::assertEquals(2, count($result));
    }

    public function testDbTransactionFail()
    {
        try {
            DB::transaction(function () {
                DB::insert("insert into categories(id, name, description, created_at) values(:id,:name,:description,:created_at)", [
                    'LAPTOP',
                    'Laptop',
                    'Laptop Category ku',
                    '2025-10-11 00:00:00'
                ]);

                DB::insert("insert into categories(id, name, descriptions, created_at) values(:id,:name,:description,:created_at)", [
                    'HP',
                    'Samsung',
                    'HP Samsung',
                    '2025-10-11 00:00:00'
                ]);
            });
        } catch (QueryException $th) {
            //throw $th;
        }

        $result = DB::select("select * from categories");

        self::assertEquals(0, count($result));
    }
}
