<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
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

        if ($notification->type === 'contact') {
            $link = (string) $notification->link;
            $isGenericNotificationLink = empty($link) || str_contains($link, '/thong-bao');

            if ($isGenericNotificationLink) {
                $user = Auth::user();
                $subject = null;

                if (preg_match('/"([^"]+)"/', (string) $notification->message, $matches)) {
                    $subject = $matches[1];
                }

                $contactQuery = Contact::where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->orWhere(function ($subQuery) use ($user) {
                            $subQuery->whereNull('user_id')
                                ->where('email', $user->email);
                        });
                })->whereNotNull('admin_reply');

                if ($subject) {
                    $contactQuery->where('subject', $subject);
                }

                $contact = $contactQuery->latest('replied_at')->first();

                if ($contact) {
                    return redirect()->route('account.contact-detail', $contact->id);
                }
            }
        }
        
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
