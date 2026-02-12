<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\AdminAuthController;

// Admin Login Routes (không cần auth)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Admin Routes (cần auth và admin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/filter-chart', [DashboardController::class, 'filterRevenueChart'])->name('dashboard.filter-chart');
    Route::get('/dashboard/export-revenue', [DashboardController::class, 'exportRevenue'])->name('dashboard.export-revenue');
    
    // Products Management
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    
    // Categories Management
    Route::resource('categories', CategoryController::class);
    Route::post('categories/{category}/toggle-active', [CategoryController::class, 'toggleActive'])->name('categories.toggle-active');
    
    // Orders Management
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('orders/{order}/export-pdf', [OrderController::class, 'exportPdf'])->name('orders.export-pdf');
    
    // Customers Management
    Route::resource('customers', CustomerController::class);
    
    // Posts Management
    Route::resource('posts', PostController::class);
    
    // Contacts Management
    Route::resource('contacts', ContactController::class)->only(['index', 'show', 'destroy']);
    Route::post('contacts/{contact}/reply', [ContactController::class, 'reply'])->name('contacts.reply');
    Route::patch('contacts/{contact}/status', [ContactController::class, 'updateStatus'])->name('contacts.update-status');
    
    // Chat Management
    Route::resource('chats', ChatController::class)->only(['index', 'show', 'destroy']);
    Route::post('chats/{sessionId}/reply', [ChatController::class, 'reply'])->name('chats.reply');
    Route::get('chats/unread/count', [ChatController::class, 'getUnreadCount'])->name('chats.unread-count');
});
