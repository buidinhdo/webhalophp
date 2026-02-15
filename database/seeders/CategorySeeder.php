<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'PlayStation 4',
                'slug' => 'ps4',
                'description' => 'Máy PlayStation 4, PS4 Slim, PS4 Pro',
                'order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'PlayStation 5',
                'slug' => 'ps5',
                'description' => 'Máy PlayStation 5, PS5 Slim, PS5 Pro và Game PS5',
                'order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Nintendo Switch',
                'slug' => 'nintendo-switch',
                'description' => 'Máy Nintendo Switch và Game Nintendo Switch',
                'order' => 3,
                'is_active' => true
            ],
            [
                'name' => 'Xbox',
                'slug' => 'xbox',
                'description' => 'Máy Xbox Series X/S và phụ kiện Xbox',
                'order' => 4,
                'is_active' => true
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
