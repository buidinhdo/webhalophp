<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // ===== PS4 - Category ID: 1 =====
            [
                'name' => 'PlayStation 2 Slim - Secondhand',
                'slug' => 'playstation-2-slim-secondhand',
                'description' => 'Máy PS2 Slim đã qua sử dụng. Hoạt động tốt, bảo hành 3 tháng.',
                'short_description' => 'PS2 Slim - Đã qua sử dụng',
                'image' => 'images/products/sanpham2.jpg',
                'price' => 1200000,
                'sale_price' => null,
                'stock' => 5,
                'category_id' => 1,
                'platform' => 'PS2',
                'is_featured' => false,
                'status' => 'active'
            ],
            [
                'name' => 'PlayStation 3 Slim 500GB',
                'slug' => 'playstation-3-slim-500gb',
                'description' => 'Máy PS3 Slim 500GB. Chơi game và xem phim Blu-ray.',
                'short_description' => 'PS3 Slim 500GB',
                'price' => 3500000,
                'sale_price' => 3200000,
                'stock' => 8,
                'category_id' => 1,
                'platform' => 'PS3',
                'is_featured' => false,
                'status' => 'active'
            ],
            [
                'name' => 'PlayStation 4 Slim 1TB',
                'slug' => 'playstation-4-slim-1tb',
                'description' => 'Máy PS4 Slim 1TB. Bảo hành 12 tháng.',
                'short_description' => 'PS4 Slim 1TB - Chính hãng',
                'price' => 7500000,
                'sale_price' => null,
                'stock' => 12,
                'category_id' => 1,
                'platform' => 'PS4',
                'is_featured' => true,
                'status' => 'active'
            ],
            [
                'name' => 'PlayStation 4 Pro 1TB',
                'slug' => 'playstation-4-pro-1tb',
                'description' => 'Máy PS4 Pro 1TB hỗ trợ 4K. Bảo hành 12 tháng.',
                'short_description' => 'PS4 Pro 1TB - Chính hãng',
                'price' => 9500000,
                'sale_price' => 8900000,
                'stock' => 6,
                'category_id' => 1,
                'platform' => 'PS4',
                'is_featured' => true,
                'status' => 'active'
            ],
            [
                'name' => 'PlayStation 4 Slim 1TB',
                'slug' => 'playstation-4-slim-1tb-new',
                'description' => 'Máy PS4 Slim 1TB chính hãng Sony. Bảo hành 12 tháng.',
                'short_description' => 'PS4 Slim 1TB - Chính hãng',
                'price' => 7500000,
                'sale_price' => null,
                'stock' => 12,
                'category_id' => 1,
                'platform' => 'PS4',
                'is_featured' => false,
                'is_new' => true,
                'status' => 'active'
            ],
            [
                'name' => 'PlayStation 4 Pro 1TB',
                'slug' => 'playstation-4-pro-1tb-new',
                'description' => 'Máy PS4 Pro 1TB hỗ trợ 4K. Bảo hành chính hãng 12 tháng.',
                'short_description' => 'PS4 Pro 1TB - 4K Ready',
                'price' => 9500000,
                'sale_price' => 8900000,
                'stock' => 6,
                'category_id' => 1,
                'platform' => 'PS4',
                'is_featured' => false,
                'is_new' => true,
                'status' => 'active'
            ],
            
            // ===== PS5 - Category ID: 2 =====
            [
                'name' => 'PlayStation 5 Slim Digital Edition',
                'slug' => 'playstation-5-slim-digital-edition',
                'description' => 'Máy PS5 Slim phiên bản Digital - Không đĩa. Bảo hành chính hãng 12 tháng.',
                'short_description' => 'PS5 Slim Digital - Chính hãng',
                'price' => 10500000,
                'sale_price' => null,
                'stock' => 10,
                'category_id' => 2,
                'platform' => 'PS5',
                'is_featured' => true,
                'is_new' => true,
                'status' => 'active'
            ],
            [
                'name' => 'PlayStation 5 Slim Standard Edition',
                'slug' => 'playstation-5-slim-standard',
                'description' => 'Máy PS5 Slim phiên bản Standard có ổ đĩa. Bảo hành chính hãng 12 tháng.',
                'short_description' => 'PS5 Slim Standard - Chính hãng',
                'price' => 11990000,
                'sale_price' => null,
                'stock' => 8,
                'category_id' => 2,
                'platform' => 'PS5',
                'is_featured' => true,
                'is_new' => true,
                'status' => 'active'
            ],
            [
                'name' => 'PlayStation 5 Pro',
                'slug' => 'playstation-5-pro',
                'description' => 'Máy PS5 Pro - Hiệu suất cao nhất. Bảo hành chính hãng 12 tháng.',
                'short_description' => 'PS5 Pro - Chính hãng',
                'price' => 19450000,
                'sale_price' => null,
                'stock' => 5,
                'category_id' => 2,
                'platform' => 'PS5',
                'is_featured' => true,
                'is_new' => true,
                'status' => 'active'
            ],
            [
                'name' => 'PlayStation 5 Slim 2TB - Digital Edition',
                'slug' => 'playstation-5-slim-2tb-digital',
                'description' => 'PS5 Slim 2TB Digital Edition - Dung lượng lưu trữ khủng. Chính hãng Sony.',
                'short_description' => 'PS5 Slim 2TB - Digital',
                'price' => 35800000,
                'sale_price' => null,
                'stock' => 4,
                'category_id' => 2,
                'platform' => 'PS5',
                'is_new' => true,
                'status' => 'active'
            ],
            [
                'name' => 'Game Ghost of Yotei - PS5',
                'slug' => 'game-ghost-of-yotei-ps5',
                'description' => 'Game Ghost of Yotei cho PS5. Phần tiếp theo của Ghost of Tsushima.',
                'short_description' => 'Game nhập vai hành động',
                'price' => 1850000,
                'sale_price' => null,
                'stock' => 20,
                'category_id' => 2,
                'platform' => 'PS5',
                'is_preorder' => true,
                'release_date' => '2026-05-15',
                'status' => 'active'
            ],
            [
                'name' => 'Game Final Fantasy VII Remake Intergrade - PS5',
                'slug' => 'game-final-fantasy-vii-remake-ps5',
                'description' => 'Game Final Fantasy VII Remake Intergrade cho PS5.',
                'short_description' => 'Game nhập vai JRPG',
                'price' => 1650000,
                'sale_price' => 1450000,
                'stock' => 15,
                'category_id' => 2,
                'platform' => 'PS5',
                'is_featured' => true,
                'status' => 'active'
            ],
            [
                'name' => 'Game God of War Ragnarök - PS4/PS5',
                'slug' => 'game-god-of-war-ragnarok',
                'description' => 'Game God of War Ragnarök cho PS4 và PS5.',
                'short_description' => 'Game hành động phiêu lưu',
                'price' => 1550000,
                'sale_price' => null,
                'stock' => 25,
                'category_id' => 2,
                'platform' => 'PS4/PS5',
                'is_featured' => true,
                'status' => 'active'
            ],
            [
                'name' => 'Game Final Fantasy XVI - PS5',
                'slug' => 'game-final-fantasy-xvi-ps5',
                'description' => 'Game Final Fantasy XVI cho PS5.',
                'short_description' => 'Game nhập vai JRPG',
                'price' => 1750000,
                'sale_price' => null,
                'stock' => 20,
                'category_id' => 2,
                'platform' => 'PS5',
                'is_preorder' => true,
                'release_date' => '2026-07-15',
                'status' => 'active'
            ],
            [
                'name' => 'Game Resident Evil 9 - PS5',
                'slug' => 'game-resident-evil-9-ps5',
                'description' => 'Game Resident Evil 9 cho PS5.',
                'short_description' => 'Game kinh dị sinh tồn',
                'price' => 1850000,
                'sale_price' => null,
                'stock' => 15,
                'category_id' => 2,
                'platform' => 'PS5',
                'is_preorder' => true,
                'release_date' => '2026-09-05',
                'status' => 'active'
            ],
            [
                'name' => 'Game Spider-Man 3 - PS5',
                'slug' => 'game-spider-man-3-ps5',
                'description' => 'Game Spider-Man 3 cho PS5.',
                'short_description' => 'Game hành động siêu anh hùng',
                'price' => 1950000,
                'sale_price' => null,
                'stock' => 25,
                'category_id' => 2,
                'platform' => 'PS5',
                'is_preorder' => true,
                'release_date' => '2026-10-20',
                'status' => 'active'
            ],
            [
                'name' => 'Game Horizon Forbidden West Complete - PS5',
                'slug' => 'game-horizon-forbidden-west-complete-ps5',
                'description' => 'Game Horizon Forbidden West Complete Edition cho PS5.',
                'short_description' => 'Game phiêu lưu thế giới mở',
                'price' => 1650000,
                'sale_price' => null,
                'stock' => 20,
                'category_id' => 2,
                'platform' => 'PS5',
                'is_preorder' => true,
                'release_date' => '2026-11-15',
                'status' => 'active'
            ],
            [
                'name' => 'Game Death Stranding 2: On The Beach - PS5',
                'slug' => 'game-death-stranding-2-ps5',
                'description' => 'Game Death Stranding 2: On The Beach cho PS5.',
                'short_description' => 'Game hành động phiêu lưu',
                'price' => 1850000,
                'sale_price' => null,
                'stock' => 18,
                'category_id' => 2,
                'platform' => 'PS5',
                'is_preorder' => true,
                'release_date' => '2026-12-20',
                'status' => 'active'
            ],
            [
                'name' => 'PS5 DualSense Edge Controller',
                'slug' => 'ps5-dualsense-edge-controller',
                'description' => 'Tay cầm PS5 DualSense Edge cao cấp. Bảo hành 12 tháng.',
                'short_description' => 'DualSense Edge - Chính hãng',
                'price' => 5500000,
                'sale_price' => null,
                'stock' => 15,
                'category_id' => 2,
                'platform' => 'PS5',
                'is_featured' => false,
                'is_new' => true,
                'status' => 'active'
            ],
            [
                'name' => 'PS5 DualSense - Wireless Controller',
                'slug' => 'ps5-dualsense-wireless-controller',
                'description' => 'Tay cầm PS5 DualSense chính hãng Sony. Bảo hành 12 tháng.',
                'short_description' => 'DualSense - Chính hãng',
                'price' => 1850000,
                'sale_price' => 1650000,
                'stock' => 20,
                'category_id' => 2,
                'platform' => 'PS5',
                'status' => 'active'
            ],
            [
                'name' => 'PS5 Charging Station - DualSense',
                'slug' => 'ps5-charging-station-dualsense',
                'description' => 'Đế sạc tay cầm PS5 DualSense chính hãng Sony.',
                'short_description' => 'Đế sạc DualSense',
                'price' => 750000,
                'sale_price' => null,
                'stock' => 25,
                'category_id' => 2,
                'platform' => 'PS5',
                'status' => 'active'
            ],
            
            // ===== NINTENDO SWITCH - Category ID: 3 =====
            [
                'name' => 'Nintendo Switch 2',
                'slug' => 'nintendo-switch-2',
                'description' => 'Máy Nintendo Switch 2 thế hệ mới nhất. Bảo hành 12 tháng.',
                'short_description' => 'Switch 2 - Chính hãng',
                'price' => 12350000,
                'sale_price' => null,
                'stock' => 8,
                'category_id' => 3,
                'platform' => 'Nintendo Switch',
                'is_featured' => true,
                'is_new' => true,
                'status' => 'active'
            ],
            [
                'name' => 'Game Pokemon Legends: Z-A - Nintendo Switch 2',
                'slug' => 'game-pokemon-legends-z-a-switch-2',
                'description' => 'Game Pokemon Legends: Z-A cho Nintendo Switch 2.',
                'short_description' => 'Game Pokemon thế hệ mới',
                'price' => 1450000,
                'sale_price' => null,
                'stock' => 25,
                'category_id' => 3,
                'platform' => 'Nintendo Switch',
                'is_preorder' => true,
                'release_date' => '2026-06-20',
                'status' => 'active'
            ],
            [
                'name' => 'Game The Legend of Zelda: Echoes of Wisdom - Switch',
                'slug' => 'game-zelda-echoes-wisdom-switch',
                'description' => 'Game The Legend of Zelda: Echoes of Wisdom cho Nintendo Switch.',
                'short_description' => 'Game phiêu lưu',
                'price' => 1550000,
                'sale_price' => null,
                'stock' => 30,
                'category_id' => 3,
                'platform' => 'Nintendo Switch',
                'is_preorder' => true,
                'release_date' => '2026-08-10',
                'status' => 'active'
            ],
            [
                'name' => 'Game Metroid Prime 4: Beyond - Nintendo Switch',
                'slug' => 'game-metroid-prime-4-switch',
                'description' => 'Game Metroid Prime 4: Beyond cho Nintendo Switch.',
                'short_description' => 'Game bắn súng nhập vai',
                'price' => 1350000,
                'sale_price' => null,
                'stock' => 30,
                'category_id' => 3,
                'platform' => 'Nintendo Switch',
                'is_preorder' => true,
                'release_date' => '2026-12-05',
                'status' => 'active'
            ],
            
            // ===== XBOX - Category ID: 4 =====
            [
                'name' => 'Xbox Series X',
                'slug' => 'xbox-series-x',
                'description' => 'Máy Xbox Series X chính hãng. Bảo hành 12 tháng.',
                'short_description' => 'Xbox Series X - Chính hãng',
                'price' => 13990000,
                'sale_price' => null,
                'stock' => 6,
                'category_id' => 4,
                'platform' => 'Xbox',
                'status' => 'active'
            ],
            [
                'name' => 'Xbox Series Wireless Controller - Robot White',
                'slug' => 'xbox-series-controller-robot-white',
                'description' => 'Tay cầm Xbox Series X/S màu trắng. Chính hãng Microsoft.',
                'short_description' => 'Xbox Controller - Chính hãng',
                'price' => 1350000,
                'sale_price' => null,
                'stock' => 15,
                'category_id' => 4,
                'platform' => 'Xbox',
                'status' => 'active'
            ],
            [
                'name' => 'HDMI 2.1 Cable - 8K Ultra High Speed',
                'slug' => 'hdmi-2-1-cable-8k',
                'description' => 'Cáp HDMI 2.1 hỗ trợ 8K 60Hz / 4K 120Hz. Dài 2m.',
                'short_description' => 'Cáp HDMI 2.1 - 2m',
                'price' => 350000,
                'sale_price' => null,
                'stock' => 40,
                'category_id' => 4,
                'platform' => 'Universal',
                'status' => 'active'
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
