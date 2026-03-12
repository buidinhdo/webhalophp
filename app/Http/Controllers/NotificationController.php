<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Hiển thị danh sách thông báo
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
                                     ->latest()
                                     ->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }

    // Lấy số thông báo chưa đọc (AJAX)
    public function getUnreadCount()
    {
        $count = Notification::unreadCountForUser(Auth::id());
        
        return response()->json(['count' => $count]);
    }

    // Lấy danh sách thông báo mới nhất (AJAX)
    public function getRecent()
    {
        $notifications = Notification::where('user_id', Auth::id())
                                     ->latest()
                                     ->limit(5)
                                     ->get();
        
        return response()->json($notifications);
    }

    // Đánh dấu một thông báo đã đọc
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
                                    ->findOrFail($id);
        
        $notification->markAsRead();
        
        if ($notification->link) {
            return redirect($notification->link);
        }
        
        return redirect()->route('notifications.index');
    }

    // Đánh dấu tất cả đã đọc
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
        
        return redirect()->back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc!');
    }

    // Xóa thông báo
    public function destroy($id)
    {
        $notification = Notification::where('user_id', Auth::id())
                                    ->findOrFail($id);
        
        $notification->delete();
        
        return redirect()->back()->with('success', 'Đã xóa thông báo!');
    }
}
