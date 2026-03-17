<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Nhập dữ liệu từ cột publisher hiện có trong products
        $existing = DB::table('products')
            ->whereNotNull('publisher')
            ->where('publisher', '!=', '')
            ->distinct()
            ->pluck('publisher');

        foreach ($existing as $name) {
            DB::table('publishers')->insertOrIgnore([
                'name'       => $name,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('publishers');
    }
};
