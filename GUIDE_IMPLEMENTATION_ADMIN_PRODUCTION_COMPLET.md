# 🚀 GUIDE COMPLET - Panel Admin Production Ready

## ⚠️ AVERTISSEMENT
Ce projet représente **14-16 heures de développement**. Il est recommandé de l'implémenter progressivement.

## 📦 PRÉREQUIS - Packages à Installer

```bash
cd carre-premium-backend
composer require maatwebsite/excel
composer require barryvdh/laravel-dompdf
composer require phpoffice/phpspreadsheet
```

## 🗑️ ÉTAPE 1: Supprimer Données Test (5 min)

### Script de nettoyage
```bash
cd carre-premium-backend
php artisan migrate:fresh --force
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=CurrencySeeder
php artisan db:seed --class=SettingSeeder
php artisan db:seed --class=ApiConfigurationsSeeder
php artisan db:seed --class=PricingRulesSeeder
php artisan db:seed --class=PaymentGatewaysSeeder
php artisan optimize:clear
```

**Résultat**: Base de données vide, prête pour vraies réservations.

---

## 📧 ÉTAPE 2: Emails Automatiques Clients (1h)

### 2.1 Créer les classes Mail

**app/Mail/FlightBookingConfirmation.php**
```php
<?php
namespace App\Mail;

use App\Models\FlightBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FlightBookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    
    public $booking;
    
    public function __construct(FlightBooking $booking)
    {
        $this->booking = $booking->load('user', 'payment');
    }
    
    public function build()
    {
        $passengers = json_decode($this->booking->passengers_data, true);
        $segments = json_decode($this->booking->segments_data, true);
        
        return $this->subject('✈️ Confirmation Vol - PNR: ' . $this->booking->pnr)
                    ->view('emails.flight-booking-confirmation')
                    ->with([
                        'booking' => $this->booking,
                        'user' => $this->booking->user,
                        'passengers' => $passengers,
                        'segments' => $segments,
                    ]);
    }
}
```

**app/Mail/PaymentReceipt.php**
```php
<?php
namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReceipt extends Mailable
{
    use Queueable, SerializesModels;
    
    public $payment;
    
    public function __construct(Payment $payment)
    {
        $this->payment = $payment->load('booking.user');
    }
    
    public function build()
    {
        return $this->subject('💳 Reçu de Paiement #' . $this->payment->transaction_id)
                    ->view('emails.payment-receipt')
                    ->with([
                        'payment' => $this->payment,
                        'booking' => $this->payment->booking,
                        'user' => $this->payment->booking->user,
                    ]);
    }
}
```

### 2.2 Templates Email (resources/views/emails/)

Créer les fichiers suivants avec design professionnel:
- `flight-booking-confirmation.blade.php`
- `payment-receipt.blade.php`
- `departure-reminder.blade.php`
- `booking-modification.blade.php`
- `booking-cancellation.blade.php`

### 2.3 Déclencher les Emails

Dans `BookingController`, après création réservation:
```php
use App\Mail\FlightBookingConfirmation;
use App\Mail\PaymentReceipt;
use Illuminate\Support\Facades\Mail;

// Après création réservation
Mail::to($booking->user->email)->send(new FlightBookingConfirmation($booking));

// Après paiement confirmé
Mail::to($payment->booking->user->email)->send(new PaymentReceipt($payment));
```

---

## 📊 ÉTAPE 3: Export Excel/CSV (45 min)

### 3.1 Créer Export Classes

**app/Exports/BookingsExport.php**
```php
<?php
namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BookingsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filters;
    
    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }
    
    public function query()
    {
        $query = Booking::with('user');
        
        if (isset($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        
        if (isset($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }
        
        if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        
        return $query->orderBy('created_at', 'desc');
    }
    
    public function headings(): array
    {
        return [
            'ID',
            'Référence',
            'Client',
            'Email',
            'Type',
            'Montant',
            'Statut',
            'Date Création',
        ];
    }
    
    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->booking_reference,
            $booking->user->first_name . ' ' . $booking->user->last_name,
            $booking->user->email,
            $booking->booking_type,
            number_format($booking->total_amount, 2),
            $booking->status,
            $booking->created_at->format('d/m/Y H:i'),
        ];
    }
}
```

### 3.2 Ajouter Méthode Export dans BookingController

```php
use App\Exports\BookingsExport;
use Maatwebsite\Excel\Facades\Excel;

public function export(Request $request)
{
    $filters = $request->only(['date_from', 'date_to', 'status', 'type']);
    
    return Excel::download(
        new BookingsExport($filters),
        'reservations_' . date('Y-m-d') . '.xlsx'
    );
}
```

