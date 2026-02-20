<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Tự động gán genre khi tạo sản phẩm mới
     */
    public function creating(Product $product)
    {
        $this->autoAssignGenre($product);
    }

    /**
     * Tự động gán genre khi cập nhật sản phẩm (nếu genre rỗng)
     */
    public function updating(Product $product)
    {
        if (empty($product->genre) || $product->genre === '') {
            $this->autoAssignGenre($product);
        }
    }

    /**
     * Tự động phát hiện và gán genre dựa trên tên sản phẩm
     */
    private function autoAssignGenre(Product $product)
    {
        // Nếu đã có genre thì không gán
        if (!empty($product->genre) && $product->genre !== '') {
            return;
        }

        $genreKeywords = [
            'Shooting' => ['shooting', 'fps', 'shooter', 'macross', 'call of duty', 'battlefield', 'warfare'],
            'Action' => ['action', 'adventure', 'combat'],
            'RPG' => ['rpg', 'role-playing', 'fantasy', 'quest'],
            'Racing' => ['racing', 'drive', 'forza', 'gran turismo', 'speed'],
            'Sports' => ['sports', 'fifa', 'nba', 'football', 'soccer', 'tennis'],
            'Fighting' => ['fighting', 'kombat', 'tekken', 'street fighter', 'brawl'],
            'Horror' => ['horror', 'resident evil', 'silent hill', 'fear', 'zombie'],
            'Strategy' => ['strategy', 'tactical', 'civilization', 'warcraft'],
            'Simulation' => ['simulator', 'simulation', 'sims'],
            'Puzzle' => ['puzzle', 'tetris', 'match'],
        ];

        $productName = strtolower($product->name);

        foreach ($genreKeywords as $genre => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($productName, strtolower($keyword))) {
                    $product->genre = $genre;
                    return;
                }
            }
        }

        // Mặc định là Action nếu không tìm thấy genre phù hợp
        $product->genre = 'Action';
    }
}
