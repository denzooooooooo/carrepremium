<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\DocumentGeneratorService;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Contrôleur pour la gestion des réservations utilisateur
 * Permet aux utilisateurs de voir leurs réservations et télécharger les reçus
 */
class UserBookingController extends Controller
{
    protected $documentService;
    protected $loyaltyService;

    public function __construct(DocumentGeneratorService $documentService, LoyaltyService $loyaltyService)
    {
        $this->documentService = $documentService;
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Obtenir toutes les réservations de l'utilisateur
     * 
     * GET /api/v1/user/bookings
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            $bookings = Booking::with(['payment', 'flightBooking'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $bookings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des réservations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les détails d'une réservation
     * 
     * GET /api/v1/user/bookings/{id}
     */
    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            $booking = Booking::with(['payment', 'flightBooking', 'event', 'package'])
                ->where('user_id', $user->id)
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $booking
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Réservation non trouvée',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Télécharger le reçu d'une réservation (PDF)
     * 
     * GET /api/v1/user/bookings/{id}/receipt
     */
    public function downloadReceipt(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            $booking = Booking::with(['payment', 'flightBooking', 'event', 'package'])
                ->where('user_id', $user->id)
                ->findOrFail($id);

            // Vérifier que le paiement est complété
            if ($booking->payment_status !== 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Le paiement n\'est pas encore complété'
                ], 400);
            }

            // Générer le PDF du reçu
            $pdf = $this->documentService->generateReceipt($booking);

            return $pdf->download('recu_' . $booking->booking_number . '.pdf');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du téléchargement du reçu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Télécharger le billet (e-ticket) pour un vol
     * 
     * GET /api/v1/user/bookings/{id}/ticket
     */
    public function downloadTicket(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            $booking = Booking::with(['flightBooking', 'payment'])
                ->where('user_id', $user->id)
                ->where('booking_type', 'flight')
                ->findOrFail($id);

            if (!$booking->flightBooking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun billet disponible pour cette réservation'
                ], 404);
            }

            // Générer le PDF du billet
            $pdf = $this->documentService->generateETicket($booking);

            return $pdf->download('billet_' . $booking->booking_number . '.pdf');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du téléchargement du billet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Télécharger la confirmation de réservation
     * 
     * GET /api/v1/user/bookings/{id}/confirmation
     */
    public function downloadConfirmation(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            $booking = Booking::with(['payment', 'flightBooking', 'event', 'package'])
                ->where('user_id', $user->id)
                ->findOrFail($id);

            // Générer le PDF de confirmation
            $pdf = $this->documentService->generateBookingConfirmation($booking);

            return $pdf->download('confirmation_' . $booking->booking_number . '.pdf');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du téléchargement de la confirmation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques de l'utilisateur
     * 
     * GET /api/v1/user/statistics
     */
    public function statistics(Request $request)
    {
        try {
            $user = $request->user();
            
            $stats = [
                'total_bookings' => Booking::where('user_id', $user->id)->count(),
                'confirmed_bookings' => Booking::where('user_id', $user->id)
                    ->where('status', 'confirmed')
                    ->count(),
                'total_spent' => Payment::where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->sum('amount'),
                'loyalty_points' => $user->loyalty_points,
                'bookings_by_type' => Booking::where('user_id', $user->id)
                    ->selectRaw('booking_type, count(*) as count')
                    ->groupBy('booking_type')
                    ->get()
                    ->pluck('count', 'booking_type'),
                'recent_bookings' => Booking::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Annuler une réservation
     * 
     * POST /api/v1/user/bookings/{id}/cancel
     */
    public function cancel(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            $booking = Booking::where('user_id', $user->id)->findOrFail($id);

            // Vérifier que la réservation peut être annulée
            if ($booking->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette réservation est déjà annulée'
                ], 400);
            }

            if ($booking->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette réservation est déjà complétée et ne peut être annulée'
                ], 400);
            }

            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->input('reason', 'Annulation client')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Réservation annulée avec succès',
                'data' => $booking
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir le solde de points de fidélité
     * 
     * GET /api/v1/user/loyalty/balance
     */
    public function getLoyaltyBalance(Request $request)
    {
        try {
            $user = $request->user();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'points' => $user->loyalty_points,
                    'point_value' => LoyaltyService::POINT_VALUE,
                    'total_value_xof' => $user->loyalty_points * LoyaltyService::POINT_VALUE
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du solde',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir l'historique des points de fidélité
     * 
     * GET /api/v1/user/loyalty/history
     */
    public function getLoyaltyHistory(Request $request)
    {
        try {
            $user = $request->user();
            
            $history = $this->loyaltyService->getPointsHistory($user);
            
            return response()->json([
                'success' => true,
                'data' => $history
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'historique',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculer la réduction potentielle avec les points
     * 
     * POST /api/v1/user/loyalty/calculate-discount
     */
    public function calculateLoyaltyDiscount(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_amount' => 'required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            
            $calculation = $this->loyaltyService->calculatePotentialDiscount(
                $user,
                $request->order_amount
            );
            
            return response()->json([
                'success' => true,
                'data' => $calculation
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
