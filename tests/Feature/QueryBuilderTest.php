<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Query\Builder;
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

    public function testInsertWhere(): void
    {
        DB::table('categories')->insert([
            'id' => 'PHONE',
            'name' => 'Samsung',
            'created_at' => '2022-01-01 00:00:00'
        ]);
        DB::table('categories')->insert([
            'id' => 'CARS',
            'name' => 'Hyundai',
            'created_at' => '2022-01-01 00:00:00'
        ]);
        DB::table('categories')->insert([
            'id' => 'MOTO',
            'name' => 'Suzuki',
            'created_at' => '2022-01-01 00:00:00'
        ]);
        DB::table('categories')->insert([
            'id' => 'FOOD',
            'name' => 'Rawon',
            'created_at' => '2022-01-01 00:00:00'
        ]);

        $result = DB::table('categories')->select(['id','name','created_at'])->get();
        self::assertEquals(4, $result->count());
    }

    public function testOrwhere(): void
    {
        $this->testInsertWhere();

        $collection = DB::table('categories')->orWhere(function(Builder $builder){
            $builder->where('id', '=', 'PHONE');
            $builder->orWhere('id', '=', 'MOTO');
        })->get();

        self::assertCount(2, $collection);

        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }
}
