<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Tạo dữ liệu mẫu cho orders với phân bố realistic
     */
    public function run(): void
    {
        $this->command->info('🛒 Bắt đầu tạo dữ liệu orders mẫu...');

        // Payment methods distribution (%)
        $paymentMethods = [
            'cod' => 45,           // 45% COD
            'bank_transfer' => 30, // 30% Chuyển khoản
            'momo' => 15,          // 15% MoMo
            'card' => 7,           // 7% Card
            'zalopay' => 3,        // 3% ZaloPay
        ];

        // Order statuses distribution
        $statuses = [
            'completed' => 60,   // 60% hoàn thành
            'shipping' => 15,    // 15% đang giao
            'processing' => 10,  // 10% đang xử lý
            'pending' => 10,     // 10% chờ xác nhận
            'cancelled' => 5,    // 5% đã hủy
        ];

        // Lấy users và products
        $users = User::where('is_admin', 0)->get();
        $products = Product::all();

        if ($users->isEmpty()) {
            $this->command->warn('⚠️  Không có user nào! Tạo user mẫu trước.');
            return;
        }

        if ($products->isEmpty()) {
            $this->command->warn('⚠️  Không có product nào! Tạo product mẫu trước.');
            return;
        }

        // Tạo distribution arrays
        $paymentDistribution = $this->createDistribution($paymentMethods);
        $statusDistribution = $this->createDistribution($statuses);

        // Tạo orders cho 12 tháng gần đây
        $totalOrders = 0;
        $totalRevenue = 0;

        for ($month = 11; $month >= 0; $month--) {
            $date = now()->subMonths($month);
            
            // Số đơn hàng theo tháng (biến động realistic)
            $ordersThisMonth = $this->getOrdersCountForMonth($date);

            for ($i = 0; $i < $ordersThisMonth; $i++) {
                // Random ngày trong tháng
                $orderDate = $date->copy()
                    ->startOfMonth()
                    ->addDays(rand(0, $date->daysInMonth - 1))
                    ->addHours(rand(0, 23))
                    ->addMinutes(rand(0, 59));

                // Random user
                $user = $users->random();

                // Random payment method và status
                $paymentMethod = $paymentDistribution[array_rand($paymentDistribution)];
                $orderStatus = $statusDistribution[array_rand($statusDistribution)];

                // COD thường unpaid, còn lại paid
                $paymentStatus = ($paymentMethod === 'cod' && $orderStatus !== 'completed') 
                    ? 'unpaid' 
                    : 'paid';

                // Tạo order
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => 'ORD-' . strtoupper(uniqid()),
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'customer_phone' => $user->phone ?? '0' . rand(300000000, 999999999),
                    'customer_address' => $user->address ?? $this->randomAddress(),
                    'total_amount' => 0, // Tính sau
                    'payment_method' => $paymentMethod,
                    'payment_status' => $paymentStatus,
                    'order_status' => $orderStatus,
                    'notes' => rand(0, 100) < 20 ? $this->randomNote() : null,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate->copy()->addHours(rand(1, 48)),
                ]);

                // Thêm order items (1-5 sản phẩm mỗi đơn)
                $itemCount = rand(1, 5);
                $orderTotal = 0;

                $selectedProducts = $products->random(min($itemCount, $products->count()));

                foreach ($selectedProducts as $product) {
                    $quantity = rand(1, 3);
                    $price = $product->sale_price ?? $product->price;
                    $subtotal = $price * $quantity;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $price,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ]);

                    $orderTotal += $subtotal;
                }

                // Update order total
                $order->update(['total_amount' => $orderTotal]);
                
                $totalOrders++;
                if ($orderStatus === 'completed') {
                    $totalRevenue += $orderTotal;
                }
            }

            $this->command->info("✓ Tháng {$date->format('m/Y')}: {$ordersThisMonth} đơn hàng");
        }

        $this->command->info("\n✅ Đã tạo {$totalOrders} orders!");
        $this->command->info("💰 Tổng doanh thu (completed): " . number_format($totalRevenue) . "₫");

        // Thống kê
        $this->showStats();
    }

    /**
     * Create distribution array based on percentages
     */
    private function createDistribution($items)
    {
        $distribution = [];
        foreach ($items as $item => $percentage) {
            $distribution = array_merge(
                $distribution, 
                array_fill(0, $percentage, $item)
            );
        }
        return $distribution;
    }

    /**
     * Get realistic order count for month
     */
    private function getOrdersCountForMonth($date)
    {
        $month = $date->month;
        
        // Tháng 1-2/2026: Tăng mạnh (gần đây)
        if ($date->year == 2026 && in_array($month, [1, 2])) {
            return rand(40, 60);
        }
        
        // Tháng 12/2025: Cao (mùa Noel)
        if ($date->year == 2025 && $month == 12) {
            return rand(35, 50);
        }
        
        // Tháng thường
        return rand(15, 30);
    }

    /**
     * Random Vietnamese address
     */
    private function randomAddress()
    {
        $streets = ['Lê Lợi', 'Nguyễn Huệ', 'Trần Hưng Đạo', 'Hai Bà Trưng', 'Lý Tự Trọng', 'Võ Văn Tần'];
        $districts = ['Quận 1', 'Quận 3', 'Quận 5', 'Quận 10', 'Bình Thạnh', 'Tân Bình', 'Phú Nhuận'];
        $cities = ['TP Hồ Chí Minh', 'Hà Nội', 'Đà Nẵng', 'Cần Thơ'];
        
        return rand(1, 999) . ' ' . $streets[array_rand($streets)] . ', ' . 
               $districts[array_rand($districts)] . ', ' . 
               $cities[array_rand($cities)];
    }

    /**
     * Random order note
     */
    private function randomNote()
    {
        $notes = [
            'Giao hàng giờ hành chính',
            'Gọi trước khi giao',
            'Giao tận tay, không giao cho bảo vệ',
            'Ship COD',
            'Gói kỹ giúp em',
            null
        ];
        return $notes[array_rand($notes)];
    }

    /**
     * Show statistics
     */
    private function showStats()
    {
        $this->command->info("\n📊 Thống kê chi tiết:");
        
        // Payment methods
        $this->command->info("\n💳 Phương thức thanh toán:");
        $paymentStats = DB::table('orders')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as revenue'))
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->orderByDesc('count')
            ->get();

        foreach ($paymentStats as $stat) {
            $method = $this->getMethodName($stat->payment_method);
            $this->command->line("   {$method}: {$stat->count} đơn - " . number_format($stat->revenue) . "₫");
        }

        // Order statuses
        $this->command->info("\n📦 Trạng thái đơn hàng:");
        $statusStats = DB::table('orders')
            ->select('order_status', DB::raw('COUNT(*) as count'))
            ->groupBy('order_status')
            ->orderByDesc('count')
            ->get();

        foreach ($statusStats as $stat) {
            $status = $this->getStatusName($stat->order_status);
            $this->command->line("   {$status}: {$stat->count} đơn");
        }
    }

    private function getMethodName($method)
    {
        $names = [
            'cod' => 'Tiền mặt (COD)',
            'bank_transfer' => 'Chuyển khoản',
            'momo' => 'Ví MoMo',
            'card' => 'Thẻ tín dụng',
            'zalopay' => 'ZaloPay',
        ];
        return $names[$method] ?? $method;
    }

    private function getStatusName($status)
    {
        $names = [
            'pending' => 'Chờ xác nhận',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];
        return $names[$status] ?? $status;
    }
}
