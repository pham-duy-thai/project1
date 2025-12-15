<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\MaintenanceRequest;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Đăng ký dịch vụ ứng dụng (chỗ trống)
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set Carbon locale to Vietnamese
        Carbon::setLocale('vi');
        setlocale(LC_TIME, 'vi_VN.UTF-8', 'vi_VN', 'vi');
        
        // Sử dụng view phân trang Bootstrap toàn cục để khớp với giao diện
        if (method_exists(Paginator::class, 'useBootstrapFour')) {
            Paginator::useBootstrapFour();
        } elseif (method_exists(Paginator::class, 'useBootstrap')) {
            // Dự phòng cho các phiên bản Laravel cũ hơn
            Paginator::useBootstrap();
        }

        // Define authorization gates
        Gate::define('manage-rooms', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-violations', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-contracts', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-payments', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        // Route model binding for maintenance requests
        Route::bind('maintenance', function ($value) {
            return MaintenanceRequest::findOrFail($value);
        });

        // Blade directive to check if storage file exists
        Blade::if('storageFileExists', function ($path) {
            return Storage::disk('public')->exists($path);
        });

        // Helper function to get storage asset with existence check
        if (!function_exists('storage_asset')) {
            function storage_asset($path, $default = null) {
                if (Storage::disk('public')->exists($path)) {
                    return asset('storage/' . $path);
                }
                return $default ?? asset('assets/images/user/user-1.jpg');
            }
        }
    }
}
