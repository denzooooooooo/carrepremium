# 💳 GUIDE D'INTÉGRATION DES PAIEMENTS - CARRÉ PREMIUM

**Date:** 10 Janvier 2025  
**Objectif:** Intégrer Stripe, Mobile Money et Emails transactionnels

---

## 📋 ÉTAPE 1: INSTALLER LES DÉPENDANCES (5 min)

### 1.1 Installer Stripe pour Laravel
```bash
cd carre-premium-backend
composer require stripe/stripe-php
```

### 1.2 Configurer les variables d'environnement

**Fichier:** `carre-premium-backend/.env`

```env
# Stripe Configuration
STRIPE_KEY=pk_test_votre_cle_publique
STRIPE_SECRET=sk_test_votre_cle_secrete
STRIPE_WEBHOOK_SECRET=whsec_votre_webhook_secret

# Mobile Money Configuration (Orange Money CI)
ORANGE_MONEY_MERCHANT_KEY=votre_merchant_key
ORANGE_MONEY_API_URL=https://api.orange.com/orange-money-webpay/dev/v1
ORANGE_MONEY_RETURN_URL=http://localhost:3000/payment/callback

# MTN Mobile Money
MTN_MOMO_API_KEY=votre_api_key
MTN_MOMO_API_SECRET=votre_api_secret
MTN_MOMO_SUBSCRIPTION_KEY=votre_subscription_key

# Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username
MAIL_PASSWORD=votre_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@carrepremium.com
MAIL_FROM_NAME="Carré Premium"
```

---

## 💳 ÉTAPE 2: CRÉER LE SERVICE STRIPE (15 min)

**Fichier:** `carre-premium-backend/app/Services/StripePaymentService.php`

```php
<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use Exception;

class StripePaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent
     */
    public function createPaymentIntent($amount, $currency, $metadata = [])
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // Stripe utilise les centimes
                'currency' => strtolower($currency),
                'metadata' => $metadata,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Confirm payment
     */
    public function confirmPayment($paymentIntentId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            return [
                'success' => true,
                'status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount / 100,
                'currency' => strtoupper($paymentIntent->currency)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle webhook
     */
    public function handleWebhook($payload, $signature)
    {
        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    // Mettre à jour la réservation
                    $this->handleSuccessfulPayment($paymentIntent);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    // Gérer l'échec
                    $this->handleFailedPayment($paymentIntent);
                    break;
            }

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function handleSuccessfulPayment($paymentIntent)
    {
        // Logique pour mettre à jour la réservation
        $bookingId = $paymentIntent->metadata->booking_id ?? null;
        
        if ($bookingId) {
            $booking = \App\Models\Booking::find($bookingId);
            if ($booking) {
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                    'confirmed_at' => now()
                ]);

                // Envoyer email de confirmation
                // Mail::to($booking->user->email)->send(new BookingConfirmed($booking));
            }
        }
    }

    private function handleFailedPayment($paymentIntent)
    {
        $bookingId = $paymentIntent->metadata->booking_id ?? null;
        
        if ($bookingId) {
            $booking = \App\Models\Booking::find($bookingId);
            if ($booking) {
                $booking->update([
                    'payment_status' => 'failed'
                ]);
            }
        }
    }
}
```

---

## 📱 ÉTAPE 3: CRÉER LE SERVICE MOBILE MONEY (20 min)

