<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertEquals;

class TransactionTest extends TestCase
{
    protected function setup(): void
    {
        parent::setUp();
        DB::delete('delet from categories');
    }

    // skenario sukses
    public function testTransaction()
    {
        DB::transaction(function(){
            DB::insert('INSERT INTO categories(id, name, description, created_at) VALUES(:id, :name, :description, :created_at)', [
                'id' => 'GADGET',
                'name' => 'Hp',
                'description' => 'Flagship',
                'created_at' => '2022-01-01 00:00:00'
            ]);
            DB::insert('INSERT INTO categories(id, name, description, created_at) VALUES(:id, :name, :description, :created_at)', [
                'id' => 'FOOD',
                'name' => 'Rawon',
                'description' => 'Soto Butek',
                'created_at' => '2022-01-01 00:00:00'
            ]);
        });

        $result = DB::select('select * from categories');
        assertEquals(2, count($result));
    }
}
