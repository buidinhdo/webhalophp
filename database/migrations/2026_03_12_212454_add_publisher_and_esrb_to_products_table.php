<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('publisher')->nullable()->after('genre')->comment('Nhà phát hành/sản xuất');
            $table->string('esrb_rating')->nullable()->after('publisher')->comment('Phân loại ESRB: E, E10+, T, M, AO, RP');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['publisher', 'esrb_rating']);
        });
    }
};
