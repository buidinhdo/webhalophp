<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'session_id',
        'type',
        'message',
        'product_id',
        'user_id',
        'is_read'
    ];
    
    protected $casts = [
        'is_read' => 'boolean',
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
