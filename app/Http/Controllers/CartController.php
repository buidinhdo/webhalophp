<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('cart.index', compact('cart', 'total'));
    }
    
    public function add(Request $request, $id)
    {
        $quantity = max(1, (int) $request->input('quantity', 1));

        if (!Auth::check()) {
            session()->put('pending_cart_add', [
                'product_id' => (int) $id,
                'quantity' => $quantity,
                'redirect_to' => url()->previous(),
            ]);

            return redirect()->guest(route('login'))
                ->with('warning', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.');
        }

        $this->addProductToCart((int) $id, $quantity);
        
        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function addProductToCart(int $productId, int $quantity = 1): void
    {
        $product = Product::findOrFail($productId);
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $product->display_price,
                'image' => $product->image,
                'quantity' => $quantity,
                'slug' => $product->slug,
            ];
        }

        session()->put('cart', $cart);
    }
    
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
        
        return redirect()->back()->with('success', 'Cập nhật giỏ hàng thành công!');
    }
    
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }
}
