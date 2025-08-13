<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertEquals;

class RawSqlTest extends TestCase
{
    protected function setup(): void
    {
        parent::setUp();
        DB::delete('DELETE FROM categories');
    }

    public function testInsert(): void
    {
        DB::insert('INSERT INTO categories(id, name, description, created_at) VALUES(?, ?, ?, ?)', ['GADGET', 'Hp', 'Flagship', '2022-01-01 00:00:00']);

        $result = DB::select('SELECT * FROM categories WHERE id = ?', ['GADGET']);

        assertEquals(1, count($result));
        assertEquals('GADGET', $result[0]->id);
        assertEquals('Hp', $result[0]->name);
        assertEquals('Flagship', $result[0]->description);
        assertEquals('2022-01-01 00:00:00', $result[0]->created_at);
    }

    public function testNamedBinding(): void
    {
        DB::insert('INSERT INTO categories(id, name, description, created_at) VALUES(:id, :name, :description, :created_at)', [
            'id' => 'GADGET',
            'name' => 'Hp',
            'description' => 'Flagship',
            'created_at' => '2022-01-01 00:00:00'
        ]);

        $results = DB::select('SELECT * FROM categories WHERE id = :id', ['id' => 'GADGET']);

        assertEquals(1, count($results));
        assertEquals('GADGET', $results[0]->id);
        assertEquals('Hp', $results[0]->name);
        assertEquals('Flagship', $results[0]->description);
        assertEquals('2022-01-01 00:00:00', $results[0]->created_at);
    }


}
