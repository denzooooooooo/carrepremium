<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Exports\BookingsExport;
use App\Mail\FlightBookingConfirmation;
use App\Mail\PaymentReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings with advanced filters
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user']);

        // Recherche avancée
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhere('pnr', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('email', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('booking_type', $request->type);
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par statut de paiement
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filtre par date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filtre par montant
        if ($request->filled('amount_min')) {
            $query->where('final_amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('final_amount', '<=', $request->amount_max);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $bookings = $query->paginate($request->get('per_page', 15));

        // Statistiques détaillées
        $stats = [
            'total' => Booking::count(),
            'today' => Booking::whereDate('created_at', today())->count(),
            'week' => Booking::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month' => Booking::whereMonth('created_at', now()->month)->count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'total_revenue' => Booking::where('payment_status', 'paid')->sum('final_amount'),
            'pending_revenue' => Booking::where('payment_status', 'pending')->sum('final_amount'),
            'by_type' => [
                'flight' => Booking::where('booking_type', 'flight')->count(),
                'event' => Booking::where('booking_type', 'event')->count(),
                'package' => Booking::where('booking_type', 'package')->count(),
            ]
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.bookings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement booking creation
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Réservation créée avec succès');
    }

    /**
     * Display the specified booking
     */
    public function show(string $id)
    {
        $booking = Booking::with(['user', 'flight', 'event', 'package', 'payments'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $booking = Booking::findOrFail($id);
        return view('admin.bookings.edit', compact('booking'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $booking->update($validated);

        if ($validated['status'] === 'confirmed' && !$booking->confirmed_at) {
            $booking->confirmed_at = now();
            $booking->save();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès'
            ]);
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Réservation mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Réservation supprimée avec succès');
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);
        
        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->reason ?? 'Annulée par l\'administrateur',
            'cancelled_at' => now()
        ]);

        return redirect()->back()->with('success', 'Réservation annulée avec succès');
    }

    /**
     * Print booking details
     */
    public function print(string $id)
    {
        $booking = Booking::with(['user', 'flight', 'event', 'package'])->findOrFail($id);
        return view('admin.bookings.print', compact('booking'));
    }

    /**
     * Send confirmation email to client
     */
    public function sendEmail(string $id)
    {
        $booking = Booking::with(['user', 'flightBooking', 'payment'])->findOrFail($id);
        
        try {
            // Envoyer email selon le type de réservation
            if ($booking->booking_type == 'flight' && $booking->flightBooking) {
                Mail::to($booking->user->email)->send(new FlightBookingConfirmation($booking->flightBooking));
            }
            
            // Envoyer aussi le reçu de paiement si payé
            if ($booking->payment && $booking->payment->status == 'completed') {
                Mail::to($booking->user->email)->send(new PaymentReceipt($booking->payment));
            }

            return response()->json([
                'success' => true,
                'message' => 'Email(s) envoyé(s) avec succès à ' . $booking->user->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export bookings to Excel with advanced formatting
     */
    public function export(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'status', 'booking_type']);
        
        $filename = 'reservations_' . now()->format('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new BookingsExport($filters), $filename);
    }
    
    /**
     * Export bookings to CSV (alternative)
     */
    public function exportCsv(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'status', 'booking_type']);
        
        $filename = 'reservations_' . now()->format('Y-m-d_His') . '.csv';
        
        return Excel::download(new BookingsExport($filters), $filename, \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * Bulk actions on bookings
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:confirm,cancel,delete',
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:bookings,id'
        ]);

        $bookings = Booking::whereIn('id', $validated['booking_ids'])->get();

        switch ($validated['action']) {
            case 'confirm':
                foreach ($bookings as $booking) {
                    $booking->update([
                        'status' => 'confirmed',
                        'confirmed_at' => now()
                    ]);
                }
                $message = count($bookings) . ' réservation(s) confirmée(s)';
                break;

            case 'cancel':
                foreach ($bookings as $booking) {
                    $booking->update([
                        'status' => 'cancelled',
                        'cancelled_at' => now()
                    ]);
                }
                $message = count($bookings) . ' réservation(s) annulée(s)';
                break;

            case 'delete':
                Booking::whereIn('id', $validated['booking_ids'])->delete();
                $message = count($bookings) . ' réservation(s) supprimée(s)';
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}
