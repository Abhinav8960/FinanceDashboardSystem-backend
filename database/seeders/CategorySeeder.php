<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    //
    public function run(): void
    {
        $categories = [
            ['name' => 'Food', 'status' => true],
            ['name' => 'Rent', 'status' => true],
            ['name' => 'Salary', 'status' => true],
            ['name' => 'Travel', 'status' => true],
            ['name' => 'Shopping', 'status' => true],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['status' => $category['status']]
            );
        }
    }
}