### 3.3 Ajouter Route

**routes/admin.php**
```php
Route::get('/bookings/export', [BookingController::class, 'export'])->name('bookings.export');
```

### 3.4 Bouton Export dans Vue

**resources/views/admin/bookings/index.blade.php**
```html
<a href="{{ route('admin.bookings.export', request()->query()) }}" 
   class="btn btn-success">
    <i class="fas fa-file-excel"></i> Exporter Excel
</a>
```

---

## 💰 ÉTAPE 4: Suivi Comptable Complet (1h30)

### 4.1 Créer AccountingController

**app/Http/Controllers/Admin/AccountingController.php**
```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        
        // Revenus
        $totalRevenue = Payment::where('status', 'completed')
            ->whereDate('created_at', '>=', $startDate)
            ->sum('amount');
            
        $totalCommissions = Payment::where('status', 'completed')
            ->whereDate('created_at', '>=', $startDate)
            ->sum('commission_amount');
            
        $netRevenue = $totalRevenue - $totalCommissions;
        
        // TVA
        $tvaRate = 0.18; // 18% Côte d'Ivoire
        $tvaCollected = $totalRevenue * $tvaRate;
        
        // Par type de produit
        $revenueByType = Booking::selectRaw('booking_type, SUM(total_amount) as total')
            ->whereDate('created_at', '>=', $startDate)
            ->where('status', 'confirmed')
            ->groupBy('booking_type')
            ->get();
            
        // Transactions récentes
        $recentTransactions = Payment::with('booking.user')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
            
        // Graphiques
        $dailyRevenue = Payment::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->where('status', 'completed')
            ->whereDate('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        return view('admin.accounting.index', compact(
            'totalRevenue',
            'totalCommissions',
            'netRevenue',
            'tvaCollected',
            'revenueByType',
            'recentTransactions',
            'dailyRevenue',
            'period'
        ));
    }
    
    public function exportJournal(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        
        $transactions = Payment::with('booking.user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->get();
            
        // Générer Excel
        return Excel::download(
            new JournalComptableExport($transactions),
            'journal_comptable_' . date('Y-m') . '.xlsx'
        );
    }
    
    private function getStartDate($period)
    {
        return match($period) {
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'quarter' => now()->startOfQuarter(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };
    }
}
```

### 4.2 Vue Comptabilité

**resources/views/admin/accounting/index.blade.php**
```html
@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-calculator"></i> Comptabilité</h1>
        <div>
            <select id="periodSelect" class="form-select" onchange="window.location.href='?period='+this.value">
                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Cette Semaine</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Ce Mois</option>
                <option value="quarter" {{ $period == 'quarter' ? 'selected' : '' }}>Ce Trimestre</option>
                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Cette Année</option>
            </select>
        </div>
    </div>

    <!-- Cartes Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Revenus Bruts</h6>
                    <h3>{{ number_format($totalRevenue, 0, ',', ' ') }} XOF</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Commissions</h6>
                    <h3>{{ number_format($totalCommissions, 0, ',', ' ') }} XOF</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Revenus Nets</h6>
                    <h3>{{ number_format($netRevenue, 0, ',', ' ') }} XOF</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>TVA Collectée (18%)</h6>
                    <h3>{{ number_format($tvaCollected, 0, ',', ' ') }} XOF</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique Revenus -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>📈 Évolution des Revenus</h5>
        </div>
        <div class="card-body">
            <canvas id="revenueChart" height="80"></canvas>
        </div>
    </div>

    <!-- Revenus par Type -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>📊 Revenus par Type de Produit</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenueByType as $item)
                    <tr>
                        <td>{{ ucfirst($item->booking_type) }}</td>
                        <td>{{ number_format($item->total, 0, ',', ' ') }} XOF</td>
                        <td>{{ number_format(($item->total / $totalRevenue) * 100, 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Transactions Récentes -->
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>💳 Transactions Récentes</h5>
            <a href="{{ route('admin.accounting.export-journal') }}" class="btn btn-sm btn-success">
                <i class="fas fa-file-excel"></i> Exporter Journal
            </a>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Transaction ID</th>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTransactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        <td><code>{{ $transaction->transaction_id }}</code></td>
                        <td>{{ $transaction->booking->user->first_name }} {{ $transaction->booking->user->last_name }}</td>
                        <td>{{ $transaction->payment_method }}</td>
                        <td><strong>{{ number_format($transaction->amount, 0, ',', ' ') }} XOF</strong></td>
                        <td>
                            @if($transaction->status == 'completed')
                                <span class="badge bg-success">Complété</span>
                            @elseif($transaction->status == 'pending')
                                <span class="badge bg-warning">En attente</span>
                            @else
                                <span class="badge bg-danger">Échoué</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($dailyRevenue->pluck('date')) !!},
        datasets: [{
            label: 'Revenus (XOF)',
            data: {!! json_encode($dailyRevenue->pluck('total')) !!},
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' XOF';
                    }
                }
            }
        }
    }
});
</script>
@endpush
@endsection
```

