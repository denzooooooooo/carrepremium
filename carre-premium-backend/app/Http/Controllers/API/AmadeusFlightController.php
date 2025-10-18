<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AmadeusService;
use App\Models\Booking;
use App\Models\FlightBooking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

/**
 * Contrôleur API pour les vols Amadeus
 * Gère toutes les interactions avec l'API Amadeus
 */
class AmadeusFlightController extends Controller
{
    protected $amadeusService;

    public function __construct(AmadeusService $amadeusService)
    {
        $this->amadeusService = $amadeusService;
    }

    /**
     * Rechercher des vols
     * 
     * POST /api/amadeus/flights/search
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchFlights(Request $request)
    {
        try {
            $validated = $request->validate([
                'origin' => 'required|string|size:3',
                'destination' => 'required|string|size:3',
                'departureDate' => 'required|date|after_or_equal:today',
                'returnDate' => 'nullable|date|after:departureDate',
                'adults' => 'required|integer|min:1|max:9',
                'children' => 'nullable|integer|min:0|max:9',
                'infants' => 'nullable|integer|min:0|max:9',
                'travelClass' => 'nullable|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST',
                'nonStop' => 'nullable|boolean',
                'currencyCode' => 'nullable|string|size:3',
                'max' => 'nullable|integer|min:1|max:250'
            ]);

            // Rechercher les vols via Amadeus
            $results = $this->amadeusService->searchFlights($validated);

            return response()->json([
                'success' => true,
                'data' => $results,
                'search_params' => $validated
            ]);

        } catch (Exception $e) {
            Log::error('Flight Search Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche de vols',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirmer le prix d'une offre de vol
     * 
     * POST /api/amadeus/flights/confirm-price
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmPrice(Request $request)
    {
        try {
            $validated = $request->validate([
                'flightOffer' => 'required|array'
            ]);

            $result = $this->amadeusService->confirmFlightPrice($validated['flightOffer']);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (Exception $e) {
            Log::error('Price Confirmation Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la confirmation du prix',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer une réservation de vol
     * 
     * POST /api/amadeus/bookings
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createBooking(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'flightOffer' => 'required|array',
                'travelers' => 'required|array|min:1',
                'travelers.*.firstName' => 'required|string',
                'travelers.*.lastName' => 'required|string',
                'travelers.*.dateOfBirth' => 'required|date',
                'travelers.*.gender' => 'required|in:MALE,FEMALE',
                'travelers.*.email' => 'required|email',
                'travelers.*.phone' => 'required|string',
                'travelers.*.documentType' => 'required|in:PASSPORT,IDENTITY_CARD',
                'travelers.*.documentNumber' => 'required|string',
                'travelers.*.documentExpiryDate' => 'required|date|after:today',
                'travelers.*.documentIssuanceCountry' => 'required|string|size:2',
                'travelers.*.nationality' => 'required|string|size:2',
                'contact' => 'required|array',
                'contact.firstName' => 'required|string',
                'contact.lastName' => 'required|string',
                'contact.email' => 'required|email',
                'contact.phone' => 'required|string',
                'user_id' => 'nullable|exists:users,id',
                'services' => 'nullable|array',
                'services.seats' => 'nullable|array',
                'services.baggage' => 'nullable|boolean',
                'services.meals' => 'nullable|array'
            ]);

            // Créer la réservation via Amadeus avec services auxiliaires
            $amadeusBooking = $this->amadeusService->createBooking(
                $validated['flightOffer'],
                $validated['travelers'],
                $validated['contact'],
                $validated['services'] ?? []
            );

            if (!$amadeusBooking['success']) {
                throw new Exception('Échec de la création de la réservation Amadeus');
            }

            // Créer l'enregistrement dans notre base de données
            $userId = $validated['user_id'] ?? (auth()->check() ? auth()->id() : null);
            
            $booking = Booking::create([
                'booking_number' => 'CP-' . strtoupper(Str::random(10)),
                'user_id' => $userId,
                'booking_type' => 'flight',
                'booking_date' => now(),
                'travel_date' => $validated['flightOffer']['itineraries'][0]['segments'][0]['departure']['at'] ?? null,
                'number_of_passengers' => count($validated['travelers']),
                'passenger_details' => json_encode($validated['travelers']),
                'total_amount' => $validated['flightOffer']['price']['total'],
                'currency' => $validated['flightOffer']['price']['currency'],
                'final_amount' => $validated['flightOffer']['price']['total'],
                'status' => 'confirmed',
                'payment_status' => 'pending',
                'confirmed_at' => now()
            ]);

            // Créer l'enregistrement FlightBooking avec les détails Amadeus
            $flightBooking = FlightBooking::create([
                'booking_id' => $booking->id,
                'pnr_number' => $amadeusBooking['pnr'],
                'amadeus_booking_id' => $amadeusBooking['booking_reference'],
                'eticket_numbers' => json_encode($amadeusBooking['eticket_numbers']),
                'flight_offer_data' => json_encode($validated['flightOffer']),
                'booking_status' => 'confirmed',
                'issued_at' => now(),
                'services_data' => json_encode($validated['services'] ?? [])
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Réservation créée avec succès',
                'data' => [
                    'booking' => $booking,
                    'flight_booking' => $flightBooking,
                    'pnr' => $amadeusBooking['pnr'],
                    'etickets' => $amadeusBooking['eticket_numbers']
                ]
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Booking Creation Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la réservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les détails d'une réservation
     * 
     * GET /api/amadeus/bookings/{id}
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBooking($id)
    {
        try {
            $booking = Booking::with('flightBooking')->findOrFail($id);

            // Vérifier que c'est bien une réservation de vol
            if ($booking->booking_type !== 'flight') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette réservation n\'est pas un vol'
                ], 400);
            }

            // Récupérer les détails depuis Amadeus si disponible
            $amadeusDetails = null;
            if ($booking->flightBooking && $booking->flightBooking->amadeus_booking_id) {
                try {
                    $amadeusDetails = $this->amadeusService->getBookingDetails(
                        $booking->flightBooking->amadeus_booking_id
                    );
                } catch (Exception $e) {
                    Log::warning('Could not fetch Amadeus details: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'booking' => $booking,
                    'amadeus_details' => $amadeusDetails
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Get Booking Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Réservation non trouvée',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Annuler une réservation
     * 
     * DELETE /api/amadeus/bookings/{id}
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelBooking(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $booking = Booking::with('flightBooking')->findOrFail($id);

            // Vérifier que la réservation peut être annulée
            if ($booking->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette réservation est déjà annulée'
                ], 400);
            }

            // Annuler via Amadeus si possible
            if ($booking->flightBooking && $booking->flightBooking->amadeus_booking_id) {
                try {
                    $this->amadeusService->cancelBooking(
                        $booking->flightBooking->amadeus_booking_id
                    );
                } catch (Exception $e) {
                    Log::warning('Amadeus cancellation failed: ' . $e->getMessage());
                    // Continuer quand même l'annulation locale
                }
            }

            // Mettre à jour le statut dans notre base
            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->input('reason', 'Annulation client')
            ]);

            if ($booking->flightBooking) {
                $booking->flightBooking->update([
                    'booking_status' => 'cancelled'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Réservation annulée avec succès',
                'data' => $booking
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Booking Cancellation Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rechercher des aéroports
     * 
     * GET /api/amadeus/airports/search?keyword=paris
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAirports(Request $request)
    {
        try {
            $validated = $request->validate([
                'keyword' => 'required|string|min:2'
            ]);

            $results = $this->amadeusService->searchAirports($validated['keyword']);

            return response()->json([
                'success' => true,
                'data' => $results
            ]);

        } catch (Exception $e) {
            Log::error('Airport Search Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche d\'aéroports',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les réservations de l'utilisateur connecté
     * 
     * GET /api/amadeus/my-bookings
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyBookings(Request $request)
    {
        try {
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié'
                ], 401);
            }
            
            $user = auth()->user();

            $bookings = Booking::with(['flightBooking', 'payment'])
                ->where('user_id', $user->id)
                ->where('booking_type', 'flight')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $bookings
            ]);

        } catch (Exception $e) {
            Log::error('Get My Bookings Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des réservations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les détails d'une offre de vol spécifique
     * 
     * GET /api/amadeus/flights/offer/{offerId}
     * 
     * @param string $offerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFlightOffer($offerId)
    {
        try {
            // Note: Amadeus ne permet pas de récupérer une offre par ID
            // Les offres doivent être stockées temporairement côté client
            // ou refaire une recherche
            
            return response()->json([
                'success' => false,
                'message' => 'Les offres de vol doivent être récupérées via une nouvelle recherche',
                'info' => 'Amadeus ne permet pas de récupérer une offre par ID. Veuillez refaire une recherche ou stocker l\'offre côté client.'
            ], 400);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques de vols pour l'admin
     * 
     * GET /api/amadeus/admin/statistics
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistics(Request $request)
    {
        try {
            $startDate = $request->input('start_date', now()->subDays(30));
            $endDate = $request->input('end_date', now());

            $stats = [
                'total_bookings' => Booking::where('booking_type', 'flight')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count(),
                
                'confirmed_bookings' => Booking::where('booking_type', 'flight')
                    ->where('status', 'confirmed')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count(),
                
                'cancelled_bookings' => Booking::where('booking_type', 'flight')
                    ->where('status', 'cancelled')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count(),
                
                'total_revenue' => Booking::where('booking_type', 'flight')
                    ->where('payment_status', 'paid')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('final_amount'),
                
                'total_passengers' => Booking::where('booking_type', 'flight')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('number_of_passengers'),
                
                'average_booking_value' => Booking::where('booking_type', 'flight')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->avg('final_amount'),
                
                'bookings_by_status' => Booking::where('booking_type', 'flight')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select('status', DB::raw('count(*) as count'))
                    ->groupBy('status')
                    ->get(),
                
                'bookings_by_day' => Booking::where('booking_type', 'flight')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'), DB::raw('sum(final_amount) as revenue'))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'period' => [
                    'start' => $startDate,
                    'end' => $endDate
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Statistics Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
