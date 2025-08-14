<?php

namespace Tests\Feature;

use Hamcrest\Description;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Laravel\Prompts\table;

class QueryBuilderTest extends TestCase
{
    protected function setup(): void
    {
        parent::setUp();
        DB::delete('delete from categories');
    }

    // insert
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

    // select
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

    // where orWhere
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

    // whereBetween
    public function testWhereBetween(): void
    {
        $this->testInsertWhere();
        
        $collection = DB::table('categories')->whereBetween('created_at', ['2021-01-01 00:00:00','2022-02-01 00:00:00'])->get();

        self::assertCount(4, $collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    // whereIn
    public function testWhereIn(): void
    {
        $this->testInsertWhere();
        
        $collection = DB::table('categories')->whereIn('id', ['MOTO','CARS'])->get();

        self::assertCount(2, $collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }
    

    // whereNull
    public function testWhereNull(): void
    {
        $this->testInsertWhere();

        $collection = DB::table('categories')->whereNull('description')->get();
        self::assertCount(4, $collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    // whereDate
    public function testWhereDate(): void
    {
        $this->testInsertWhere();

        $collection = DB::table('categories')->whereDate('created_at', '2022-01-01 00:00:00')->get();
        self::assertCount(4, $collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    // update()
    public function testUpdate(): void
    {
        $this->testInsertWhere();

        DB::table('categories')->where('id', '=', 'MOTO')->update(['name' => 'Honda']);

        $collection = DB::table('categories')->where('name', '=', 'Honda')->get();
        self::assertCount(1, $collection);

        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    // update or insert
    public function testUpdateOrInsert(): void
    {
        $this->testInsertWhere();
        DB::table('categories')->updateOrInsert([
            'id' => 'DRINK'
        ],
        [
            'name' => 'Coca-Cola',
            'description' => 'coke',
            'created_at' => '2022-01-01 00:00:00',
        ]);

        $collection = DB::table('categories')->where('id', '=', 'DRINK')->get();
        self::assertCount(1, $collection);

        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }
}

