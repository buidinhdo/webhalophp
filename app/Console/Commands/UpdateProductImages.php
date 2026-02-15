<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class UpdateProductImages extends Command
{
    protected $signature = 'products:update-images';
    protected $description = 'Cập nhật đường dẫn ảnh cho tất cả sản phẩm từ thư mục public/images/products';

    public function handle()
    {
        $this->info('🔄 Bắt đầu cập nhật ảnh sản phẩm...');
        
        $imagePath = public_path('images/products');
        $availableImages = File::files($imagePath);
        
        // Tạo danh sách tên file ảnh
        $imageNames = array_map(function($file) {
            return $file->getFilename();
        }, $availableImages);
        
        $this->info('📁 Tìm thấy ' . count($imageNames) . ' ảnh trong thư mục products');
        
        // Mapping sản phẩm với ảnh (dựa trên tên/slug)
        $imageMapping = [
            // PS2
            'playstation-2-slim-secondhand' => 'sanpham1.jpg',
            
            // PS3
            'playstation-3-slim-500gb' => 'sanpham2.jpg',
            
            // PS4
            'playstation-4-slim-1tb' => 'sanpham3.jpg',
            'playstation-4-pro-1tb' => 'sanpham4.jpg',
            
            // PS5
            'playstation-5-slim-digital-edition' => '1771122110_PS5-SLIM-STANDARD-EDITION-DUALSENSE-WHITE-00.webp',
            'playstation-5-slim-standard' => '1771122110_PS5-SLIM-STANDARD-EDITION-DUALSENSE-WHITE-00.webp',
            'playstation-5-pro' => '1771121790_PlayStation-5-PS5-Standard-Edition-2.webp',
            
            // Games - Best Sellers
            'game-elden-ring-nightreign-ps5' => '1771121739_elden_ring_nightreign_asia_ps5-700x700h.jpg',
            'game-ghost-of-tsushima-directors-cut' => '1770790163_Ghost-of-Tsushima_-Director_s-Cut-US.jpg',
            'game-demon-slayer-sweep-the-board-ps5' => '1770876693_demon-slayer-kimetsu-no-yaiba-sweep-the-board_ps5-600x600.webp',
            'game-call-of-duty-black-ops-iii-zombies-chronicles-ps4-digital' => 'sanpham5.jpg',
            'game-legend-of-heroes-trails-cold-steel-iii-ps4' => '1771121554_the-legend-of-heroes-trails-of-cold-steel-3-early-enrollment-edition-ps4-700x700h-1.jpg',
            'grand-theft-auto-v-ps4-game' => '1771121409_Grand-Theft-Auto-V-Premium-Edition-US.jpg',
            
            // Other Games
            'game-ghost-of-yotei-ps5' => '1770790536_Ghost-of-Tsushima_-Director_s-Cut-US.jpg',
            'game-final-fantasy-vii-remake-ps5' => 'sanpham14.jpg',
            'game-god-of-war-ragnarok' => 'sanpham15.jpg',
            'game-final-fantasy-xvi-ps5' => 'sanpham16.jpg',
            'game-resident-evil-9-ps5' => 'sanpham17.jpg',
            'game-spider-man-3-ps5' => 'sanpham19.jpg',
            'game-horizon-forbidden-west-complete-ps5' => '1771122490_horizon-forbidden-west-ps5-700x700h.jpg',
            'game-death-stranding-2-ps5' => '1771128074_PS5-DUALSENSE-DEATH-STRANDING-2-ON-THE-BEACH-LIMITED-EDITION-WIRELESS-GAME-CONTROLLER-43.jpg',
            
            // Nintendo
            'nintendo-switch-2' => '1771121932_NINTENDO-SWITCH-2-WITH-MARIO-KART-WORLD-BUNDLE-00-1.webp',
            'game-pokemon-legends-z-a-switch-2' => '1771122657_POKEMON-LEGENDS-Z-A_asia_sw2.webp',
            'game-zelda-echoes-wisdom-switch' => '1771122609_the-legend-of-zelda-breath-of-the-wild-switch-700x700h.jpg',
            'game-metroid-prime-4-switch' => '1771122719_METROID-PRIME-4-BEYOND-switch.webp',
            
            // Controllers & Accessories
            'ps5-dualsense-edge-controller' => '1770531698_dualsense-ps5-edge-wireless-controller-00-700x700-1.jpg',
            'ps5-dualsense-wireless-controller' => '1771122751_dualsense-charging-station-00-700x700-1.jpg',
            'xbox-series-controller-robot-white' => '1771122872_xbox-elite-wireless-controller-series-2-core-white-00-700x700-1.jpg',
            'ps5-charging-station-dualsense' => '1771122751_dualsense-charging-station-00-700x700-1.jpg',
            
            // Xbox
            'xbox-series-x' => '1771122823_Xbox-Series-S-white.webp',
            
            // iPhone/iPad
            'iphone-17-pro-max-deep-blue-vn' => 'iphone172.png',
            'iphone-air-space-black-vn' => 'iphone16.png',
            
            // PS4 Games
            'the-last-of-us-part-2-remaster-ps5' => '1771122252_the_last_of_us_part_2_remaster_ps5_00-700x700h.jpg',
            
            // More Products
            'playstation-4-slim-1tb-new' => 'sanpham22.jpg',
            'playstation-4-pro-1tb-new' => 'sanpham23.jpg',
            'playstation-5-slim-2tb-digital' => 'sanpham25.jpg',
            'hdmi-2-1-cable-8k' => 'sanpham26.jpg',
        ];
        
        $updated = 0;
        $skipped = 0;
        
        foreach ($imageMapping as $slug => $imageName) {
            $product = Product::where('slug', $slug)->first();
            
            if ($product) {
                $imagePath = 'images/products/' . $imageName;
                
                // Kiểm tra file tồn tại
                if (File::exists(public_path($imagePath))) {
                    $product->image = $imagePath;
                    $product->save();
                    $this->info("✓ Updated: {$product->name} -> {$imageName}");
                    $updated++;
                } else {
                    $this->warn("⚠ Image not found: {$imageName} for {$slug}");
                    $skipped++;
                }
            } else {
                $this->warn("⚠ Product not found: {$slug}");
                $skipped++;
            }
        }
        
        // Update các sản phẩm còn lại chưa có ảnh với ảnh mặc định
        $productsWithoutImages = Product::whereNull('image')->orWhere('image', '')->get();
        
        $defaultImages = [
            'sanpham27.jpg',
            'sanpham29.jpg',
            'anpham20.jpg',
        ];
        
        $defaultIndex = 0;
        foreach ($productsWithoutImages as $product) {
            if ($defaultIndex < count($defaultImages)) {
                $imagePath = 'images/products/' . $defaultImages[$defaultIndex];
                $product->image = $imagePath;
                $product->save();
                $this->info("✓ Set default image for: {$product->name}");
                $updated++;
                $defaultIndex++;
            }
        }
        
        $this->info("\n✅ HOÀN TẤT!");
        $this->info("📊 Đã cập nhật: {$updated} sản phẩm");
        $this->info("⏭️  Bỏ qua: {$skipped} sản phẩm");
        
        return 0;
    }
}
