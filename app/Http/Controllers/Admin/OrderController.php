<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();
        
        if ($request->has('status') && $request->status) {
            $query->where('order_status', $request->status);
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Tìm kiếm theo số đơn hàng (bao gồm cả số đuôi)
                $q->where('order_number', 'like', "%{$search}%")
                  // Hoặc tên khách hàng
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  // Hoặc số điện thoại
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  // Hoặc email
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }
        
        $orders = $query->latest()->paginate(20);
        
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:pending,processing,shipping,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid'
        ]);

        $order->update($validated);

        return redirect()->back()
            ->with('success', 'Đơn hàng đã được cập nhật!');
    }
}
