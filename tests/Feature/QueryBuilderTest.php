<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public function testSelect(): void
    {
        $this->testInsert();

        $collection = DB::table('categories')->select(['id','name'])->get();
        self::assertEquals(2, $collection->count());
        self::assertNotNull($collection);

        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }
}
