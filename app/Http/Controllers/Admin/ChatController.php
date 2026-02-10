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
        // Lấy danh sách các session chat, group theo session_id
        $sessions = ChatMessage::select('session_id', DB::raw('MAX(created_at) as last_message_at'), DB::raw('COUNT(*) as message_count'))
            ->groupBy('session_id')
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);
        
        return view('admin.chats.index', compact('sessions'));
    }
    
    public function show($sessionId)
    {
        $messages = ChatMessage::where('session_id', $sessionId)
            ->with(['product:id,name,price,image,slug', 'user:id,name,email'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Đánh dấu đã đọc các tin nhắn của user
        ChatMessage::where('session_id', $sessionId)
            ->where('type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return view('admin.chats.show', compact('messages', 'sessionId'));
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
