<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        // Lấy danh sách các session chat với tên khách hàng
        $sessions = DB::table('chat_messages as cm1')
            ->select(
                'cm1.session_id',
                DB::raw('MAX(cm1.created_at) as last_message_at'),
                DB::raw('COUNT(*) as message_count'),
                DB::raw('(SELECT user_id FROM chat_messages WHERE session_id = cm1.session_id AND type = "user" LIMIT 1) as user_id')
            )
            ->groupBy('cm1.session_id')
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);
        
        // Lấy thông tin user cho mỗi session
        foreach ($sessions as $session) {
            if ($session->user_id) {
                $user = DB::table('users')->where('id', $session->user_id)->first();
                $session->customer_name = $user ? $user->name : 'Khách (Guest)';
            } else {
                $session->customer_name = 'Khách (Guest)';
            }
        }
        
        return view('admin.chats.index', compact('sessions'));
    }
    
    public function show($sessionId)
    {
        $messages = ChatMessage::where('session_id', $sessionId)
            ->with(['product:id,name,price,image,slug', 'user:id,name,email'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Lấy tên khách hàng từ tin nhắn đầu tiên
        $customerName = 'Khách (Guest)';
        $firstUserMessage = ChatMessage::where('session_id', $sessionId)
            ->where('type', 'user')
            ->with('user:id,name')
            ->first();
        
        if ($firstUserMessage && $firstUserMessage->user) {
            $customerName = $firstUserMessage->user->name;
        }
        
        // Đánh dấu đã đọc các tin nhắn của user
        ChatMessage::where('session_id', $sessionId)
            ->where('type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return view('admin.chats.show', compact('messages', 'sessionId', 'customerName'));
    }
    
    public function reply(Request $request, $sessionId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ], [
            'message.required' => 'Vui lòng nhập nội dung tin nhắn',
            'message.max' => 'Tin nhắn không được vượt quá 1000 ký tự',
        ]);
        
        ChatMessage::create([
            'session_id' => $sessionId,
            'type' => 'admin',
            'message' => $request->message,
            'user_id' => auth()->id(),
        ]);
        
        return redirect()->route('admin.chats.show', $sessionId)
            ->with('success', 'Đã gửi tin nhắn thành công!');
    }
    
    public function destroy($sessionId)
    {
        ChatMessage::where('session_id', $sessionId)->delete();
        
        return redirect()->route('admin.chats.index')
            ->with('success', 'Đã xóa cuộc trò chuyện thành công!');
    }
    
    public function getUnreadCount()
    {
        $count = ChatMessage::where('type', 'user')
            ->where('is_read', false)
            ->distinct('session_id')
            ->count('session_id');
        
        return response()->json(['count' => $count]);
    }
}
