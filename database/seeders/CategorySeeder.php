<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    private $categories = [
        ['title' => 'Electronics'],
        ['title' => 'Mobile'],
        ['title' => 'Video Games'],
        ['title' => 'Playstation'],
    ];
    public function run()
    {
        DB::table('categories')->insert($this->categories);
    }
}
