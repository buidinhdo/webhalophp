<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\MoMoPaymentService;

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
            'payment_method' => 'required|in:cod,bank_transfer,momo',
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'address.required' => 'Vui lòng nhập địa chỉ',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
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
            'subtotal' => $totalAmount,
            'shipping_fee' => 0,
            'discount' => 0,
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
                'total' => $item['price'] * $item['quantity'],
            ]);
        }
        
        // Xóa giỏ hàng
        session()->forget('cart');
        
        // Nếu thanh toán chuyển khoản, chuyển đến trang QR
        if ($request->payment_method === 'bank_transfer') {
            return redirect()->route('checkout.payment-qr', $order->id);
        }
        
        // Nếu thanh toán MoMo, chuyển đến trang MoMo
        if ($request->payment_method === 'momo') {
            return redirect()->route('checkout.payment-momo', $order->id);
        }
        
        return redirect()->route('checkout.success', $order->id)
            ->with('success', 'Đặt hàng thành công!');
    }
    
    public function paymentQR($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        
        // Lấy danh sách ngân hàng
        $banks = config('banks.banks');
        
        return view('checkout.payment-qr', compact('order', 'banks'));
    }
    
    public function success($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        return view('checkout.success', compact('order'));
    }
    
    public function paymentMoMo($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        
        // Khởi tạo MoMo Payment Service
        $momoService = new MoMoPaymentService();
        
        // Tạo yêu cầu thanh toán
        $response = $momoService->createPayment($order);
        
        return view('checkout.payment-momo', compact('order', 'response'));
    }
    
    public function momoCallback(Request $request)
    {
        Log::info('MoMo Callback', $request->all());
        
        $momoService = new MoMoPaymentService();
        
        // Xác thực chữ ký từ MoMo
        if (!$momoService->verifySignature($request->all())) {
            Log::error('MoMo Invalid Signature', $request->all());
            return redirect()->route('home')->with('error', 'Xác thực thanh toán thất bại!');
        }
        
        // Lấy thông tin đơn hàng
        $orderId = $request->orderId;
        $order = Order::where('order_number', $orderId)->first();
        
        if (!$order) {
            Log::error('MoMo Order Not Found', ['order_number' => $orderId]);
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng!');
        }
        
        // Kiểm tra kết quả thanh toán
        if ($request->resultCode == 0) {
            // Thanh toán thành công
            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'processing',
            ]);
            
            Log::info('MoMo Payment Success', [
                'order_id' => $order->id,
                'transaction_id' => $request->transId
            ]);
            
            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Thanh toán MoMo thành công!');
        } else {
            // Thanh toán thất bại
            Log::warning('MoMo Payment Failed', [
                'order_id' => $order->id,
                'result_code' => $request->resultCode,
                'message' => $request->message
            ]);
            
            return redirect()->route('checkout.payment-momo', $order->id)
                ->with('error', 'Thanh toán thất bại: ' . $request->message);
        }
    }
    
    public function momoIPN(Request $request)
    {
        Log::info('MoMo IPN', $request->all());
        
        $momoService = new MoMoPaymentService();
        
        // Xác thực chữ ký từ MoMo
        if (!$momoService->verifySignature($request->all())) {
            Log::error('MoMo IPN Invalid Signature', $request->all());
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid signature'
            ], 400);
        }
        
        // Lấy thông tin đơn hàng
        $orderId = $request->orderId;
        $order = Order::where('order_number', $orderId)->first();
        
        if (!$order) {
            Log::error('MoMo IPN Order Not Found', ['order_number' => $orderId]);
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }
        
        // Kiểm tra kết quả thanh toán
        if ($request->resultCode == 0) {
            // Thanh toán thành công
            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'processing',
            ]);
            
            Log::info('MoMo IPN Payment Success', [
                'order_id' => $order->id,
                'transaction_id' => $request->transId
            ]);
        }
        
        return response()->json(['status' => 'success'], 200);
    }
}
