<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class UpdateProductMeta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-meta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse và cập nhật ESRB rating và Publisher từ description của sản phẩm';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu cập nhật ESRB và Publisher từ description...');
        
        $products = Product::whereNotNull('description')->get();
        $updated = 0;
        $skipped = 0;
        
        foreach ($products as $product) {
            $esrb = null;
            $publisher = null;
            $description = $product->description;
            $productName = (string) $product->name;
            
            // Extract ESRB rating
            if (preg_match('/ESRB:\s*(.+?)(?:\n|$)/i', $description, $matches)) {
                $esrbRaw = trim($matches[1]);
                // Lấy chỉ phần đầu tiên trước dấu phẩy hoặc description chi tiết
                if (preg_match('/^(Everyone|E10\+|Teen|Mature\s*17\+|Adults?\s*Only|Rating\s*Pending|E|T|M|AO|RP)/i', $esrbRaw, $esrbMatch)) {
                    $esrb = $this->normalizeESRB($esrbMatch[1]);
                }
            }
            
            // Extract Publisher từ nhiều format mô tả khác nhau
            $patterns = [
                '/Nhà\s*sản\s*xuất\s*&\s*phát\s*hành\s*:?\s*(.+?)(?:\n|$)/ui',
                '/Nhà\s*sản\s*xuất\s*phát\s*hành\s*:?\s*(.+?)(?:\n|$)/ui',
                '/Nhà\s*phát\s*hành\s*:?\s*(.+?)(?:\n|$)/ui',
                '/Hãng\s*sản\s*xuất\s*:?\s*(.+?)(?:\n|$)/ui',
                '/Publisher\s*:?\s*(.+?)(?:\n|$)/i',
            ];

            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $description, $matches)) {
                    $publisher = trim($matches[1]);
                    $publisher = preg_split('/\s*\|\s*/', $publisher)[0] ?? $publisher;
                    $publisher = $this->normalizePublisher($publisher);
                    break;
                }
            }

            // Fallback an toàn cho sản phẩm phần cứng/phụ kiện khi mô tả không có publisher rõ ràng
            if (!$publisher) {
                $isGame = preg_match('/^\s*Game\b/i', $productName) === 1;

                if (!$isGame && preg_match('/PlayStation|\bPS[1-5]\b|DualSense/i', $productName)) {
                    $publisher = 'Sony Interactive Entertainment';
                } elseif (!$isGame && preg_match('/Nintendo|Switch|GameCube|Wii|Super Nintendo/i', $productName)) {
                    $publisher = 'Nintendo';
                } elseif (!$isGame && preg_match('/Xbox|Microsoft|\bbox\s+Elite/i', $productName)) {
                    $publisher = 'Microsoft';
                }
            }
            
            // Update nếu có thông tin mới
            if ($esrb || $publisher) {
                if ($esrb) {
                    $product->esrb_rating = $esrb;
                }
                if ($publisher) {
                    $product->publisher = $publisher;
                }
                $product->save();
                
                $this->line("✓ ID {$product->id}: {$product->name}");
                if ($esrb) {
                    $this->line("  ESRB: {$esrb}");
                }
                if ($publisher) {
                    $this->line("  Publisher: {$publisher}");
                }
                
                $updated++;
            } else {
                $skipped++;
            }
        }
        
        $this->newLine();
        $this->info("Hoàn thành!");
        $this->info("Đã cập nhật: {$updated} sản phẩm");
        $this->info("Bỏ qua: {$skipped} sản phẩm (không có thông tin)");
        
        return 0;
    }
    
    private function normalizeESRB($esrb)
    {
        $esrb = trim($esrb);
        
        // Chuẩn hóa các giá trị ESRB
        if (preg_match('/Everyone.*10/i', $esrb)) return 'E10+';
        if (preg_match('/Teen/i', $esrb)) return 'T';
        if (preg_match('/Mature|17\+|M/i', $esrb)) return 'M';
        if (preg_match('/Adults?\s*Only|AO|18\+/i', $esrb)) return 'AO';
        if (preg_match('/Rating\s*Pending|RP/i', $esrb)) return 'RP';
        if (preg_match('/Everyone|^E$/i', $esrb)) return 'E';
        
        return $esrb;
    }
    
    private function normalizePublisher($publisher)
    {
        $publisher = trim((string) $publisher);
        $publisher = trim($publisher, " \t\n\r\0\x0B:;,.-");
        $publisher = preg_replace('/\s{2,}/', ' ', $publisher);
        if ($publisher === '') {
            return null;
        }
        
        // Chuẩn hóa tên nhà phát hành
        $map = [
            'Bandai Namco Games' => 'Bandai Namco',
            'Namco' => 'Bandai Namco',
            'Sony Computer Entertainment' => 'Sony Interactive Entertainment',
            'Sony' => 'Sony Interactive Entertainment',
            'EA' => 'Electronic Arts',
            'Microsoft Game Studios' => 'Microsoft',
            'Microsoft Studios' => 'Microsoft',
            'Koei Tecmo' => 'Koei',
            'Konami Digital Entertainment' => 'Konami',
            'Nintendo Co.' => 'Nintendo',
            'Ubisoft Entertainment' => 'Ubisoft',
        ];
        
        foreach ($map as $old => $new) {
            if (stripos($publisher, $old) !== false) {
                return $new;
            }
        }
        
        return $publisher;
    }
}
