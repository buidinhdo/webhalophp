<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
        // Refresh dữ liệu từ database để đảm bảo hiển thị mới nhất
        $order->refresh();
        $order->load(['items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        // Refresh dữ liệu từ database
        $order->refresh();
        $order->load(['items.product']);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'order_status' => 'required|in:pending,processing,shipping,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid',
            'notes' => 'nullable|string',
            'item_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        // Cập nhật thông tin đơn hàng
        $order->update([
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'],
            'order_status' => $validated['order_status'],
            'payment_status' => $validated['payment_status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Xử lý upload ảnh cho từng order item
        if ($request->hasFile('item_images')) {
            foreach ($request->file('item_images') as $itemId => $image) {
                if ($image) {
                    $orderItem = OrderItem::find($itemId);
                    if ($orderItem && $orderItem->order_id == $order->id) {
                        // Xóa ảnh cũ nếu có
                        if ($orderItem->product_image && file_exists(public_path($orderItem->product_image))) {
                            @unlink(public_path($orderItem->product_image));
                        }
                        
                        // Upload ảnh mới
                        $imageName = time() . '_' . $itemId . '_' . $image->getClientOriginalName();
                        $image->move(public_path('images/products'), $imageName);
                        $orderItem->update([
                            'product_image' => 'images/products/' . $imageName
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Đơn hàng đã được cập nhật!');
    }

    public function destroy(Order $order)
    {
        // Xóa order items trước
        $order->items()->delete();
        
        // Xóa order
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Xóa đơn hàng thành công!');
    }

    public function exportPdf(Order $order)
    {
        // Load order with items and products - refresh to get latest data
        $order->refresh();
        $order->load(['items.product']);
        
        // Generate PDF with options for Vietnamese support
        $pdf = Pdf::loadView('admin.orders.pdf', compact('order'));
        
        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');
        
        // Set options for better Unicode support
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans'
        ]);
        
        // Download PDF
        return $pdf->download('don-hang-' . $order->order_number . '.pdf');
    }
}
