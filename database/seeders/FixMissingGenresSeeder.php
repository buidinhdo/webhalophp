<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class FixMissingGenresSeeder extends Seeder
{
    public function run()
    {
        // Cập nhật genre cho Game Macross
        Product::where('name', 'LIKE', '%Macross%')
            ->where(function($q) {
                $q->whereNull('genre')->orWhere('genre', '');
            })
            ->update(['genre' => 'Shooting']);

        // Tự động phát hiện và cập nhật genre dựa trên tên sản phẩm
        $genreKeywords = [
            'Shooting' => ['shooting', 'fps', 'shooter', 'macross', 'call of duty', 'battlefield'],
            'Action' => ['action', 'adventure'],
            'RPG' => ['rpg', 'role-playing'],
            'Racing' => ['racing', 'drive', 'forza', 'gran turismo'],
            'Sports' => ['sports', 'fifa', 'nba', 'football'],
            'Fighting' => ['fighting', 'kombat', 'tekken', 'street fighter'],
            'Horror' => ['horror', 'resident evil', 'silent hill'],
            'Strategy' => ['strategy', 'tactical'],
        ];

        foreach ($genreKeywords as $genre => $keywords) {
            foreach ($keywords as $keyword) {
                Product::where('name', 'LIKE', "%{$keyword}%")
                    ->where(function($q) {
                        $q->whereNull('genre')->orWhere('genre', '');
                    })
                    ->update(['genre' => $genre]);
            }
        }

        echo "Đã cập nhật genre cho các sản phẩm bị thiếu.\n";
    }
}
