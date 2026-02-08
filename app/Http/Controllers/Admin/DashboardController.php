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
        
        // Sản phẩm bán chạy - lấy từ order_items
        $topProducts = DB::table('products')
            ->select('products.*', DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'))
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->where(function($query) {
                $query->whereNull('orders.order_status')
                      ->orWhereIn('orders.order_status', ['completed', 'shipping']);
            })
            ->groupBy('products.id', 'products.name', 'products.slug', 'products.short_description', 'products.description', 'products.price', 'products.sale_price', 'products.stock', 'products.category_id', 'products.image', 'products.status', 'products.is_featured', 'products.is_new', 'products.is_preorder', 'products.platform', 'products.created_at', 'products.updated_at')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();
        
        // Đơn hàng gần đây
        $recentOrders = Order::latest()
            ->limit(10)
            ->get();
        
        // Biểu đồ doanh thu 7 ngày gần đây
        $last7Days = [];
        $revenueChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $last7Days[] = $date->format('d/m');
            $revenueChart[] = Order::where('order_status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total_amount');
        }
        
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
            'last7Days',
            'revenueChart'
        ));
    }
}
