<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class XboxGamesSeeder extends Seeder
{
    public function run()
    {
        $xboxCategory = Category::where('slug', 'xbox')->first();
        
        if (!$xboxCategory) {
            echo "Xbox category not found!\n";
            return;
        }

        $games = [
            [
                'name' => 'Halo Infinite - Xbox Series X',
                'genre' => 'Shooting',
                'price' => 1490000,
                'sale_price' => 1290000,
                'description' => 'Game bắn súng đình đám độc quyền Xbox với đồ họa tuyệt đẹp',
            ],
            [
                'name' => 'Forza Horizon 5 - Xbox Series X',
                'genre' => 'Racing',
                'price' => 1390000,
                'sale_price' => 1190000,
                'description' => 'Game đua xe open-world đẹp nhất thế giới',
            ],
            [
                'name' => 'Gears 5 - Xbox Series X',
                'genre' => 'Shooting',
                'price' => 990000,
                'sale_price' => null,
                'description' => 'Game hành động bắn súng góc nhìn thứ ba hấp dẫn',
            ],
            [
                'name' => 'Starfield - Xbox Series X',
                'genre' => 'RPG',
                'price' => 1690000,
                'sale_price' => 1490000,
                'description' => 'Game nhập vai không gian mở rộng lớn từ Bethesda',
            ],
            [
                'name' => 'Mortal Kombat 11 - Xbox One',
                'genre' => 'Fighting',
                'price' => 890000,
                'sale_price' => null,
                'description' => 'Game đối kháng đỉnh cao với đồ họa bạo lực',
            ],
        ];

        foreach ($games as $gameData) {
            $slug = Str::slug($gameData['name']);
            
            // Check if product already exists
            $existingProduct = Product::where('slug', $slug)->first();
            if ($existingProduct) {
                echo "Product {$gameData['name']} already exists, skipping...\n";
                continue;
            }

            Product::create([
                'name' => $gameData['name'],
                'slug' => $slug,
                'description' => $gameData['description'],
                'short_description' => Str::limit($gameData['description'], 100),
                'price' => $gameData['price'],
                'sale_price' => $gameData['sale_price'],
                'sku' => 'XBOX-' . strtoupper(Str::random(8)),
                'stock' => rand(5, 30),
                'category_id' => $xboxCategory->id,
                'platform' => 'Xbox',
                'genre' => $gameData['genre'],
                'is_featured' => rand(0, 1),
                'is_new' => 1,
                'is_preorder' => 0,
                'status' => 'active',
                'image' => 'images/products/xbox-game-placeholder.jpg',
            ]);

            echo "Added: {$gameData['name']} - Genre: {$gameData['genre']}\n";
        }

        echo "\nXbox games with genres added successfully!\n";
    }
}
