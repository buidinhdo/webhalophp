<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Tự động gán genre khi tạo sản phẩm mới (chỉ khi genre rỗng)
     */
    public function creating(Product $product)
    {
        // Chỉ tự động gán nếu genre chưa được set
        if (empty($product->genre) || $product->genre === '') {
            $this->autoAssignGenre($product);
        }
    }

    /**
     * KHÔNG tự động gán genre khi cập nhật
     * Cho phép admin tự do chọn genre từ dropdown
     */
    public function updating(Product $product)
    {
        // Không làm gì cả - để admin tự quản lý genre
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

        // Lấy tất cả genres từ database
        $genres = \App\Models\Genre::active()->get();

        $productName = strtolower($product->name);

        // Kiểm tra từng genre xem có khớp với tên sản phẩm không
        foreach ($genres as $genre) {
            $genreName = strtolower($genre->name);
            if (str_contains($productName, $genreName)) {
                $product->genre = $genre->name;
                return;
            }
        }

        // Nếu không tìm thấy genre phù hợp, kiểm tra một số từ khóa phổ biến
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

        foreach ($genreKeywords as $genre => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($productName, strtolower($keyword))) {
                    // Kiểm tra xem genre này có tồn tại trong database không
                    $genreModel = \App\Models\Genre::where('name', $genre)->first();
                    if ($genreModel) {
                        $product->genre = $genreModel->name;
                        return;
                    }
                }
            }
        }

        // Mặc định là Action nếu không tìm thấy genre phù hợp
        $defaultGenre = \App\Models\Genre::where('name', 'Action')->first();
        if ($defaultGenre) {
            $product->genre = $defaultGenre->name;
        }
    }
}
