<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_order_value',
        'max_discount',
        'usage_limit',
        'used_count',
        'usage_per_user',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Kiểm tra mã có hợp lệ không
    public function isValid($userId = null, $orderTotal = 0)
    {
        // Kiểm tra trạng thái active
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Mã giảm giá không hợp lệ'];
        }

        // Kiểm tra thời gian
        $now = Carbon::now();
        if ($this->start_date && $now->lt($this->start_date)) {
            return ['valid' => false, 'message' => 'Mã giảm giá chưa có hiệu lực'];
        }
        if ($this->end_date && $now->gt($this->end_date)) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết hạn'];
        }

        // Kiểm tra số lần sử dụng
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng'];
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($orderTotal < $this->min_order_value) {
            return ['valid' => false, 'message' => 'Đơn hàng tối thiểu ' . number_format($this->min_order_value, 0, ',', '.') . ' đ để sử dụng mã này'];
        }

        // Kiểm tra số lần sử dụng mỗi user (nếu có userId)
        if ($userId && $this->usage_per_user) {
            $userUsageCount = Order::where('user_id', $userId)
                ->where('coupon_id', $this->id)
                ->count();
            
            if ($userUsageCount >= $this->usage_per_user) {
                return ['valid' => false, 'message' => 'Bạn đã sử dụng hết lượt cho mã này'];
            }
        }

        return ['valid' => true, 'message' => 'Mã giảm giá hợp lệ'];
    }

    // Tính số tiền giảm
    public function calculateDiscount($orderTotal)
    {
        if ($this->type === 'percentage') {
            $discount = ($orderTotal * $this->value) / 100;
            
            // Áp dụng giảm tối đa nếu có
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            
            return $discount;
        } else {
            // Fixed amount
            return min($this->value, $orderTotal);
        }
    }

    // Tăng số lần sử dụng
    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
