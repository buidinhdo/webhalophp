<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Populate payment_method cho các orders hiện có
     */
    public function run(): void
    {
        $paymentMethods = [
            'cod' => 45,           // 45% COD (phổ biến nhất VN)
            'bank_transfer' => 30, // 30% Chuyển khoản
            'momo' => 15,          // 15% Ví MoMo
            'card' => 7,           // 7% Thẻ tín dụng
            'zalopay' => 3,        // 3% ZaloPay
        ];

        // Lấy tất cả orders chưa có payment_method
        $orders = Order::whereNull('payment_method')
            ->orWhere('payment_method', '')
            ->get();

        if ($orders->isEmpty()) {
            $this->command->info('✓ Tất cả orders đã có payment_method!');
            return;
        }

        $this->command->info("Đang cập nhật payment_method cho {$orders->count()} orders...");

        // Tạo distribution dựa trên tỷ lệ %
        $distribution = [];
        foreach ($paymentMethods as $method => $percentage) {
            $distribution = array_merge(
                $distribution, 
                array_fill(0, $percentage, $method)
            );
        }

        // Update từng order
        $updated = 0;
        foreach ($orders as $order) {
            $randomMethod = $distribution[array_rand($distribution)];
            
            $order->update([
                'payment_method' => $randomMethod,
                // Nếu là COD thì unpaid, còn lại là paid
                'payment_status' => $randomMethod === 'cod' ? 'unpaid' : 'paid'
            ]);
            
            $updated++;
        }

        $this->command->info("✓ Đã cập nhật payment_method cho {$updated} orders!");
        
        // Hiển thị thống kê
        $this->command->info("\n📊 Thống kê phương thức thanh toán:");
        $stats = Order::select('payment_method', DB::raw('COUNT(*) as count'))
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->orderByDesc('count')
            ->get();

        foreach ($stats as $stat) {
            $methodName = $this->getMethodName($stat->payment_method);
            $this->command->info("   {$methodName}: {$stat->count} đơn");
        }
    }

    /**
     * Get Vietnamese name for payment method
     */
    private function getMethodName($method)
    {
        $names = [
            'cod' => 'Tiền mặt (COD)',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'momo' => 'Ví MoMo',
            'card' => 'Thẻ tín dụng/ghi nợ',
            'zalopay' => 'ZaloPay',
        ];

        return $names[$method] ?? $method;
    }
}
