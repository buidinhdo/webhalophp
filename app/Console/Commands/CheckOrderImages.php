<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderItem;
use Illuminate\Support\Facades\File;

class CheckOrderImages extends Command
{
    protected $signature = 'orders:check-images';
    protected $description = 'Kiểm tra và báo cáo trạng thái ảnh sản phẩm trong đơn hàng';

    public function handle()
    {
        $this->info('Đang kiểm tra ảnh sản phẩm trong đơn hàng...');
        
        $items = OrderItem::all();
        $missing = 0;
        $exists = 0;
        $noImage = 0;
        
        $this->info("\nTổng số order items: " . $items->count());
        
        foreach ($items as $item) {
            if (!$item->product_image) {
                $noImage++;
                $this->warn("Item #{$item->id} ({$item->product_name}): Chưa có ảnh");
            } elseif (!File::exists(public_path($item->product_image))) {
                $missing++;
                $this->error("Item #{$item->id} ({$item->product_name}): Ảnh không tồn tại - {$item->product_image}");
            } else {
                $exists++;
                $this->line("Item #{$item->id} ({$item->product_name}): ✓ OK - {$item->product_image}");
            }
        }
        
        $this->info("\n=== KẾT QUẢ KIỂM TRA ===");
        $this->info("✓ Ảnh tồn tại: {$exists}");
        $this->warn("⚠ Chưa có ảnh: {$noImage}");
        $this->error("✗ Ảnh bị thiếu: {$missing}");
        
        if ($missing > 0) {
            $this->warn("\nGợi ý: Sử dụng trang admin để cập nhật ảnh cho các sản phẩm bị thiếu.");
        }
        
        return 0;
    }
}
