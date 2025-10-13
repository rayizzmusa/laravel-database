<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TesRawQuery extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from categories");
    }

    public function testExample(): void
    {
        DB::insert("insert into categories(id, name, description, created_at) values(?,?,?,?)", [
            'GADGET',
            'Gadget',
            'Gadget Category ku',
            '2025-10-10 00:00:00'
        ]);

        $result = DB::select("select * from categories where id = ?", ['GADGET']);

        self::assertEquals(1, count($result));
        self::assertEquals('Gadget', $result[0]->name);
    }

    public function testExamplewithNamedBinding(): void
    {
        DB::insert("insert into categories(id, name, description, created_at) values(:id,:name,:description,:created_at)", [
            'LAPTOP',
            'Laptop',
            'Laptop Category ku',
            '2025-10-11 00:00:00'
        ]);

        $result = DB::select("select * from categories where id = :id", ['id' => 'LAPTOP']);

        self::assertEquals(1, count($result));
        self::assertEquals('Laptop', $result[0]->name);
    }
}
