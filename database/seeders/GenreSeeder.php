<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    public function run()
    {
        // Lấy tất cả genres unique từ products
        $genres = Product::whereNotNull('genre')
            ->where('genre', '!=', '')
            ->select('genre')
            ->groupBy('genre')
            ->orderBy('genre')
            ->pluck('genre');

        // Định nghĩa icon cho mỗi genre
        $genreIcons = [
            'Shooting' => 'fas fa-crosshairs',
            'Action' => 'fas fa-fist-raised',
            'Adventure' => 'fas fa-map',
            'RPG' => 'fas fa-hat-wizard',
            'Racing' => 'fas fa-car',
            'Sports' => 'fas fa-football-ball',
            'Fighting' => 'fas fa-dragon',
            'Horror' => 'fas fa-ghost',
            'Strategy' => 'fas fa-chess',
            'Simulation' => 'fas fa-plane',
            'Puzzle' => 'fas fa-puzzle-piece',
            'Platform' => 'fas fa-running',
        ];

        $order = 1;
        foreach ($genres as $genreName) {
            Genre::firstOrCreate(
                ['name' => $genreName],
                [
                    'slug' => \Illuminate\Support\Str::slug($genreName),
                    'icon' => $genreIcons[$genreName] ?? 'fas fa-gamepad',
                    'is_active' => true,
                    'order' => $order++,
                ]
            );
        }

        $this->command->info('✓ Đã tạo ' . $genres->count() . ' thể loại game từ dữ liệu products');
    }
}
