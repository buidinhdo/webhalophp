<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Force update ALL order items to calculate total = price * quantity
        // This ensures all existing and future records have correct total
        DB::statement('UPDATE order_items SET total = CAST(price AS DECIMAL(15,2)) * CAST(quantity AS DECIMAL(15,2))');
        
        // Log the update
        $count = DB::table('order_items')->count();
        echo "Updated {$count} order items with correct total.\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data fix
    }
};
