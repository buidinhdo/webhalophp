<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Update orders table để phù hợp với Order model
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kiểm tra và rename columns nếu cần
            if (Schema::hasColumn('orders', 'total') && !Schema::hasColumn('orders', 'total_amount')) {
                $table->renameColumn('total', 'total_amount');
            }
            
            if (Schema::hasColumn('orders', 'status') && !Schema::hasColumn('orders', 'order_status')) {
                $table->renameColumn('status', 'order_status');
            }
            
            // Đảm bảo các cột cần thiết tồn tại
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('user_id');
            }
            
            if (!Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_name');
            }
            
            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_email');
            }
            
            if (!Schema::hasColumn('orders', 'customer_address')) {
                $table->text('customer_address')->nullable()->after('customer_phone');
            }
            
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rollback changes
            if (Schema::hasColumn('orders', 'total_amount')) {
                $table->renameColumn('total_amount', 'total');
            }
            
            if (Schema::hasColumn('orders', 'order_status')) {
                $table->renameColumn('order_status', 'status');
            }
            
            $table->dropColumn([
                'customer_name',
                'customer_email', 
                'customer_phone',
                'customer_address',
                'notes'
            ]);
        });
    }
};
