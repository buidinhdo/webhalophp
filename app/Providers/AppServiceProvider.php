<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

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
        // Share categories with all views
        View::composer('*', function ($view) {
            $headerCategories = Category::where('is_active', true)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get();
            $view->with('headerCategories', $headerCategories);
        });
    }
}
