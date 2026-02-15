<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UpdatePaymentMethods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-payment-methods';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật payment_method cho tất cả orders chưa có dữ liệu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Bắt đầu cập nhật payment methods cho orders...');
        $this->newLine();

        // Run seeder
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\PaymentMethodSeeder'
        ]);

        // Get output from seeder
        $this->line(Artisan::output());

        $this->newLine();
        $this->info('✅ Hoàn tất!');
        
        return Command::SUCCESS;
    }
}
