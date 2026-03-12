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
            $table->string('game_mode')->nullable()->after('genre')->comment('Chế độ chơi: Single Player, Multiplayer, Co-op, Online');
            $table->string('language')->nullable()->after('game_mode')->comment('Ngôn ngữ: Vietnamese, English, Japanese, Multi-language');
            $table->string('age_rating')->nullable()->after('language')->comment('Độ tuổi: E, E10+, T, M, AO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['game_mode', 'language', 'age_rating']);
        });
    }
};
