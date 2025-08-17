<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
    }
}
