<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ChatMessage;
use App\Models\Product;
use Carbon\Carbon;

class ChatMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Session 1: Khách hàng hỏi về game PS5
        $session1 = 'session_test_' . time() . '_001';
        $product = Product::where('platform', 'LIKE', '%PS5%')->first();
        
        ChatMessage::create([
            'session_id' => $session1,
            'type' => 'user',
            'message' => 'Cho tôi xem các game PS5 đang có',
            'user_id' => null,
            'created_at' => Carbon::now()->subMinutes(10),
        ]);
        
        ChatMessage::create([
            'session_id' => $session1,
            'type' => 'bot',
            'message' => 'Chúng tôi tìm thấy một số sản phẩm PS5 phù hợp với yêu cầu của bạn. Hãy xem các sản phẩm bên dưới và cho tôi biết nếu bạn cần thông tin chi tiết về sản phẩm nào nhé!',
            'product_id' => $product ? $product->id : null,
            'created_at' => Carbon::now()->subMinutes(10)->addSeconds(2),
        ]);
        
        ChatMessage::create([
            'session_id' => $session1,
            'type' => 'user',
            'message' => 'Sản phẩm này giá bao nhiêu?',
            'user_id' => null,
            'created_at' => Carbon::now()->subMinutes(8),
        ]);
        
        ChatMessage::create([
            'session_id' => $session1,
            'type' => 'bot',
            'message' => $product ? "Giá của {$product->name} là " . number_format($product->price) . " VNĐ. Sản phẩm có sẵn và bạn có thể đặt hàng ngay!" : 'Vui lòng chọn sản phẩm bạn quan tâm để xem giá.',
            'product_id' => $product ? $product->id : null,
            'created_at' => Carbon::now()->subMinutes(8)->addSeconds(2),
        ]);
        
        // Session 2: Khách hàng hỏi về game Nintendo Switch
        $session2 = 'session_test_' . time() . '_002';
        
        ChatMessage::create([
            'session_id' => $session2,
            'type' => 'user',
            'message' => 'Có game nào hay cho Nintendo Switch không?',
            'user_id' => null,
            'created_at' => Carbon::now()->subMinutes(5),
        ]);
        
        ChatMessage::create([
            'session_id' => $session2,
            'type' => 'bot',
            'message' => 'Chúng tôi có nhiều game hay cho Nintendo Switch! Hãy xem các sản phẩm bên dưới và cho tôi biết nếu bạn cần thông tin chi tiết về sản phẩm nào nhé!',
            'created_at' => Carbon::now()->subMinutes(5)->addSeconds(2),
        ]);
        
        ChatMessage::create([
            'session_id' => $session2,
            'type' => 'user',
            'message' => 'Bạn có thể tư vấn thêm không?',
            'user_id' => null,
            'created_at' => Carbon::now()->subMinutes(2),
        ]);
        
        // Session 3: Khách hàng hỏi chung chung
        $session3 = 'session_test_' . time() . '_003';
        
        ChatMessage::create([
            'session_id' => $session3,
            'type' => 'user',
            'message' => 'Xin chào, shop có game gì mới không?',
            'user_id' => null,
            'created_at' => Carbon::now()->subMinute(),
        ]);
        
        ChatMessage::create([
            'session_id' => $session3,
            'type' => 'bot',
            'message' => 'Xin chào! Chúng tôi có nhiều sản phẩm game mới nhất. Hãy cho tôi biết bạn đang tìm game cho nền tảng nào (PS5, PS4, Nintendo Switch, Xbox...) để tôi tư vấn cụ thể hơn nhé!',
            'created_at' => Carbon::now()->subMinute()->addSeconds(2),
        ]);
        
        $this->command->info('✓ Đã tạo 3 cuộc trò chuyện mẫu trong Chat/Bình luận');
        $this->command->info('→ Vào Admin Dashboard > Chat/Bình luận để xem và phản hồi');
    }
}
