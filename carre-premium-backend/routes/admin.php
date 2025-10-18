<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FlightController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CarouselController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\PricingRuleController;
use App\Http\Controllers\Admin\ApiConfigController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\ReportingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Guest routes (not authenticated)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    // Authenticated admin routes
    Route::middleware('auth:admin')->group(function () {
        
        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/realtime', [DashboardController::class, 'getRealtimeStats'])->name('dashboard.realtime');
        
        // Users Management
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('users-export', [UserController::class, 'export'])->name('users.export');
        Route::post('users/{user}/add-points', [UserController::class, 'addPoints'])->name('users.add-points');
        Route::post('users/{user}/send-email', [UserController::class, 'sendEmail'])->name('users.send-email');
        
        // Flights Management
        Route::resource('flights', FlightController::class);
        Route::post('flights/{flight}/toggle-status', [FlightController::class, 'toggleStatus'])->name('flights.toggle-status');
        
        // Events Management
        Route::resource('events', EventController::class);
        Route::post('events/{event}/toggle-status', [EventController::class, 'toggleStatus'])->name('events.toggle-status');
        
        // Tour Packages Management
        Route::resource('packages', PackageController::class);
        Route::post('packages/{package}/toggle-status', [PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
        
        // Bookings Management
        Route::resource('bookings', BookingController::class);
        Route::post('bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
        Route::get('bookings/{booking}/print', [BookingController::class, 'print'])->name('bookings.print');
        Route::post('bookings/{booking}/send-email', [BookingController::class, 'sendEmail'])->name('bookings.send-email');
        Route::get('bookings-export', [BookingController::class, 'export'])->name('bookings.export');
        Route::get('bookings-export-csv', [BookingController::class, 'exportCsv'])->name('bookings.export-csv');
        Route::post('bookings-bulk-action', [BookingController::class, 'bulkAction'])->name('bookings.bulk-action');
        
        // Categories Management
        Route::resource('categories', CategoryController::class);
        Route::post('categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('categories.toggle');
        
        // Carousels Management
        Route::resource('carousels', CarouselController::class);
        Route::post('carousels/{carousel}/toggle-status', [CarouselController::class, 'toggleStatus'])->name('carousels.toggle-status');
        Route::post('carousels/reorder', [CarouselController::class, 'reorder'])->name('carousels.reorder');
        
        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        
        // Admin Profile
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
        Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');
        
        // Notifications
        Route::get('/notifications', [AuthController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/{id}/read', [AuthController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [AuthController::class, 'markAllAsRead'])->name('notifications.read-all');
        
        // Pricing Rules Management
        Route::get('/pricing-rules', [PricingRuleController::class, 'index'])->name('pricing-rules.index');
        Route::post('/pricing-rules', [PricingRuleController::class, 'store'])->name('pricing-rules.store');
        Route::put('/pricing-rules/{id}', [PricingRuleController::class, 'update'])->name('pricing-rules.update');
        Route::delete('/pricing-rules/{id}', [PricingRuleController::class, 'destroy'])->name('pricing-rules.destroy');
        Route::post('/pricing-rules/{id}/toggle', [PricingRuleController::class, 'toggleStatus'])->name('pricing-rules.toggle');
        
        // API Configuration Management
        Route::get('/api-config', [ApiConfigController::class, 'index'])->name('api-config.index');
        Route::post('/api-config', [ApiConfigController::class, 'store'])->name('api-config.store');
        Route::put('/api-config/{id}', [ApiConfigController::class, 'update'])->name('api-config.update');
        Route::delete('/api-config/{id}', [ApiConfigController::class, 'destroy'])->name('api-config.destroy');
        Route::post('/api-config/{id}/test', [ApiConfigController::class, 'testConnection'])->name('api-config.test');
        
        // Payment Gateways Management
        Route::get('/payment-gateways', [PaymentGatewayController::class, 'index'])->name('payment-gateways.index');
        Route::post('/payment-gateways', [PaymentGatewayController::class, 'store'])->name('payment-gateways.store');
        Route::put('/payment-gateways/{id}', [PaymentGatewayController::class, 'update'])->name('payment-gateways.update');
        Route::delete('/payment-gateways/{id}', [PaymentGatewayController::class, 'destroy'])->name('payment-gateways.destroy');
        Route::post('/payment-gateways/{id}/toggle', [PaymentGatewayController::class, 'toggleStatus'])->name('payment-gateways.toggle');
        
        // Reviews Management
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{id}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{id}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
        Route::post('/reviews/{id}/respond', [ReviewController::class, 'respond'])->name('reviews.respond');
        Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
        
        // Promo Codes Management
        Route::get('/promo-codes', [PromoCodeController::class, 'index'])->name('promo-codes.index');
        Route::post('/promo-codes', [PromoCodeController::class, 'store'])->name('promo-codes.store');
        Route::put('/promo-codes/{id}', [PromoCodeController::class, 'update'])->name('promo-codes.update');
        Route::delete('/promo-codes/{id}', [PromoCodeController::class, 'destroy'])->name('promo-codes.destroy');
        Route::post('/promo-codes/{id}/toggle', [PromoCodeController::class, 'toggleStatus'])->name('promo-codes.toggle');
        
        // Financial Reporting (Comptabilité)
        Route::prefix('reporting')->name('reporting.')->group(function () {
            // Dashboard principal
            Route::get('/', [ReportingController::class, 'index'])->name('index');
            
            // Rapports spécifiques
            Route::get('/revenue', [ReportingController::class, 'revenue'])->name('revenue');
            Route::get('/clients', [ReportingController::class, 'clients'])->name('clients');
            Route::get('/services', [ReportingController::class, 'services'])->name('services');
            Route::get('/transactions', [ReportingController::class, 'transactions'])->name('transactions');
            Route::get('/refunds', [ReportingController::class, 'refunds'])->name('refunds');
            
            // Rapport personnalisé
            Route::post('/custom', [ReportingController::class, 'customReport'])->name('custom');
            
            // Exports
            Route::get('/export/{type}', [ReportingController::class, 'export'])->name('export');
            
            // API pour graphiques AJAX
            Route::get('/api/data', [ReportingController::class, 'getReportingData'])->name('api.data');
        });
    });
});
