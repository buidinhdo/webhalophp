<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Đánh dấu đã đọc
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    // Lấy thông báo chưa đọc của user
    public static function unreadForUser($userId)
    {
        return static::where('user_id', $userId)
                     ->where('is_read', false)
                     ->latest()
                     ->get();
    }

    // Đếm số thông báo chưa đọc
    public static function unreadCountForUser($userId)
    {
        return static::where('user_id', $userId)
                     ->where('is_read', false)
                     ->count();
    }
}
