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
                'name' => 'PlayStation',
                'slug' => 'playstation',
                'description' => 'Máy PlayStation từ PS2 đến PS5',
                'order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'PlayStation 2 (PS2)',
                'slug' => 'playstation-2',
                'description' => 'Máy PlayStation 2 chính hãng',
                'parent_id' => 1,
                'order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'PlayStation 3 (PS3)',
                'slug' => 'playstation-3',
                'description' => 'Máy PlayStation 3 chính hãng',
                'parent_id' => 1,
                'order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'PlayStation 4 (PS4)',
                'slug' => 'playstation-4',
                'description' => 'Máy PlayStation 4 và PS4 Pro',
                'parent_id' => 1,
                'order' => 3,
                'is_active' => true
            ],
            [
                'name' => 'PlayStation 5 (PS5)',
                'slug' => 'playstation-5',
                'description' => 'Máy PlayStation 5 và PS5 Pro',
                'parent_id' => 1,
                'order' => 4,
                'is_active' => true
            ],
            [
                'name' => 'Game PlayStation',
                'slug' => 'game-playstation',
                'description' => 'Game cho các dòng PlayStation',
                'order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Máy Nintendo Switch',
                'slug' => 'may-nintendo-switch',
                'description' => 'Máy Nintendo Switch chính hãng',
                'order' => 3,
                'is_active' => true
            ],
            [
                'name' => 'Game Nintendo Switch',
                'slug' => 'game-nintendo-switch',
                'description' => 'Game Nintendo Switch chính hãng',
                'order' => 4,
                'is_active' => true
            ],
            [
                'name' => 'Máy Xbox',
                'slug' => 'may-xbox',
                'description' => 'Máy Xbox Series X/S chính hãng',
                'order' => 5,
                'is_active' => true
            ],
            [
                'name' => 'iPhone',
                'slug' => 'iphone',
                'description' => 'iPhone chính hãng VN/A',
                'order' => 6,
                'is_active' => true
            ],
            [
                'name' => 'iPad',
                'slug' => 'ipad',
                'description' => 'iPad chính hãng Apple',
                'order' => 7,
                'is_active' => true
            ],
            [
                'name' => 'Tay cầm',
                'slug' => 'tay-cam',
                'description' => 'Tay cầm chơi game',
                'order' => 8,
                'is_active' => true
            ],
            [
                'name' => 'Phụ kiện',
                'slug' => 'phu-kien',
                'description' => 'Phụ kiện gaming',
                'order' => 9,
                'is_active' => true
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
