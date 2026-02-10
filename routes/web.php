<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Trang chủ và các trang công khai - Admin có thể sử dụng
Route::get('/', [HomeController::class, 'index'])->name('home');

// Sản phẩm
Route::get('/san-pham', [ProductController::class, 'index'])->name('products.index');
Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');

// Danh mục
Route::get('/danh-muc/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Giỏ hàng
Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
Route::post('/gio-hang/them/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/gio-hang/cap-nhat/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/gio-hang/xoa/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Thanh toán
Route::get('/thanh-toan', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/thanh-toan/xu-ly', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/thanh-toan/thanh-cong/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Tin tức
Route::get('/tin-tuc', [App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
Route::get('/tin-tuc/{slug}', [App\Http\Controllers\NewsController::class, 'show'])->name('news.show');

// Liên hệ
Route::get('/lien-he', function () {
    return view('pages.contact');
})->name('contact');
Route::post('/lien-he', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// Chatbot
Route::post('/chatbot/send', [App\Http\Controllers\ChatbotController::class, 'sendMessage'])->name('chatbot.send');
Route::get('/chatbot/history', [App\Http\Controllers\ChatbotController::class, 'getHistory'])->name('chatbot.history');
Route::get('/chatbot/new-messages', [App\Http\Controllers\ChatbotController::class, 'getNewMessages'])->name('chatbot.new-messages');

// Về chúng tôi
Route::get('/ve-chung-toi', function () {
    return view('pages.about');
})->name('about');

// Account routes (yêu cầu đăng nhập)
Route::middleware('auth')->group(function () {
    Route::get('/tai-khoan', [AccountController::class, 'profile'])->name('account.profile');
    Route::post('/tai-khoan/cap-nhat', [AccountController::class, 'updateProfile'])->name('account.update');
    Route::post('/tai-khoan/doi-mat-khau', [AccountController::class, 'changePassword'])->name('account.change-password');
    Route::get('/don-hang', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/don-hang/{id}', [AccountController::class, 'orderDetail'])->name('account.order-detail');
});

// Authentication routes
Route::get('/dang-nhap', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/dang-nhap', [AuthController::class, 'login']);
Route::get('/dang-ky', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/dang-ky', [AuthController::class, 'register']);
Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
require __DIR__.'/admin.php';
