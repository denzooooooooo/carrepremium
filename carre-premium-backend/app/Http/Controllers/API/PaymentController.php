<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\StripePaymentService;
use App\Services\MobileMoneyService;
use App\Services\LoyaltyService;
use App\Models\Booking;
use App\Models\Payment;
use App\Mail\BookingConfirmation;
use App\Mail\PaymentConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $stripeService;
    protected $mobileMoneyService;
    protected $loyaltyService;

    public function __construct(
        StripePaymentService $stripeService,
        MobileMoneyService $mobileMoneyService,
        LoyaltyService $loyaltyService
    ) {
        $this->stripeService = $stripeService;
        $this->mobileMoneyService = $mobileMoneyService;
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Initialize payment
     */
    public function initializePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|in:stripe,orange_money,mtn_momo',
            'phone' => 'required_if:payment_method,orange_money,mtn_momo'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $booking = Booking::with('user')->find($request->booking_id);
            
            if ($booking->payment_status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette réservation a déjà été payée'
                ], 400);
            }

            $paymentMethod = $request->payment_method;
            $amount = $booking->final_amount;

            $result = null;

            switch ($paymentMethod) {
                case 'stripe':
                    $result = $this->stripeService->createPaymentIntent(
                        $amount,
                        $booking->currency,
                        ['booking_id' => $booking->id]
                    );
                    break;

                case 'orange_money':
                    $result = $this->mobileMoneyService->initializeOrangeMoneyPayment(
                        $amount,
                        $request->phone,
                        $booking->booking_number
                    );
                    break;

                case 'mtn_momo':
                    $result = $this->mobileMoneyService->initializeMTNPayment(
                        $amount,
                        $request->phone,
                        $booking->booking_number
                    );
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Méthode de paiement non supportée'
                    ], 400);
            }

            if ($result['success']) {
                // Créer l'enregistrement de paiement
                $payment = Payment::create([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'transaction_id' => $result['payment_intent_id'] ?? $result['transaction_id'] ?? uniqid('PAY_'),
                    'payment_method' => $paymentMethod,
                    'amount' => $amount,
                    'currency' => $booking->currency,
                    'status' => 'pending',
                    'payment_details' => json_encode($result)
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement initialisé avec succès',
                    'data' => $result,
                    'payment_id' => $payment->id
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Erreur lors de l\'initialisation du paiement'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Payment initialization error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'initialisation du paiement'
            ], 500);
        }
    }

    /**
     * Confirm payment (Stripe)
     */
    public function confirmPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'payment_intent_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $booking = Booking::with('user')->find($request->booking_id);
            $result = $this->stripeService->confirmPayment($request->payment_intent_id);

            if ($result['success'] && $result['status'] === 'succeeded') {
                // Mettre à jour la réservation
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                    'confirmed_at' => now()
                ]);

                // Mettre à jour le paiement
                Payment::where('booking_id', $booking->id)
                    ->where('transaction_id', $request->payment_intent_id)
                    ->update([
                        'status' => 'completed',
                        'payment_date' => now()
                    ]);

                // Attribuer les points de fidélité
                $pointsAwarded = 0;
                if ($booking->user_id) {
                    $pointsAwarded = $this->loyaltyService->awardPoints($booking);
                }

                // Envoyer les emails
                try {
                    Mail::to($booking->user->email)->send(new BookingConfirmation($booking));
                    Mail::to($booking->user->email)->send(new PaymentConfirmation($booking));
                } catch (\Exception $e) {
                    Log::error('Email sending failed: ' . $e->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement confirmé avec succès',
                    'booking' => $booking,
                    'loyalty_points_awarded' => $pointsAwarded
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Échec de la confirmation du paiement',
                'status' => $result['status'] ?? 'unknown'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Payment confirmation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la confirmation du paiement'
            ], 500);
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required',
            'provider' => 'required|in:orange,mtn'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->mobileMoneyService->checkPaymentStatus(
                $request->transaction_id,
                $request->provider
            );

            if ($result['success'] && $result['paid']) {
                // Mettre à jour le paiement et la réservation
                $payment = Payment::where('transaction_id', $request->transaction_id)->first();
                
                if ($payment && $payment->status !== 'completed') {
                    $payment->update([
                        'status' => 'completed',
                        'payment_date' => now()
                    ]);

                    $booking = $payment->booking;
                    $booking->update([
                        'payment_status' => 'paid',
                        'status' => 'confirmed',
                        'confirmed_at' => now()
                    ]);

                    // Attribuer les points de fidélité
                    if ($booking->user_id) {
                        $this->loyaltyService->awardPoints($booking);
                    }

                    // Envoyer emails
                    try {
                        Mail::to($booking->user->email)->send(new BookingConfirmation($booking));
                        Mail::to($booking->user->email)->send(new PaymentConfirmation($booking));
                    } catch (\Exception $e) {
                        Log::error('Email sending failed: ' . $e->getMessage());
                    }
                }
            }

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Payment status check error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification du statut'
            ], 500);
        }
    }

    /**
     * Webhook Stripe
     */
    public function stripeWebhook(Request $request)
    {
        try {
            $payload = $request->getContent();
            $signature = $request->header('Stripe-Signature');

            $result = $this->stripeService->handleWebhook($payload, $signature);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Callback Orange Money
     */
    public function orangeMoneyCallback(Request $request)
    {
        try {
            $transactionId = $request->input('transaction_id');
            $status = $request->input('status');

            Log::info('Orange Money callback received', [
                'transaction_id' => $transactionId,
                'status' => $status
            ]);

            $result = $this->mobileMoneyService->handleOrangeMoneyCallback($transactionId, $status);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Orange Money callback error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get payment methods
     */
    public function getPaymentMethods()
    {
        return response()->json([
            'success' => true,
            'data' => [
                [
                    'id' => 'stripe',
                    'name' => 'Carte bancaire',
                    'description' => 'Visa, Mastercard, American Express',
                    'icon' => '💳',
                    'enabled' => true
                ],
                [
                    'id' => 'orange_money',
                    'name' => 'Orange Money',
                    'description' => 'Paiement mobile Orange',
                    'icon' => '📱',
                    'enabled' => true
                ],
                [
                    'id' => 'mtn_momo',
                    'name' => 'MTN Mobile Money',
                    'description' => 'Paiement mobile MTN',
                    'icon' => '📱',
                    'enabled' => true
                ]
            ]
        ]);
    }

    /**
     * Get payment history for a booking
     */
    public function getPaymentHistory($bookingId)
    {
        try {
            $payments = Payment::where('booking_id', $bookingId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $payments
            ]);

        } catch (\Exception $e) {
            Log::error('Get payment history error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'historique'
            ], 500);
        }
    }
}
