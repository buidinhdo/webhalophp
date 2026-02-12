<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }
        
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Lấy thông tin user nếu đã đăng nhập
        $user = Auth::user();
        
        return view('checkout.index', compact('cart', 'subtotal', 'user'));
    }
    
    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'address.required' => 'Vui lòng nhập địa chỉ',
        ]);
        
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }
        
        // Tính tổng tiền
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }
        
        // Tạo đơn hàng
        $order = Order::create([
            'user_id' => Auth::id(), // null nếu chưa đăng nhập
            'order_number' => 'ORD-' . date('Ymd') . '-' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT),
            'customer_name' => $request->name,
            'customer_email' => $request->email,
            'customer_phone' => $request->phone,
            'customer_address' => $request->address,
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method ?? 'cod',
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'notes' => $request->notes,
        ]);
        
        // Tạo order items - lưu chi tiết từng sản phẩm
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'product_name' => $item['name'],
                'product_image' => $product ? $product->image : null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }
        
        // Xóa giỏ hàng
        session()->forget('cart');
        
        return redirect()->route('checkout.success', $order->id)
            ->with('success', 'Đặt hàng thành công!');
    }
    
    public function success($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        return view('checkout.success', compact('order'));
    }
}
