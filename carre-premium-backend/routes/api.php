<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FlightController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\PackageController;
use App\Http\Controllers\API\CarouselController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\AmadeusFlightController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserBookingController;
use App\Http\Controllers\API\PromoCodeController;
use App\Http\Controllers\API\ImageController;

/*
|--------------------------------------------------------------------------
| API Routes - Carré Premium
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ============================================
// PUBLIC API ROUTES - Version 1
// ============================================
Route::prefix('v1')->group(function () {
    
    // ============================================
    // AUTHENTICATION (Public)
    // ============================================
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    });
    
    // ============================================
    // AMADEUS FLIGHTS API (Real-time)
    // ============================================
    Route::prefix('amadeus')->group(function () {
        // Recherche de vols en temps réel
        Route::post('/flights/search', [AmadeusFlightController::class, 'searchFlights']);
        
        // Confirmation de prix
        Route::post('/flights/confirm-price', [AmadeusFlightController::class, 'confirmPrice']);
        
        // Recherche d'aéroports
        Route::get('/airports/search', [AmadeusFlightController::class, 'searchAirports']);
        
        // Créer une réservation (peut être fait sans auth pour les guests)
        Route::post('/bookings', [AmadeusFlightController::class, 'createBooking']);
        
        // Récupérer une réservation
        Route::get('/bookings/{id}', [AmadeusFlightController::class, 'getBooking']);
        
        // Annuler une réservation
        Route::delete('/bookings/{id}', [AmadeusFlightController::class, 'cancelBooking']);
        
        // Statistiques (admin only - sera protégé par middleware)
        Route::get('/admin/statistics', [AmadeusFlightController::class, 'getStatistics']);
    });
    
    // ============================================
    // FLIGHTS API (Legacy - pour événements/packages)
    // ============================================
    Route::prefix('flights')->group(function () {
        Route::get('/', [FlightController::class, 'index']);
        Route::get('/popular', [FlightController::class, 'popular']);
        Route::post('/search', [FlightController::class, 'search']);
        Route::get('/{id}', [FlightController::class, 'show']);
        Route::post('/{id}/check-availability', [FlightController::class, 'checkAvailability']);
    });
    
    // Airlines & Airports
    Route::get('/airlines', [FlightController::class, 'airlines']);
    Route::get('/airports', [FlightController::class, 'airports']);
    
    // ============================================
    // EVENTS API
    // ============================================
    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index']);
        Route::get('/upcoming', [EventController::class, 'upcoming']);
        Route::get('/categories', [EventController::class, 'categories']);
        Route::post('/search', [EventController::class, 'search']);
        Route::get('/category/{id}', [EventController::class, 'byCategory']);
        Route::get('/{id}', [EventController::class, 'show']);
        Route::post('/{id}/check-availability', [EventController::class, 'checkAvailability']);
    });
    
    // ============================================
    // PACKAGES API
    // ============================================
    Route::prefix('packages')->group(function () {
        Route::get('/', [PackageController::class, 'index']);
        Route::get('/vip', [PackageController::class, 'vip']);
        Route::post('/search', [PackageController::class, 'search']);
        Route::get('/type/{type}', [PackageController::class, 'byType']);
        Route::get('/{id}', [PackageController::class, 'show']);
        Route::get('/{id}/available-dates', [PackageController::class, 'availableDates']);
        Route::post('/{id}/check-availability', [PackageController::class, 'checkAvailability']);
    });
    
    // ============================================
    // CAROUSELS API
    // ============================================
    Route::get('/carousels', [CarouselController::class, 'active']);
    
    // ============================================
    // SETTINGS API
    // ============================================
    Route::get('/settings', [SettingController::class, 'public']);
    Route::get('/currencies', [SettingController::class, 'currencies']);
    
    // ============================================
    // CART API (Guest & Authenticated)
    // ============================================
    Route::prefix('cart')->group(function () {
        Route::post('/add', [CartController::class, 'add']);
        Route::get('/{session_id}', [CartController::class, 'get']);
        Route::put('/{id}', [CartController::class, 'update']);
        Route::delete('/{id}', [CartController::class, 'remove']);
        Route::delete('/session/{session_id}', [CartController::class, 'clear']);
    });
    
    // ============================================
    // BOOKINGS API (Guest can create)
    // ============================================
    Route::post('/bookings', [BookingController::class, 'create']);
    Route::get('/bookings/{booking_number}', [BookingController::class, 'getByNumber']);
    
    // ============================================
    // PAYMENTS API
    // ============================================
    Route::prefix('payments')->group(function () {
        // Get available payment methods
        Route::get('/methods', [PaymentController::class, 'getPaymentMethods']);
        
        // Initialize payment
        Route::post('/initialize', [PaymentController::class, 'initializePayment']);
        
        // Confirm payment (Stripe)
        Route::post('/confirm', [PaymentController::class, 'confirmPayment']);
        
        // Check payment status (Mobile Money)
        Route::post('/check-status', [PaymentController::class, 'checkPaymentStatus']);
        
        // Get payment history for a booking
        Route::get('/history/{booking_id}', [PaymentController::class, 'getPaymentHistory']);
        
        // Webhooks (no authentication required)
        Route::post('/stripe/webhook', [PaymentController::class, 'stripeWebhook'])->name('payment.stripe.webhook');
        Route::post('/orange/callback', [PaymentController::class, 'orangeMoneyCallback'])->name('payment.orange.callback');
    });
    
    // ============================================
    // PROMO CODES API (Public - Validation)
    // ============================================
    Route::prefix('promo-codes')->group(function () {
        // Valider un code promo
        Route::post('/validate', [PromoCodeController::class, 'validateCode']);
        
        // Obtenir les codes promo actifs (pour affichage marketing)
        Route::get('/active', [PromoCodeController::class, 'getActiveCodes']);
    });
    
    // ============================================
    // IMAGES API (Public - Serving images with CORS)
    // ============================================
    Route::get('/images/{path}', [ImageController::class, 'serve'])->where('path', '.*');
    Route::post('/images/url', [ImageController::class, 'getImageUrl']);
});

// ============================================
// PROTECTED API ROUTES (Require Authentication)
// ============================================
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    
    // ============================================
    // USER AUTHENTICATION (Protected)
    // ============================================
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::put('/password', [AuthController::class, 'changePassword']);
        Route::post('/avatar', [AuthController::class, 'uploadAvatar']);
        Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
        Route::delete('/account', [AuthController::class, 'deleteAccount']);
    });
    
    // ============================================
    // USER BOOKINGS & DOCUMENTS
    // ============================================
    Route::prefix('user')->group(function () {
        // Réservations
        Route::get('/bookings', [UserBookingController::class, 'index']);
        Route::get('/bookings/{id}', [UserBookingController::class, 'show']);
        Route::post('/bookings/{id}/cancel', [UserBookingController::class, 'cancel']);
        
        // Téléchargements
        Route::get('/bookings/{id}/receipt', [UserBookingController::class, 'downloadReceipt']);
        Route::get('/bookings/{id}/ticket', [UserBookingController::class, 'downloadTicket']);
        Route::get('/bookings/{id}/confirmation', [UserBookingController::class, 'downloadConfirmation']);
        
        // Statistiques
        Route::get('/statistics', [UserBookingController::class, 'statistics']);
        
        // ============================================
        // LOYALTY POINTS (Protected)
        // ============================================
        Route::prefix('loyalty')->group(function () {
            // Obtenir le solde de points
            Route::get('/balance', [UserBookingController::class, 'getLoyaltyBalance']);
            
            // Historique des points
            Route::get('/history', [UserBookingController::class, 'getLoyaltyHistory']);
            
            // Calculer la réduction potentielle
            Route::post('/calculate-discount', [UserBookingController::class, 'calculateLoyaltyDiscount']);
        });
    });
    
    // Amadeus - Mes réservations
    Route::get('/amadeus/my-bookings', [AmadeusFlightController::class, 'getMyBookings']);
    
    // User Bookings (Legacy)
    Route::get('/my-bookings', [BookingController::class, 'myBookings']);
    Route::get('/my-bookings/{id}', [BookingController::class, 'show']);
    Route::post('/my-bookings/{id}/cancel', [BookingController::class, 'cancel']);
    
    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'add']);
    Route::delete('/favorites/{id}', [FavoriteController::class, 'remove']);
    
    // Reviews
    Route::post('/reviews', [ReviewController::class, 'create']);
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'delete']);
    
    // User Profile
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::put('/profile/password', [UserController::class, 'updatePassword']);
});
