<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;

class WebsiteScoreController extends Controller
{
    public function index()
    {
        $criteria = $this->computeScore();
        $totalScore = array_sum(array_column($criteria, 'earned'));
        $maxScore = array_sum(array_column($criteria, 'max'));

        return view('admin.website-score.index', compact('criteria', 'totalScore', 'maxScore'));
    }

    private function computeScore(): array
    {
        $totalProducts = Product::count();

        // ---- 1. Chất lượng nội dung (Content Quality) – 25 pts ----
        $productsWithImages = $totalProducts > 0
            ? Product::whereNotNull('image')->where('image', '!=', '')->count()
            : 0;
        $productsWithDesc = $totalProducts > 0
            ? Product::whereNotNull('description')->where('description', '!=', '')->count()
            : 0;
        $productsWithShortDesc = $totalProducts > 0
            ? Product::whereNotNull('short_description')->where('short_description', '!=', '')->count()
            : 0;

        $imageRatio   = $totalProducts > 0 ? $productsWithImages   / $totalProducts : 0;
        $descRatio    = $totalProducts > 0 ? $productsWithDesc      / $totalProducts : 0;
        $shortDescRatio = $totalProducts > 0 ? $productsWithShortDesc / $totalProducts : 0;

        $contentScore = (int) round(($imageRatio * 10) + ($descRatio * 8) + ($shortDescRatio * 7));
        $contentScore = min($contentScore, 25);

        // ---- 2. SEO (25 pts) ----
        $productsWithSlugs = $totalProducts > 0
            ? Product::whereNotNull('slug')->where('slug', '!=', '')->count()
            : 0;
        $totalCategories = Category::count();
        $categoriesWithSlugs = $totalCategories > 0
            ? Category::whereNotNull('slug')->where('slug', '!=', '')->count()
            : 0;
        $activeCategories = Category::where('is_active', true)->count();

        $slugRatio       = $totalProducts > 0    ? $productsWithSlugs / $totalProducts : 0;
        $catSlugRatio    = $totalCategories > 0  ? $categoriesWithSlugs / $totalCategories : 0;
        $activeCatRatio  = $totalCategories > 0  ? $activeCategories / $totalCategories : 0;

        $seoScore = (int) round(($slugRatio * 10) + ($catSlugRatio * 8) + ($activeCatRatio * 7));
        $seoScore = min($seoScore, 25);

        // ---- 3. Tương tác khách hàng (Customer Engagement) – 25 pts ----
        $totalReviews      = Review::where('status', 'approved')->count();
        $avgRating         = Review::where('status', 'approved')->avg('rating') ?? 0;
        $totalCompletedOrders = Order::where('order_status', 'completed')->count();
        $totalCustomers    = User::where('is_admin', 0)->count();

        // Scale: >=20 reviews → 8pts, >=10 → 5pts, >=1 → 2pts
        $reviewPts = $totalReviews >= 20 ? 8 : ($totalReviews >= 10 ? 5 : ($totalReviews >= 1 ? 2 : 0));
        // Avg rating (out of 5) → up to 8 pts
        $ratingPts = (int) round(($avgRating / 5) * 8);
        // Completed orders >= 50 → 5pts, >= 10 → 3pts, >= 1 → 1pt
        $orderPts  = $totalCompletedOrders >= 50 ? 5 : ($totalCompletedOrders >= 10 ? 3 : ($totalCompletedOrders >= 1 ? 1 : 0));
        // Customers >= 20 → 4pts, >= 5 → 2pts, >= 1 → 1pt
        $custPts   = $totalCustomers >= 20 ? 4 : ($totalCustomers >= 5 ? 2 : ($totalCustomers >= 1 ? 1 : 0));

        $engagementScore = min($reviewPts + $ratingPts + $orderPts + $custPts, 25);

        // ---- 4. Thiết lập cửa hàng (Store Setup) – 25 pts ----
        $activeBanners   = Banner::where('is_active', true)->count();
        $featuredProducts = Product::where('is_featured', true)->where('status', 'active')->count();
        $inStockProducts = Product::where('stock', '>', 0)->where('status', 'active')->count();
        $totalActiveProducts = Product::where('status', 'active')->count();

        // Banners >= 3 → 7pts, >= 1 → 4pts
        $bannerPts  = $activeBanners >= 3 ? 7 : ($activeBanners >= 1 ? 4 : 0);
        // Featured >= 5 → 6pts, >= 1 → 3pts
        $featuredPts = $featuredProducts >= 5 ? 6 : ($featuredProducts >= 1 ? 3 : 0);
        // Stock ratio → up to 6 pts
        $stockRatio  = $totalActiveProducts > 0 ? $inStockProducts / $totalActiveProducts : 0;
        $stockPts    = (int) round($stockRatio * 6);
        // Products >= 20 → 6pts, >= 10 → 4pts, >= 1 → 2pts
        $prodCountPts = $totalActiveProducts >= 20 ? 6 : ($totalActiveProducts >= 10 ? 4 : ($totalActiveProducts >= 1 ? 2 : 0));

        $setupScore = min($bannerPts + $featuredPts + $stockPts + $prodCountPts, 25);

        return [
            'content' => [
                'label'       => 'Chất lượng nội dung',
                'icon'        => 'fas fa-file-alt',
                'color'       => 'info',
                'earned'      => $contentScore,
                'max'         => 25,
                'details'     => [
                    [
                        'name'   => 'Sản phẩm có ảnh',
                        'value'  => $productsWithImages . ' / ' . $totalProducts,
                        'passed' => $imageRatio >= 0.8,
                        'hint'   => 'Tải ảnh cho tất cả sản phẩm để tăng tỷ lệ chuyển đổi.',
                    ],
                    [
                        'name'   => 'Sản phẩm có mô tả chi tiết',
                        'value'  => $productsWithDesc . ' / ' . $totalProducts,
                        'passed' => $descRatio >= 0.8,
                        'hint'   => 'Thêm mô tả đầy đủ để cải thiện SEO và trải nghiệm người dùng.',
                    ],
                    [
                        'name'   => 'Sản phẩm có mô tả ngắn',
                        'value'  => $productsWithShortDesc . ' / ' . $totalProducts,
                        'passed' => $shortDescRatio >= 0.8,
                        'hint'   => 'Mô tả ngắn giúp hiển thị snippet trên trang danh sách.',
                    ],
                ],
            ],
            'seo' => [
                'label'       => 'SEO & Cấu trúc',
                'icon'        => 'fas fa-search',
                'color'       => 'success',
                'earned'      => $seoScore,
                'max'         => 25,
                'details'     => [
                    [
                        'name'   => 'Sản phẩm có slug SEO',
                        'value'  => $productsWithSlugs . ' / ' . $totalProducts,
                        'passed' => $slugRatio >= 1.0,
                        'hint'   => 'URL thân thiện giúp Google index tốt hơn.',
                    ],
                    [
                        'name'   => 'Danh mục có slug',
                        'value'  => $categoriesWithSlugs . ' / ' . $totalCategories,
                        'passed' => $catSlugRatio >= 1.0,
                        'hint'   => 'Tất cả danh mục cần có slug để điều hướng đúng.',
                    ],
                    [
                        'name'   => 'Danh mục đang hoạt động',
                        'value'  => $activeCategories . ' / ' . $totalCategories,
                        'passed' => $activeCatRatio >= 0.8,
                        'hint'   => 'Kích hoạt đủ danh mục để tổ chức sản phẩm hiệu quả.',
                    ],
                ],
            ],
            'engagement' => [
                'label'       => 'Tương tác khách hàng',
                'icon'        => 'fas fa-star',
                'color'       => 'warning',
                'earned'      => $engagementScore,
                'max'         => 25,
                'details'     => [
                    [
                        'name'   => 'Số lượt đánh giá (đã duyệt)',
                        'value'  => $totalReviews . ' đánh giá',
                        'passed' => $totalReviews >= 10,
                        'hint'   => 'Khuyến khích khách hàng viết đánh giá sau khi mua.',
                    ],
                    [
                        'name'   => 'Điểm đánh giá trung bình',
                        'value'  => number_format($avgRating, 1) . ' / 5.0 ⭐',
                        'passed' => $avgRating >= 4.0,
                        'hint'   => 'Duy trì chất lượng sản phẩm và dịch vụ để giữ điểm cao.',
                    ],
                    [
                        'name'   => 'Đơn hàng hoàn thành',
                        'value'  => $totalCompletedOrders . ' đơn',
                        'passed' => $totalCompletedOrders >= 10,
                        'hint'   => 'Tăng tỷ lệ hoàn thành đơn hàng.',
                    ],
                    [
                        'name'   => 'Số tài khoản khách hàng',
                        'value'  => $totalCustomers . ' tài khoản',
                        'passed' => $totalCustomers >= 5,
                        'hint'   => 'Khuyến khích khách hàng tạo tài khoản để theo dõi đơn hàng.',
                    ],
                ],
            ],
            'setup' => [
                'label'       => 'Thiết lập cửa hàng',
                'icon'        => 'fas fa-store',
                'color'       => 'danger',
                'earned'      => $setupScore,
                'max'         => 25,
                'details'     => [
                    [
                        'name'   => 'Banner đang hiển thị',
                        'value'  => $activeBanners . ' banner',
                        'passed' => $activeBanners >= 3,
                        'hint'   => 'Thêm ít nhất 3 banner để trang chủ hấp dẫn hơn.',
                    ],
                    [
                        'name'   => 'Sản phẩm nổi bật (featured)',
                        'value'  => $featuredProducts . ' sản phẩm',
                        'passed' => $featuredProducts >= 5,
                        'hint'   => 'Đánh dấu ít nhất 5 sản phẩm nổi bật để hiển thị trên trang chủ.',
                    ],
                    [
                        'name'   => 'Sản phẩm còn hàng',
                        'value'  => $inStockProducts . ' / ' . $totalActiveProducts,
                        'passed' => $stockRatio >= 0.8,
                        'hint'   => 'Cập nhật tồn kho thường xuyên để tránh trường hợp bán hàng hết.',
                    ],
                    [
                        'name'   => 'Tổng sản phẩm đang bán',
                        'value'  => $totalActiveProducts . ' sản phẩm',
                        'passed' => $totalActiveProducts >= 20,
                        'hint'   => 'Thêm nhiều sản phẩm để tăng lựa chọn cho khách hàng.',
                    ],
                ],
            ],
        ];
    }
}