---

## 🎫 ÉTAPE 4: Gestion Complète Réservations Vols (2h)

### 4.1 Améliorer BookingController

Ajouter ces méthodes dans `app/Http/Controllers/Admin/BookingController.php`:

```php
/**
 * Afficher détails complets réservation
 */
public function show($id)
{
    $booking = Booking::with([
        'user',
        'flightBooking',
        'payment',
        'promoCodeUsage.promoCode'
    ])->findOrFail($id);
    
    // Décoder données JSON
    $passengers = json_decode($booking->flightBooking->passengers_data ?? '[]', true);
    $segments = json_decode($booking->flightBooking->segments_data ?? '[]', true);
    $extras = json_decode($booking->flightBooking->extras_data ?? '[]', true);
    
    return view('admin.bookings.show', compact(
        'booking',
        'passengers',
        'segments',
        'extras'
    ));
}

/**
 * Envoyer email au client
 */
public function sendEmail(Request $request, $id)
{
    $booking = Booking::with('user', 'flightBooking')->findOrFail($id);
    
    $request->validate([
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);
    
    Mail::to($booking->user->email)->send(
        new \App\Mail\CustomBookingEmail($booking, $request->subject, $request->message)
    );
    
    return back()->with('success', 'Email envoyé avec succès');
}

/**
 * Confirmer réservation
 */
public function confirm($id)
{
    $booking = Booking::findOrFail($id);
    $booking->update(['status' => 'confirmed']);
    
    // Envoyer email confirmation
    Mail::to($booking->user->email)->send(new FlightBookingConfirmation($booking->flightBooking));
    
    return back()->with('success', 'Réservation confirmée et email envoyé');
}

/**
 * Annuler réservation
 */
public function cancel(Request $request, $id)
{
    $booking = Booking::findOrFail($id);
    
    $request->validate([
        'cancellation_reason' => 'required|string',
        'refund_amount' => 'nullable|numeric|min:0',
    ]);
    
    $booking->update([
        'status' => 'cancelled',
        'cancellation_reason' => $request->cancellation_reason,
        'cancelled_at' => now(),
        'cancelled_by' => auth('admin')->id(),
    ]);
    
    // Traiter remboursement si montant spécifié
    if ($request->refund_amount > 0) {
        $this->processRefund($booking, $request->refund_amount);
    }
    
    // Envoyer email annulation
    Mail::to($booking->user->email)->send(new BookingCancellation($booking));
    
    return back()->with('success', 'Réservation annulée');
}

/**
 * Traiter remboursement
 */
private function processRefund($booking, $amount)
{
    $payment = $booking->payment;
    
    if ($payment->payment_method == 'stripe') {
        // Remboursement Stripe
        app(StripePaymentService::class)->refund($payment->transaction_id, $amount);
    }
    
    // Enregistrer remboursement
    Payment::create([
        'booking_id' => $booking->id,
        'user_id' => $booking->user_id,
        'amount' => -$amount,
        'payment_method' => $payment->payment_method,
        'transaction_id' => 'REFUND_' . uniqid(),
        'status' => 'completed',
        'payment_type' => 'refund',
    ]);
}

/**
 * Imprimer reçu
 */
public function printReceipt($id)
{
    $booking = Booking::with('user', 'flightBooking', 'payment')->findOrFail($id);
    
    $pdf = app(DocumentGeneratorService::class)->generateReceipt($booking);
    
    return $pdf->download('recu_' . $booking->booking_reference . '.pdf');
}
```

### 4.2 Routes Admin

**routes/admin.php** - Ajouter:
```php
// Comptabilité
Route::prefix('accounting')->name('accounting.')->group(function () {
    Route::get('/', [AccountingController::class, 'index'])->name('index');
    Route::get('/export-journal', [AccountingController::class, 'exportJournal'])->name('export-journal');
});

// Réservations - Actions
Route::post('/bookings/{id}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
Route::post('/bookings/{id}/send-email', [BookingController::class, 'sendEmail'])->name('bookings.send-email');
Route::get('/bookings/{id}/print-receipt', [BookingController::class, 'printReceipt'])->name('bookings.print-receipt');
```

---

## 📄 ÉTAPE 5: Reçus PDF Professionnels (1h)

### 5.1 Améliorer DocumentGeneratorService

**app/Services/DocumentGeneratorService.php** - Ajouter:

