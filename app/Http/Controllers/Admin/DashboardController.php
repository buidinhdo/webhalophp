<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê tổng quan
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCustomers = User::count();
        $totalRevenue = Order::where('order_status', 'completed')->sum('total_amount');
        
        // Đơn hàng mới (pending)
        $pendingOrders = Order::where('order_status', 'pending')->count();
        
        // Đơn hàng hôm nay
        $todayOrders = Order::whereDate('created_at', today())->count();
        
        // Doanh thu tháng này
        $monthlyRevenue = Order::where('order_status', 'completed')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('total_amount');
        
        // Sản phẩm bán chạy - lấy từ order_items (đã bán thật)
        $topProducts = Product::select('products.*')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.order_status', ['completed', 'shipping', 'processing', 'pending'])
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
        
        // Đơn hàng gần đây
        $recentOrders = Order::latest()
            ->limit(10)
            ->get();
        
        // Biểu đồ doanh thu 7 ngày (default)
        $chartData = $this->getRevenueChartData(7);
        
        // Biểu đồ tròn - Top 5 danh mục bán chạy
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
        
        // Biểu đồ cột - Doanh thu theo trạng thái đơn
        $revenueByStatus = [
            'pending' => Order::where('order_status', 'pending')->sum('total_amount'),
            'processing' => Order::where('order_status', 'processing')->sum('total_amount'),
            'shipping' => Order::where('order_status', 'shipping')->sum('total_amount'),
            'completed' => Order::where('order_status', 'completed')->sum('total_amount'),
            'cancelled' => Order::where('order_status', 'cancelled')->sum('total_amount'),
        ];
        
        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalCustomers',
            'totalRevenue',
            'pendingOrders',
            'todayOrders',
            'monthlyRevenue',
            'topProducts',
            'recentOrders',
            'chartData',
            'topCategories',
            'revenueByStatus'
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
