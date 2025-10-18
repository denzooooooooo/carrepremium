<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Flight;
use App\Models\Event;
use App\Models\TourPackage;
use App\Models\Review;
use App\Models\FlightBooking;
use App\Models\EventTicket;
use App\Models\PackageBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with comprehensive statistics
     */
    public function index()
    {
        // Statistiques générales
        $stats = [
            // Utilisateurs
            'total_users' => User::count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'new_users_month' => User::whereMonth('created_at', now()->month)->count(),
            'active_users' => User::where('is_active', true)->count(),
            
            // Réservations
            'total_bookings' => Booking::count(),
            'bookings_today' => Booking::whereDate('created_at', today())->count(),
            'bookings_week' => Booking::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'bookings_month' => Booking::whereMonth('created_at', now()->month)->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            
            // Revenus
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'revenue_today' => Payment::where('status', 'completed')->whereDate('created_at', today())->sum('amount'),
            'revenue_week' => Payment::where('status', 'completed')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount'),
            'revenue_month' => Payment::where('status', 'completed')->whereMonth('created_at', now()->month)->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->sum('amount'),
            
            // Produits
            'total_flights' => Flight::count(),
            'active_flights' => Flight::where('is_active', true)->count(),
            'total_events' => Event::count(),
            'active_events' => Event::where('is_active', true)->count(),
            'upcoming_events' => Event::where('event_date', '>', now())->count(),
            'total_packages' => TourPackage::count(),
            'active_packages' => TourPackage::where('is_active', true)->count(),
            
            // Vols Amadeus
            'flight_bookings_total' => FlightBooking::count(),
            'flight_bookings_pending' => FlightBooking::where('ticket_status', 'pending')->count(),
            'flight_bookings_issued' => FlightBooking::where('ticket_status', 'issued')->count(),
            
            // Événements
            'event_tickets_sold' => EventTicket::where('ticket_status', 'valid')->count(),
            'event_revenue' => EventTicket::sum('final_price'),
            
            // Packages
            'package_bookings_total' => PackageBooking::count(),
            'package_bookings_confirmed' => PackageBooking::where('status', 'confirmed')->count(),
            'package_revenue' => PackageBooking::where('status', 'confirmed')->sum('final_price'),
            
            // Avis
            'total_reviews' => Review::count(),
            'pending_reviews' => Review::where('is_approved', false)->count(),
            'average_rating' => round(Review::where('is_approved', true)->avg('rating'), 1),
        ];

        // Réservations récentes avec relations
        $recentBookings = Booking::with(['user'])
            ->latest()
            ->take(10)
            ->get();

        // Données graphique revenus (12 derniers mois)
        $revenueData = Payment::where('status', 'completed')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(amount) as total')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Données graphique réservations (12 derniers mois)
        $bookingsData = Booking::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Réservations par type
        $bookingsByType = Booking::select('booking_type', DB::raw('count(*) as count'))
            ->groupBy('booking_type')
            ->get();

        // Réservations par statut
        $bookingsByStatus = Booking::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Top 5 destinations (vols) - Utiliser les villes d'événements
        $topDestinations = Event::select('city as destination', DB::raw('count(*) as count'))
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        // Top 5 événements
        $topEvents = Event::withCount('tickets')
            ->orderBy('tickets_count', 'desc')
            ->take(5)
            ->get();

        // Top 5 packages
        $topPackages = TourPackage::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();

        // Utilisateurs récents
        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // Alertes importantes
        $alerts = [
            'low_stock_events' => Event::where('available_seats', '<', 10)->count(),
            'low_stock_packages' => 0, // À implémenter avec inventory
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'failed_payments' => Payment::where('status', 'failed')->whereDate('created_at', '>=', now()->subDays(7))->count(),
        ];

        // Statistiques par période (7 derniers jours)
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyStats[] = [
                'date' => $date->format('Y-m-d'),
                'bookings' => Booking::whereDate('created_at', $date)->count(),
                'revenue' => Payment::where('status', 'completed')->whereDate('created_at', $date)->sum('amount'),
                'users' => User::whereDate('created_at', $date)->count(),
            ];
        }

        return view('admin.dashboard', compact(
            'stats',
            'recentBookings',
            'revenueData',
            'bookingsData',
            'bookingsByType',
            'bookingsByStatus',
            'topDestinations',
            'topEvents',
            'topPackages',
            'recentUsers',
            'alerts',
            'dailyStats'
        ));
    }

    /**
     * Get real-time statistics (AJAX)
     */
    public function getRealtimeStats()
    {
        return response()->json([
            'bookings_today' => Booking::whereDate('created_at', today())->count(),
            'revenue_today' => Payment::where('status', 'completed')->whereDate('created_at', today())->sum('amount'),
            'users_online' => User::where('last_activity', '>', now()->subMinutes(5))->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
        ]);
    }
}
