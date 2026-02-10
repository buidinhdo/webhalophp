<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Models\Product;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            \Log::info('Chatbot sendMessage called', [
                'message' => $request->message,
                'session_id' => $request->session_id,
            ]);
            
            $request->validate([
                'message' => 'required|string|max:1000',
                'session_id' => 'required|string',
            ]);
            
            // Chỉ lưu tin nhắn của user, không tự động trả lời
            $userMessage = ChatMessage::create([
                'session_id' => $request->session_id,
                'type' => 'user',
                'message' => $request->message,
                'user_id' => auth()->id(),
            ]);
            
            \Log::info('User message saved', ['id' => $userMessage->id]);
            
            // Trả về success, admin sẽ trả lời từ dashboard
            return response()->json([
                'success' => true,
                'message' => 'Tin nhắn đã được gửi. Chúng tôi sẽ phản hồi sớm nhất có thể.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Chatbot error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function searchProducts($message)
    {
        $keywords = $this->extractKeywords($message);
        
        if (empty($keywords)) {
            return Product::where('stock', '>', 0)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id', 'name', 'price', 'image', 'platform', 'slug']);
        }
        
        $query = Product::where('stock', '>', 0);
        
        foreach ($keywords as $keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%")
                  ->orWhere('platform', 'LIKE', "%{$keyword}%")
                  ->orWhere('genre', 'LIKE', "%{$keyword}%");
            });
        }
        
        $products = $query->limit(5)->get(['id', 'name', 'price', 'image', 'platform', 'slug']);
        
        // Nếu không tìm thấy, thử tìm với OR
        if ($products->isEmpty()) {
            $query = Product::where('stock', '>', 0);
            
            $query->where(function($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('name', 'LIKE', "%{$keyword}%")
                      ->orWhere('description', 'LIKE', "%{$keyword}%")
                      ->orWhere('platform', 'LIKE', "%{$keyword}%")
                      ->orWhere('genre', 'LIKE', "%{$keyword}%");
                }
            });
            
            $products = $query->limit(5)->get(['id', 'name', 'price', 'image', 'platform', 'slug']);
        }
        
        return $products;
    }
    
    private function extractKeywords($message)
    {
        $message = strtolower($message);
        
        // Loại bỏ các từ không cần thiết
        $stopWords = ['tôi', 'muốn', 'mua', 'tìm', 'kiếm', 'có', 'không', 'gì', 'nào', 'cho', 'của', 'game', 'games', 'sản', 'phẩm'];
        
        $words = preg_split('/\s+/', $message);
        $keywords = array_filter($words, function($word) use ($stopWords) {
            return strlen($word) > 2 && !in_array($word, $stopWords);
        });
        
        return array_values($keywords);
    }
    
    private function generateResponse($message, $products)
    {
        $message = strtolower($message);
        
        if (empty($products) || $products->isEmpty()) {
            return [
                'message' => 'Xin lỗi, hiện tại chúng tôi không tìm thấy sản phẩm phù hợp với yêu cầu của bạn. Bạn có thể xem các sản phẩm khác hoặc liên hệ với chúng tôi để được tư vấn cụ thể hơn!',
                'product_id' => null,
            ];
        }
        
        $count = $products->count();
        
        if (preg_match('/giá|bao nhiêu|cost|price/', $message)) {
            if ($count == 1) {
                $product = $products->first();
                return [
                    'message' => "Giá của {$product->name} là " . number_format($product->price) . " VNĐ. Sản phẩm có sẵn và bạn có thể đặt hàng ngay!",
                    'product_id' => $product->id,
                ];
            }
        }
        
        if ($count == 1) {
            $product = $products->first();
            return [
                'message' => "Chúng tôi có {$product->name} ({$product->platform}) với giá " . number_format($product->price) . " VNĐ. Bạn có muốn xem chi tiết sản phẩm này không?",
                'product_id' => $product->id,
            ];
        }
        
        return [
            'message' => "Chúng tôi tìm thấy {$count} sản phẩm phù hợp với yêu cầu của bạn. Hãy xem các sản phẩm bên dưới và cho tôi biết nếu bạn cần thông tin chi tiết về sản phẩm nào nhé!",
            'product_id' => null,
        ];
    }
    
    public function getHistory(Request $request)
    {
        $sessionId = $request->input('session_id');
        
        // Chỉ lấy tin nhắn trong session này (không cross-session)
        // Session đã unique per user nên không cần filter thêm
        $messages = ChatMessage::where('session_id', $sessionId)
            ->with('product:id,name,price,image,slug')
            ->orderBy('created_at', 'asc')
            ->get();
        
        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }
    
    public function getNewMessages(Request $request)
    {
        $sessionId = $request->input('session_id');
        $lastMessageId = $request->input('last_message_id', 0);
        
        // Chỉ lấy tin nhắn mới trong session này (không cross-session)
        $messages = ChatMessage::where('session_id', $sessionId)
            ->where('id', '>', $lastMessageId)
            ->with('product:id,name,price,image,slug', 'user:id,name')
            ->orderBy('created_at', 'asc')
            ->get();
        
        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }
    
    public function getOrCreateSession(Request $request)
    {
        // Nếu user đã đăng nhập, tìm session gần nhất của user đó
        if (auth()->check()) {
            $userId = auth()->id();
            
            // Tìm session gần nhất của user này
            $lastSession = ChatMessage::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($lastSession) {
                // Reuse session cũ của user
                return response()->json([
                    'success' => true,
                    'session_id' => $lastSession->session_id,
                    'is_existing' => true
                ]);
            }
            
            // Tạo session mới nếu user chưa có chat nào
            $sessionId = 'session_user_' . $userId . '_' . time();
        } else {
            // Guest user - tạo session mới
            $sessionId = 'session_guest_' . time() . '_' . substr(md5(uniqid()), 0, 8);
        }
        
        return response()->json([
            'success' => true,
            'session_id' => $sessionId,
            'is_existing' => false
        ]);
    }
}
