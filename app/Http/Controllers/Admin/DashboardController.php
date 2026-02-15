<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ========== THỐNG KÊ TỔNG QUAN ==========
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCustomers = User::where('is_admin', 0)->count();
        $totalRevenue = Order::where('order_status', 'completed')->sum('total_amount');
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $todayOrders = Order::whereDate('created_at', today())->count();
        
        // Doanh thu tháng này
        $monthlyRevenue = Order::where('order_status', 'completed')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('total_amount');
        
        // Doanh thu tháng trước
        $lastMonthRevenue = Order::where('order_status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount');
        
        // ========== THỐNG KÊ NÂNG CAO ==========
        
        // 1. Giá trị đơn hàng trung bình (AOV)
        $avgOrderValue = Order::where('order_status', 'completed')->avg('total_amount') ?? 0;
        
        // 2. Số lượng sản phẩm trung bình mỗi đơn
        $avgProductsPerOrder = OrderItem::selectRaw('AVG(quantity) as avg_qty')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.order_status', '!=', 'cancelled')
            ->value('avg_qty') ?? 0;
        
        // 3. Tỷ lệ khách hàng quay lại
        $repeatCustomers = User::whereHas('orders', function($q) {
            $q->select('user_id')
              ->groupBy('user_id')
              ->havingRaw('COUNT(*) > 1');
        })->count();
        $repeatCustomerRate = $totalCustomers > 0 ? ($repeatCustomers / $totalCustomers) * 100 : 0;
        
        // 4. Thời gian xử lý đơn trung bình (từ pending → completed, tính bằng giờ)
        $avgProcessingTime = Order::where('order_status', 'completed')
            ->whereNotNull('updated_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
            ->value('avg_hours') ?? 0;
        
        // 5. Tỷ lệ hủy đơn
        $cancelledOrders = Order::where('order_status', 'cancelled')->count();
        $cancellationRate = $totalOrders > 0 ? ($cancelledOrders / $totalOrders) * 100 : 0;
        
        // 6. Tỷ lệ chuyển đổi (estimated - tổng khách vs đơn hàng)
        $conversionRate = $totalCustomers > 0 ? ($totalOrders / $totalCustomers) * 100 : 0;
        
        // ========== SO SÁNH THÁNG NÀY VS THÁNG TRƯỚC ==========
        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : ($monthlyRevenue > 0 ? 100 : 0);
        
        $thisMonthOrders = Order::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))->count();
        $lastMonthOrders = Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)->count();
        $ordersGrowth = $lastMonthOrders > 0 
            ? (($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 
            : ($thisMonthOrders > 0 ? 100 : 0);
        
        // ========== THỐNG KÊ KHÁCH HÀNG ==========
        
        // Top 10 khách hàng VIP
        $vipCustomers = User::select('users.*', DB::raw('SUM(orders.total_amount) as total_spent'), DB::raw('COUNT(orders.id) as order_count'))
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->where('orders.order_status', 'completed')
            ->groupBy('users.id')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();
        
        // Khách hàng mới (tháng này)
        $newCustomers = User::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->where('is_admin', 0)
            ->count();
        
        // Khách hàng không hoạt động (>90 ngày không đặt hàng)
        $inactiveCustomers = User::where('is_admin', 0)
            ->whereDoesntHave('orders', function($q) {
                $q->where('created_at', '>=', now()->subDays(90));
            })
            ->whereHas('orders') // Có ít nhất 1 đơn
            ->count();
        
        // ========== THỐNG KÊ SẢN PHẨM ==========
        
        // Top sản phẩm bán chạy
        $topProducts = Product::select('products.*')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.order_status', ['completed', 'shipping', 'processing'])
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
        
        // Sản phẩm có doanh thu cao nhất
        $topRevenueProducts = Product::select('products.*')
            ->selectRaw('SUM(order_items.quantity * order_items.price) as product_revenue')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.order_status', 'completed')
            ->groupBy('products.id')
            ->orderByDesc('product_revenue')
            ->limit(5)
            ->get();
        
        // Sản phẩm sắp hết hàng (stock < 10)
        $lowStockProducts = Product::where('stock', '<', 10)
            ->where('stock', '>', 0)
            ->orderBy('stock')
            ->limit(10)
            ->get();
        
        // Top sản phẩm được yêu thích (từ Wishlist)
        $topWishlistProducts = Product::select('products.*', DB::raw('COUNT(wishlists.id) as wishlist_count'))
            ->join('wishlists', 'products.id', '=', 'wishlists.product_id')
            ->groupBy('products.id')
            ->orderByDesc('wishlist_count')
            ->limit(10)
            ->get();
        
        // Sản phẩm mới hiệu quả (30 ngày đầu)
        $newProductsPerformance = Product::select('products.*')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as sold_count')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->where('products.created_at', '>=', now()->subDays(30))
            ->groupBy('products.id')
            ->orderByDesc('sold_count')
            ->limit(5)
            ->get();
        
        // ========== BIỂU ĐỒ DỮ LIỆU ==========
        
        // Biểu đồ doanh thu 7 ngày
        $chartData = $this->getRevenueChartData(7);
        
        // Doanh thu theo giờ trong ngày (24 giờ)
        $revenueByHour = $this->getRevenueByHour();
        
        // Doanh thu theo ngày trong tuần
        $revenueByWeekday = $this->getRevenueByWeekday();
        
        // Doanh thu theo tháng (12 tháng)
        $revenueByMonth = $this->getRevenueByMonth();
        
        // Top danh mục
        $topCategories = DB::table('categories')
            ->select('categories.name', DB::raw('SUM(order_items.quantity * order_items.price) as revenue'))
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.order_status', 'completed')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();
        
        // Doanh thu theo trạng thái
        $revenueByStatus = [
            'pending' => Order::where('order_status', 'pending')->sum('total_amount'),
            'processing' => Order::where('order_status', 'processing')->sum('total_amount'),
            'shipping' => Order::where('order_status', 'shipping')->sum('total_amount'),
            'completed' => Order::where('order_status', 'completed')->sum('total_amount'),
            'cancelled' => Order::where('order_status', 'cancelled')->sum('total_amount'),
        ];
        
        // Doanh thu theo nền tảng (platform)
        $revenueByPlatform = Product::select('products.platform', DB::raw('SUM(order_items.quantity * order_items.price) as revenue'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.order_status', 'completed')
            ->whereNotNull('products.platform')
            ->groupBy('products.platform')
            ->orderByDesc('revenue')
            ->get();
        
        // Doanh thu theo phương thức thanh toán
        $revenueByPaymentMethod = Order::select('payment_method', DB::raw('SUM(total_amount) as revenue'))
            ->where('order_status', 'completed')
            ->groupBy('payment_method')
            ->orderByDesc('revenue')
            ->get();
        
        // Đơn hàng gần đây
        $recentOrders = Order::latest()->limit(10)->get();
        
        return view('admin.dashboard', compact(
            // Tổng quan
            'totalProducts', 'totalOrders', 'totalCustomers', 'totalRevenue',
            'pendingOrders', 'todayOrders', 'monthlyRevenue',
            
            // Thống kê nâng cao
            'avgOrderValue', 'avgProductsPerOrder', 'repeatCustomerRate',
            'avgProcessingTime', 'cancellationRate', 'conversionRate',
            
            // So sánh tăng trưởng
            'revenueGrowth', 'ordersGrowth', 'lastMonthRevenue',
            
            // Khách hàng
            'vipCustomers', 'newCustomers', 'inactiveCustomers',
            
            // Sản phẩm
            'topProducts', 'topRevenueProducts', 'lowStockProducts',
            'topWishlistProducts', 'newProductsPerformance',
            
            // Biểu đồ
            'chartData', 'revenueByHour', 'revenueByWeekday', 'revenueByMonth',
            'topCategories', 'revenueByStatus', 'revenueByPlatform',
            'revenueByPaymentMethod',
            
            // Khác
            'recentOrders'
        ));
    }
    
    public function getRevenueChartData($days = 7)
    {
        $labels = [];
        $revenues = [];
        $orderCounts = [];
        $customerCounts = [];
        $percentChanges = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $previousDate = now()->subDays($i + 1);
            
            $labels[] = $date->format('d/m');
            
            $dayRevenue = Order::where('order_status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total_amount');
            $revenues[] = $dayRevenue;
            
            $dayOrderCount = Order::whereDate('created_at', $date)->count();
            $orderCounts[] = $dayOrderCount;
            
            // Đếm số khách hàng mới đăng ký trong ngày
            $dayCustomerCount = User::whereDate('created_at', $date)->count();
            $customerCounts[] = $dayCustomerCount;
            
            // Tính phần trăm thay đổi so với ngày hôm trước
            $previousRevenue = Order::where('order_status', 'completed')
                ->whereDate('created_at', $previousDate)
                ->sum('total_amount');
            
            if ($previousRevenue > 0) {
                $percentChange = (($dayRevenue - $previousRevenue) / $previousRevenue) * 100;
                $percentChanges[] = round($percentChange, 1);
            } else {
                $percentChanges[] = $dayRevenue > 0 ? 100 : 0;
            }
        }
        
        return [
            'labels' => $labels,
            'revenues' => $revenues,
            'orderCounts' => $orderCounts,
            'customerCounts' => $customerCounts,
            'percentChanges' => $percentChanges
        ];
    }
    
    public function filterRevenueChart(Request $request)
    {
        $days = $request->input('days', 7);
        $chartData = $this->getRevenueChartData($days);
        
        return response()->json($chartData);
    }
    
    public function exportRevenue(Request $request)
    {
        $days = $request->input('days', 7);
        $chartData = $this->getRevenueChartData($days);
        
        $filename = 'doanh_thu_' . $days . '_ngay_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // UTF-8 BOM for Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Header
        fputcsv($output, ['Ngày', 'Doanh thu (VNĐ)', 'Số đơn hàng', 'Số khách hàng', 'Thay đổi (%)']);
        
        // Data
        foreach ($chartData['labels'] as $index => $label) {
            fputcsv($output, [
                $label,
                number_format($chartData['revenues'][$index], 0, ',', '.'),
                $chartData['orderCounts'][$index],
                $chartData['customerCounts'][$index],
                $chartData['percentChanges'][$index] . '%'
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    // ========== HELPER METHODS CHO BIỂU ĐỒ MỚI ==========
    
    /**
     * Doanh thu theo giờ trong ngày (0-23h)
     */
    public function getRevenueByHour()
    {
        $hours = [];
        $revenues = [];
        
        for ($h = 0; $h < 24; $h++) {
            $hours[] = sprintf('%02d:00', $h);
            
            $hourRevenue = Order::where('order_status', 'completed')
                ->whereRaw('HOUR(created_at) = ?', [$h])
                ->sum('total_amount');
            
            $revenues[] = $hourRevenue;
        }
        
        return [
            'labels' => $hours,
            'revenues' => $revenues
        ];
    }
    
    /**
     * Doanh thu theo ngày trong tuần (Thứ 2 - CN)
     */
    public function getRevenueByWeekday()
    {
        $weekdays = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];
        $revenues = [];
        
        for ($day = 1; $day <= 7; $day++) {
            $dayRevenue = Order::where('order_status', 'completed')
                ->whereRaw('DAYOFWEEK(created_at) = ?', [$day == 7 ? 1 : $day + 1]) // MySQL: 1=Sunday
                ->sum('total_amount');
            
            $revenues[] = $dayRevenue;
        }
        
        return [
            'labels' => $weekdays,
            'revenues' => $revenues
        ];
    }
    
    /**
     * Doanh thu theo tháng (12 tháng gần nhất)
     */
    public function getRevenueByMonth()
    {
        $labels = [];
        $revenues = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            $monthRevenue = Order::where('order_status', 'completed')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_amount');
            
            $revenues[] = $monthRevenue;
        }
        
        return [
            'labels' => $labels,
            'revenues' => $revenues
        ];
    }
}
