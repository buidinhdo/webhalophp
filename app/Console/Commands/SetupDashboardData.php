<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SetupDashboardData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:setup-data {--fresh : Xóa dữ liệu cũ và tạo mới}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup dữ liệu mẫu cho Dashboard (orders, payment methods, etc.)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('╔════════════════════════════════════════╗');
        $this->info('║   SETUP DASHBOARD DATA - HALOSHOP     ║');
        $this->info('╚════════════════════════════════════════╝');
        $this->newLine();

        // Kiểm tra fresh option
        if ($this->option('fresh')) {
            if ($this->confirm('⚠️  Bạn có chắc muốn XÓA TẤT CẢ dữ liệu orders cũ?', false)) {
                $this->info('🗑️  Đang xóa dữ liệu cũ...');
                DB::table('order_items')->delete();
                DB::table('orders')->delete();
                $this->info('✓ Đã xóa dữ liệu cũ!');
                $this->newLine();
            } else {
                $this->warn('❌ Hủy bỏ!');
                return Command::FAILURE;
            }
        }

        // Step 1: Run migration
        $this->info('📋 Step 1: Chạy migration để cập nhật orders table...');
        Artisan::call('migrate', ['--force' => true]);
        $this->line(Artisan::output());

        // Step 2: Seed orders
        $ordersCount = DB::table('orders')->count();
        
        if ($ordersCount == 0 || $this->option('fresh')) {
            $this->info('📦 Step 2: Tạo dữ liệu orders mẫu...');
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\OrderSeeder']);
            $this->line(Artisan::output());
        } else {
            $this->info("📦 Step 2: Đã có {$ordersCount} orders, bỏ qua việc tạo mới.");
            $this->info('         (Dùng --fresh để xóa và tạo lại)');
        }

        // Step 3: Update payment methods
        $this->newLine();
        $this->info('💳 Step 3: Cập nhật payment methods cho tất cả orders...');
        Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\PaymentMethodSeeder']);
        $this->line(Artisan::output());

        // Show final stats
        $this->newLine();
        $this->showFinalStats();

        $this->newLine();
        $this->info('✅ HOÀN TẤT! Dashboard đã sẵn sàng với dữ liệu đầy đủ.');
        $this->info('🌐 Truy cập: /admin/dashboard để xem thống kê');

        return Command::SUCCESS;
    }

    /**
     * Show final statistics
     */
    private function showFinalStats()
    {
        $this->info('═══════════════════════════════════════');
        $this->info('📊 THỐNG KÊ TỔNG QUAN');
        $this->info('═══════════════════════════════════════');

        // Total orders
        $totalOrders = DB::table('orders')->count();
        $this->line("📦 Tổng đơn hàng: {$totalOrders}");

        // Revenue
        $revenue = DB::table('orders')
            ->where('order_status', 'completed')
            ->sum('total_amount');
        $this->line("💰 Doanh thu (completed): " . number_format($revenue) . "₫");

        // By status
        $this->newLine();
        $this->line("📋 Theo trạng thái:");
        $statuses = DB::table('orders')
            ->select('order_status', DB::raw('COUNT(*) as count'))
            ->groupBy('order_status')
            ->get();
        
        foreach ($statuses as $status) {
            $emoji = $this->getStatusEmoji($status->order_status);
            $this->line("   {$emoji} {$status->order_status}: {$status->count}");
        }

        // By payment method
        $this->newLine();
        $this->line("💳 Theo phương thức thanh toán:");
        $payments = DB::table('orders')
            ->select('payment_method', DB::raw('COUNT(*) as count'))
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->orderByDesc('count')
            ->get();
        
        foreach ($payments as $payment) {
            $emoji = $this->getPaymentEmoji($payment->payment_method);
            $this->line("   {$emoji} {$payment->payment_method}: {$payment->count}");
        }

        $this->info('═══════════════════════════════════════');
    }

    private function getStatusEmoji($status)
    {
        return match($status) {
            'completed' => '✅',
            'shipping' => '🚚',
            'processing' => '⚙️',
            'pending' => '⏳',
            'cancelled' => '❌',
            default => '📦'
        };
    }

    private function getPaymentEmoji($method)
    {
        return match($method) {
            'cod' => '💵',
            'bank_transfer' => '🏦',
            'momo' => '📱',
            'card' => '💳',
            'zalopay' => '💰',
            default => '💳'
        };
    }
}
