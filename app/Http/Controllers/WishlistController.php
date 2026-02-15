<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $wishlists = Auth::user()->wishlists()->with('product')->latest()->get();
        
        return view('wishlist.index', compact('wishlists'));
    }
    
    /**
     * Add a product to wishlist.
     */
    public function add($productId)
    {
        $product = Product::findOrFail($productId);
        
        // Check if already in wishlist
        $exists = Wishlist::where('user_id', Auth::id())
                         ->where('product_id', $productId)
                         ->exists();
        
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm đã có trong danh sách yêu thích'
            ]);
        }
        
        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $productId
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào danh sách yêu thích',
            'count' => Wishlist::where('user_id', Auth::id())->count()
        ]);
    }
    
    /**
     * Remove a product from wishlist.
     */
    public function remove($productId)
    {
        Wishlist::where('user_id', Auth::id())
               ->where('product_id', $productId)
               ->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khỏi danh sách yêu thích',
            'count' => Wishlist::where('user_id', Auth::id())->count()
        ]);
    }
    
    /**
     * Toggle wishlist status.
     */
    public function toggle($productId)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
                           ->where('product_id', $productId)
                           ->first();
        
        if ($wishlist) {
            $wishlist->delete();
            $message = 'Đã xóa khỏi danh sách yêu thích';
            $inWishlist = false;
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
            $message = 'Đã thêm vào danh sách yêu thích';
            $inWishlist = true;
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'inWishlist' => $inWishlist,
            'count' => Wishlist::where('user_id', Auth::id())->count()
        ]);
    }
    
    /**
     * Check if product is in wishlist.
     */
    public function check($productId)
    {
        $inWishlist = Wishlist::where('user_id', Auth::id())
                             ->where('product_id', $productId)
                             ->exists();
        
        return response()->json([
            'inWishlist' => $inWishlist
        ]);
    }
}
