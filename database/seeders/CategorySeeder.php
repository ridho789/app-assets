<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = [
            ['name' => 'FUEL EXPENSES'],
            ['name' => 'MATERIAL EXPENSES'],
            ['name' => 'SALARY EXPENSES'],
            ['name' => 'SPAREPART EXPENSES'],
            ['name' => 'UNEXPECTED EXPENSES'],
        ];

        foreach ($category as $c) {
            Category::create($c);
        }
    }
}
