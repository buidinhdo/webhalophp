<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Product;
use App\Observers\ProductObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Đăng ký ProductObserver để tự động gán genre
        Product::observe(ProductObserver::class);

        // Share categories with all views
        View::composer('*', function ($view) {
            $headerCategories = Category::where('is_active', true)
                ->whereNull('parent_id')
                ->orderByRaw("CASE 
                    WHEN slug = 'playstation-1' OR slug = 'ps1' THEN 1
                    WHEN slug = 'playstation-2' THEN 2
                    WHEN slug = 'playstation-3' THEN 3
                    WHEN slug = 'ps4' THEN 4
                    WHEN slug = 'ps5' THEN 5
                    WHEN slug = 'nintendo-switch' THEN 6
                    WHEN slug = 'xbox' THEN 7
                    WHEN slug = 'nintendo-gamecube' THEN 8
                    WHEN slug = 'wii' OR slug = 'nintendo-wii' THEN 9
                    WHEN slug = 'super-nintendo' OR slug = 'super-nintedo' OR slug = 'snes' THEN 10
                    ELSE 11
                END")
                ->get();
            $view->with('headerCategories', $headerCategories);
        });
    }
}