```php
use Barryvdh\DomPDF\Facade\Pdf;

public function generateReceipt($booking)
{
    $data = [
        'booking' => $booking,
        'user' => $booking->user,
        'payment' => $booking->payment,
        'company' => [
            'name' => 'Carré Premium',
            'address' => 'Abidjan, Côte d\'Ivoire',
            'phone' => '+225 XX XX XX XX',
            'email' => 'contact@carrepremium.com',
            'website' => 'www.carrepremium.com',
        ],
        'date' => now()->format('d/m/Y'),
    ];
    
    return Pdf::loadView('pdf.receipt', $data)
              ->setPaper('a4')
              ->setOption('margin-top', 10)
              ->setOption('margin-bottom', 10);
}

public function generateInvoice($booking)
{
    $data = [
        'booking' => $booking,
        'user' => $booking->user,
        'items' => $this->getInvoiceItems($booking),
        'subtotal' => $booking->total_amount / 1.18, // HT
        'tva' => $booking->total_amount - ($booking->total_amount / 1.18),
        'total' => $booking->total_amount,
    ];
    
    return Pdf::loadView('pdf.invoice', $data)->setPaper('a4');
}

private function getInvoiceItems($booking)
{
    $items = [];
    
    if ($booking->booking_type == 'flight') {
        $flightData = json_decode($booking->flightBooking->flight_data, true);
        $items[] = [
            'description' => 'Vol ' . $flightData['itineraries'][0]['segments'][0]['departure']['iataCode'] . 
                           ' - ' . end($flightData['itineraries'][0]['segments'])['arrival']['iataCode'],
            'quantity' => count(json_decode($booking->flightBooking->passengers_data, true)),
            'unit_price' => $booking->total_amount / count(json_decode($booking->flightBooking->passengers_data, true)),
            'total' => $booking->total_amount,
        ];
    }
    
    return $items;
}
```

### 5.2 Template PDF Reçu

**resources/views/pdf/receipt.blade.php**
```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reçu #{{ $booking->booking_reference }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #667eea; padding-bottom: 20px; }
        .company-info { margin-bottom: 20px; }
        .receipt-details { background: #f9f9f9; padding: 15px; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .total { font-size: 16px; font-weight: bold; text-align: right; margin-top: 20px; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REÇU DE PAIEMENT</h1>
        <p>{{ $company['name'] }}</p>
        <p>{{ $company['address'] }} | {{ $company['phone'] }} | {{ $company['email'] }}</p>
    </div>

    <div class="receipt-details">
        <table>
            <tr>
                <td><strong>Numéro de Reçu:</strong></td>
                <td>{{ $booking->booking_reference }}</td>
                <td><strong>Date:</strong></td>
                <td>{{ $date }}</td>
            </tr>
            <tr>
                <td><strong>Client:</strong></td>
                <td colspan="3">{{ $user->first_name }} {{ $user->last_name }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td colspan="3">{{ $user->email }}</td>
            </tr>
        </table>
    </div>

    <h3>Détails du Paiement</h3>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ ucfirst($booking->booking_type) }} - Réf: {{ $booking->booking_reference }}</td>
                <td>{{ number_format($booking->total_amount, 0, ',', ' ') }} XOF</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <p>Montant Payé: <span style="color: #10b981;">{{ number_format($payment->amount, 0, ',', ' ') }} XOF</span></p>
        <p>Méthode: {{ strtoupper($payment->payment_method) }}</p>
        <p>Transaction ID: {{ $payment->transaction_id }}</p>
    </div>

    <div class="footer">
        <p>Merci pour votre confiance | Carré Premium © {{ date('Y') }}</p>
        <p>Ce document est un reçu officiel de paiement</p>
    </div>
</body>
</html>
```

---

## 🎯 ÉTAPE 6: Points de Fidélité Automatiques (30 min)

### 6.1 Améliorer LoyaltyService

**app/Services/LoyaltyService.php** - Ajouter:

```php
/**
 * Attribuer points automatiquement après paiement
 */
public function awardPointsForBooking($booking)
{
    $user = $booking->user;
    $amount = $booking->total_amount;
    
    // 1 point par 1000 XOF dépensés
    $points = floor($amount / 1000);
    
    // Bonus selon niveau VIP
    $level = $this->getUserLevel($user);
    $bonus = match($level) {
        'platinum' => 0.5, // +50%
        'gold' => 0.3,     // +30%
        'silver' => 0.15,  // +15%
        default => 0,
    };
    
    $totalPoints = $points * (1 + $bonus);
    
    // Ajouter points
    $user->increment('loyalty_points', $totalPoints);
    
    // Enregist