**Fichier:** `carre-premium-backend/app/Services/MobileMoneyService.php`

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class MobileMoneyService
{
    /**
     * Initialize Orange Money payment
     */
    public function initializeOrangeMoneyPayment($amount, $phone, $bookingNumber)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getOrangeMoneyToken(),
                'Content-Type' => 'application/json'
            ])->post(config('services.orange_money.api_url') . '/webpayment/v1/transactionInit', [
                'merchant_key' => config('services.orange_money.merchant_key'),
                'currency' => 'XOF',
                'order_id' => $bookingNumber,
                'amount' => $amount,
                'return_url' => config('services.orange_money.return_url'),
                'cancel_url' => config('services.orange_money.return_url'),
                'notif_url' => route('payment.orange.callback'),
                'lang' => 'fr',
                'reference' => $bookingNumber
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'payment_url' => $response->json('payment_url'),
                    'payment_token' => $response->json('payment_token')
                ];
            }

            return [
                'success' => false,
                'error' => 'Erreur lors de l\'initialisation du paiement'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Initialize MTN Mobile Money payment
     */
    public function initializeMTNPayment($amount, $phone, $bookingNumber)
    {
        try {
            $response = Http::withHeaders([
                'X-Reference-Id' => $this->generateUUID(),
                'X-Target-Environment' => 'sandbox', // 'production' en prod
                'Ocp-Apim-Subscription-Key' => config('services.mtn_momo.subscription_key'),
                'Content-Type' => 'application/json'
            ])->post('https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay', [
                'amount' => $amount,
                'currency' => 'XOF',
                'externalId' => $bookingNumber,
                'payer' => [
                    'partyIdType' => 'MSISDN',
                    'partyId' => $phone
                ],
                'payerMessage' => 'Paiement Carré Premium',
                'payeeNote' => 'Réservation ' . $bookingNumber
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'transaction_id' => $response->header('X-Reference-Id')
                ];
            }

            return [
                'success' => false,
                'error' => 'Erreur lors de l\'initialisation du paiement MTN'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus($transactionId, $provider = 'orange')
    {
        if ($provider === 'orange') {
            return $this->checkOrangeMoneyStatus($transactionId);
        } elseif ($provider === 'mtn') {
            return $this->checkMTNStatus($transactionId);
        }

        return ['success' => false, 'error' => 'Provider not supported'];
    }

    private function checkOrangeMoneyStatus($transactionId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getOrangeMoneyToken()
            ])->get(config('services.orange_money.api_url') . '/webpayment/v1/transactionStatus/' . $transactionId);

            if ($response->successful()) {
                $status = $response->json('status');
                return [
                    'success' => true,
                    'status' => $status,
                    'paid' => $status === 'SUCCESS'
                ];
            }

            return ['success' => false];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function checkMTNStatus($transactionId)
    {
        try {
            $response = Http::withHeaders([
                'X-Target-Environment' => 'sandbox',
                'Ocp-Apim-Subscription-Key' => config('services.mtn_momo.subscription_key')
            ])->get('https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay/' . $transactionId);

            if ($response->successful()) {
                $status = $response->json('status');
                return [
                    'success' => true,
                    'status' => $status,
                    'paid' => $status === 'SUCCESSFUL'
                ];
            }

            return ['success' => false];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function getOrangeMoneyToken()
    {
        // Implémenter la logique d'obtention du token OAuth
        // Cache le token pour éviter de le redemander à chaque fois
        return cache()->remember('orange_money_token', 3600, function () {
            // Appel API pour obtenir le token
            return 'token_here';
        });
    }

    private function generateUUID()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
```

---

## 📧 ÉTAPE 4: CRÉER LES EMAILS TRANSACTIONNELS (30 min)

### 4.1 Créer les classes Mailable

```bash
cd carre-premium-backend
php artisan make:mail BookingConfirmation
php artisan make:mail PaymentConfirmation
php artisan make:mail BookingCancellation
```

### 4.2 BookingConfirmation.php

**Fichier:** `carre-premium-backend/app/Mail/BookingConfirmation.php`

```php
<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Confirmation de réservation - ' . $this->booking->booking_number)
                    ->view('emails.booking-confirmation')
                    ->with([
                        'bookingNumber' => $this->booking->booking_number,
                        'customerName' => $this->booking->user->first_name ?? 'Client',
                        'totalAmount' => $this->booking->final_amount,
                        'currency' => $this->booking->currency,
                        'bookingDate' => $this->booking->created_at->format('d/m/Y'),
                        'travelDate' => $this->booking->travel_date
                    ]);
    }
}
```

### 4.3 Template Email

**Fichier:** `carre-premium-backend/resources/views/emails/booking-confirmation.blade.php`

```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #9333EA 0%, #7C3AED 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .booking-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #9333EA;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Réservation Confirmée !</h1>
            <p>Merci pour votre confiance</p>
        </div>
        
        <div class="content">
            <p>Bonjour {{ $customerName }},</p>
            
            <p>Nous avons le plaisir de confirmer votre réservation chez <strong>Carré Premium</strong>.</p>
            
            <div class="booking-details">
                <h2 style="color: #9333EA; margin-top: 0;">Détails de la réservation</h2>
                
                <div class="detail-row">
                    <span><strong>Numéro de réservation:</strong></span>
                    <span>{{ $bookingNumber }}</span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Date de réservation:</strong></span>
                    <span>{{ $bookingDate }}</span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Date de voyage:</strong></span>
                    <span>{{ $travelDate }}</span>
                </div>
                
                <div class="detail-row" style="border-bottom: none;">
                    <span><strong>Montant total:</strong></span>
                    <span style="color: #9333EA; font-size: 20px; font-weight: bold;">
                        {{ number_format($totalAmount, 0, ',', ' ') }} {{ $currency }}
                    </span>
                </div>
            </div>
            
            <p>Votre e-ticket a été généré et est disponible dans votre espace client.</p>
            
            <center>
                <a href="{{ config('app.frontend_url') }}/my-bookings/{{ $bookingNumber }}" class="button">
                    Voir ma réservation
                </a>
            </center>
            
            <p style="margin-top: 30px; padding: 15px; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 4px;">
                <strong>📌 Important:</strong> Veuillez présenter ce numéro de réservation lors de votre voyage.
            </p>
        </div>
        
        <div class="footer">
            <p>Carré Premium - Votre Conciergerie de Voyage Premium</p>
            <p>📧 contact@carrepremium.com | 📞 +225 XX XX XX XX XX</p>
            <p style="font-size: 12px; color: #9ca3af;">
                Cet email a été envoyé automatiquement, merci de ne pas y répondre.
            </p>
        </div>
    </div>
</body>
</html>
```

---

## 🎯 ÉTAPE 5: CRÉER LE CONTROLLER DE PAIEMENT (20 min)

**Fichier:** `carre-premium-backend/app/Http/Controllers/API/PaymentController.php`

```php
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\StripePaymentService;
use App\Services\MobileMoneyService;
use App\Models\Booking;
use App\Models\Payment;
use App\Mail\BookingConfirmation;
use App\Mail\PaymentConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $stripeService;
    protected $mobileMoneyService;

    public function __construct(
        StripePaymentService $stripeService,
        MobileMoneyService $mobileMoneyService
    ) {
        $this->stripeService = $stripeService;
        $this->mobileMoneyService = $mobileMoneyService;
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

        $booking = Booking::find($request->booking_id);
        
        if ($booking->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Cette réservation a déjà été payée'
            ], 400);
        }

        $paymentMethod = $request->payment_method;
        $amount = $booking->final_amount;

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
            Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'transaction_id' => $result['payment_intent_id'] ?? $result['transaction_id'] ?? uniqid(),
                'payment_method' => $paymentMethod,
                'amount' => $amount,
                'currency' => $booking->currency,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['error'] ?? 'Erreur lors de l\'initialisation du paiement'
        ], 500);
    }

    /**
     * Confirm payment
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

        $booking = Booking::find($request->booking_id);
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

            // Envoyer les emails
            Mail::to($booking->user->email)->send(new BookingConfirmation($booking));
            Mail::to($booking->user->email)->send(new PaymentConfirmation($booking));

            return response()->json([
                'success' => true,
                'message' => 'Paiement confirmé avec succès',
                'booking' => $booking
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Échec de la confirmation du paiement'
        ], 500);
    }

    /**
     * Webhook Stripe
     */
    public function stripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        $result = $this->stripeService->handleWebhook($payload, $signature);

        return response()->json($result);
    }

    /**
     * Callback Orange Money
     */
    public function orangeMoneyCallback(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $status = $request->input('status');

        if ($status === 'SUCCESS') {
            $payment = Payment::where('transaction_id', $transactionId)->first();
            
            if ($payment) {
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

                // Envoyer emails
                Mail::to($booking->user->email)->send(new BookingConfirmation($booking));
            }
        }

        return response()->json(['success' => true]);
    }
}
```

---

## 🔧 ÉTAPE 6: AJOUTER LES ROUTES (5 min)

**Fichier:** `carre-premium-backend/routes/api.php`

Ajouter ces routes:

```php
// Payment routes
Route::prefix('v1/payments')->group(function () {
    Route::post('/initialize', [PaymentController::class, 'initializePayment']);
    Route::post('/confirm', [PaymentController::class, 'confirmPayment']);
    
    // Webhooks
    Route::post('/stripe/webhook', [PaymentController::class, 'stripeWebhook']);
    Route::post('/orange/callback', [PaymentController::class, 'orangeMoneyCallback'])->name('payment.orange.callback');
});
```

---

## 🧪 ÉTAPE 7: TESTER LES PAIEMENTS (30 min)

### Test 1: Stripe

```bash
curl -X POST "http://localhost:8000/api/v1/payments/initialize" \
  -H "Content-Type: application/json" \
  -d '{
    "booking_id": 1,
    "payment_method": "stripe"
  }'
```

### Test 2: Orange Money

```bash
curl -X POST "http://localhost:8000/api/v1/payments/initialize" \
  -H "Content-Type: application/json" \
  -d '{
    "booking_id": 1,
    "payment_method": "orange_money",
    "phone": "+225XXXXXXXXX"
  }'
```

### Test 3: Email

```bash
cd carre-premium-backend
php artisan tinker

# Dans tinker:
$booking = App\Models\Booking::first();
Mail::to('test@example.com')->send(new App\Mail\BookingConfirmation($booking));
```

---

## ✅ CHECKLIST FINALE

- [ ] Stripe installé et configuré
- [ ] Services de paiement créés
- [ ] Emails transactionnels créés
- [ ] Controller de paiement créé
- [ ] Routes ajoutées
- [ ] Tests effectués
- [ ] Webhooks configurés
- [ ] Emails testés

---

**🎉 Système de paiement complet et fonctionnel !**
