<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QueryBuilderTest extends TestCase
{
    protected function setup(): void
    {
        parent::setUp();
        DB::delete('delete from categories');
    }

    public function testInsert(): void
    {
        DB::table('categories')->insert([
            'id' => 'GADGET',
            'name' => 'Samsung'
        ]);
        DB::table('categories')->insert([
            'id' => 'FOOD',
            'name' => 'Coto'
        ]);

        $result = DB::select('select COUNT(id) as total from categories');
        self::assertEquals(2, $result[0]->total);
    }
}
