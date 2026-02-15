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
        
        // So sánh khách hàng tháng này vs tháng trước
        $thisMonthCustomers = User::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->where('is_admin', 0)
            ->count();
        $lastMonthCustomers = User::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->where('is_admin', 0)
            ->count();
        $customersGrowth = $lastMonthCustomers > 0 
            ? (($thisMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100 
            : ($thisMonthCustomers > 0 ? 100 : 0);
        
        // ========== THỐNG KÊ KHÁCH HÀNG ==========
        
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
            ->havingRaw('total_sold > 0')
            ->orderByDesc('total_sold')
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
        

        
        // Đơn hàng gần đây
        $recentOrders = Order::latest()->limit(10)->get();
        
        return view('admin.dashboard', compact(
            // Tổng quan
            'totalProducts', 'totalOrders', 'totalCustomers', 'totalRevenue',
            'pendingOrders', 'todayOrders', 'monthlyRevenue',
            
            // So sánh tăng trưởng
            'revenueGrowth', 'ordersGrowth', 'customersGrowth',
            'lastMonthRevenue', 'thisMonthOrders', 'lastMonthOrders',
            'thisMonthCustomers', 'lastMonthCustomers',
            
            // Khách hàng
            'newCustomers', 'inactiveCustomers',
            
            // Sản phẩm
            'topProducts', 'newProductsPerformance',
            
            // Biểu đồ
            'chartData', 'topCategories', 'revenueByStatus',
            
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
    

}
