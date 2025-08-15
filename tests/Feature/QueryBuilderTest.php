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
        DB::delete('delete from products');
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
    
    // incremente & decrement
    public function testIncrement(): void
    {
        DB::table('counters')->where('id', '=', 'sample')->increment('counter', 1);
        
        $collection = DB::table('counters')->where('id', '=', 'sample')->get();
        self::assertCount(1, $collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }
    
    // trunket() = hapus tabel dan buat ulang
    // delete()
    public function testDelete(): void
    {
        $this->testInsertWhere();
        
        DB::table('categories')->where('id', '=', 'DRINK')->delete();
        $collection = DB::table('categories')->where('id', '=', 'DRINK')->get();
        
        self::assertCount(0, $collection);

    }

    // insert product
    public function testInsertProducts(): void
    {
        $this->testInsertWhere();

        DB::table('products')->insert([
            'id' => '1',
            'name' => 'Samsung S25U',
            'description' => 'Powerful smartphone',
            'price' => 25000000,
            'category_id' => 'PHONE'
        ]);
        DB::table('products')->insert([
            'id' => '2',
            'name' => 'Iphone 15 Pro Max',
            'description' => 'Apple',
            'price' => 20000000,
            'category_id' => 'PHONE'
        ]);
        
    }
    
    // test join
    public function testJoin(): void
    {
        $this->testInsertProducts();
        
        $collection = DB::table('products')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->select('products.id', 'products.name', 'categories.name as category_name', 'products.price')
        ->get();
        
        self::assertCount(2, $collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
        
    }

    // ordering
    public function testOrder(): void
    {
        $this->testInsertProducts();

        $collection = DB::table('products')
        ->whereNotNull('id')
        ->orderBy('price', 'desc')
        ->orderBy('name', 'asc')
        ->get();

        self::assertCount(2, $collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }
    
    //paging take & skip
    public function testPaging(): void
    {
        $this->testInsertProducts();

        $collection = DB::table('categories')
        ->skip(0)
        ->take(2)
        ->get();
        
        self::assertCount(2, $collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    // data 100 categories
    public function insertManyCategories(): void
    {
        for ($i = 0; $i < 100; $i++){
            DB::table('categories')->insert([
                'id' => "CATEGORY-$i",
                'name' => "Category $i",
                'created_at' => "2022-01-01 00:00:00"
            ]);
        };
    }

    // chunk
    public function testChunk(): void
    {
        $this->insertManyCategories();

        DB::table('categories')
        ->orderBy('id')
        ->chunk(10, function($categories){
            self::assertNotNull($categories);
            Log::info('start chunk');
            foreach ($categories as $category){
                Log::info(json_encode($category));
            }
            Log::info('end chunk');
        });
    }

    //LAZY RESULTS
    public function testLazyResults(): void
    {
        $this->insertManyCategories();

        DB::table('categories')
        ->orderBy('id')
        ->lazy(10)
        ->take(3)
        ->each(function($category){
            self::assertNotNull($category);
            Log::info(json_encode($category));
        });
    }

    // cursor
    public function testCursor(): void
    {
        $this->insertManyCategories();

        DB::table('categories')
        ->orderBy('id')
        ->cursor()
        ->each(function($category){
            self::assertNotNull($category);
            Log::info(json_encode($category));
        });
    }
    
    // query builder aggregate
    public function testAggregate(): void
    {
        $this->testInsertProducts();
        
        //hitung data
        $count = DB::table('products')->count('id');
        self::assertEquals(2, $count);
        
        // harga paling tinggi
        $max = DB::table('products')->max('price');
        self::assertEquals(25000000, $max);
        
        // harga paling rendah
        $min = DB::table('products')->min('price');
        self::assertEquals(20000000, $min);
        
        // harga rata rata
        $avg = DB::table('products')->avg('price');
        self::assertEquals(22500000, $avg);
        
        // total harga 
        $sum = DB::table('products')->sum('price');
        self::assertEquals(45000000, $sum);
    }

    // query builder raw
    public function testQueryBuilderRaw(): void
    {
        $this->testInsertProducts();

        $collection = DB::table('products')
        ->select(
            DB::raw('count(id) as total_product'),
            DB::raw('min(price) as min_product'),
            DB::raw('max(price) as max_product')
        )->get();

        self::assertEquals(2, $collection[0]->total_product);
        self::assertEquals(20000000, $collection[0]->min_product);
        self::assertEquals(25000000, $collection[0]->max_product);
    }

    public function testInsertProductsFood(): void
    {
        
        $this->testInsertProducts();
        DB::table('products')->insert([
            'id' => '3',
            'name' => 'Coto Betawi',
            'description' => 'Soto dari Betawi',
            'price' => 15000,
            'category_id' => 'FOOD'
        ]);
        DB::table('products')->insert([
            'id' => '4',
            'name' => 'Gultik',
            'description' => 'Gulai Tikungan',
            'price' => 20000,
            'category_id' => 'FOOD'
        ]);
    }

    // groupping
    public function testGroupBy(): void
    {
        $this->testInsertProductsFood();

        $collection = DB::table('products')
        ->select('category_id', DB::raw('count(*) as total_product'))
        ->groupBy('category_id')
        ->orderBy('category_id', 'desc')
        ->get();

        self::assertCount(2, $collection);
        self::assertEquals('PHONE', $collection[0]->category_id);
        self::assertEquals('FOOD', $collection[1]->category_id);
        self::assertEquals('2', $collection[0]->total_product);
        self::assertEquals('2', $collection[1]->total_product);
    }
}

