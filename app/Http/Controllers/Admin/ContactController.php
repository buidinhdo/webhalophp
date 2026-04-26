<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(20);
        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    public function reply(Request $request, Contact $contact)
    {
        $request->validate([
            'admin_reply' => 'required|string',
        ]);

        $contact->update([
            'admin_reply' => $request->admin_reply,
            'status' => 'replied',
            'replied_at' => now(),
        ]);

        $targetUserId = $contact->user_id;

        if (!$targetUserId) {
            $targetUserId = User::where('email', $contact->email)->value('id');

            if ($targetUserId) {
                $contact->update(['user_id' => $targetUserId]);
            }
        }

        if ($targetUserId) {
            Notification::create([
                'user_id' => $targetUserId,
                'type' => 'contact',
                'title' => 'Phản hồi liên hệ mới',
                'message' => 'Admin đã phản hồi liên hệ: "' . $contact->subject . '".',
                'link' => route('account.contact-detail', $contact->id, false),
                'is_read' => false,
            ]);
        }

        return redirect()->route('admin.contacts.show', $contact)
            ->with('success', 'Đã gửi phản hồi thành công!');
    }

    public function updateStatus(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,replied,closed',
        ]);

        $contact->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Đã cập nhật trạng thái!');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        
        return redirect()->route('admin.contacts.index')
            ->with('success', 'Đã xóa liên hệ!');
    }
}
