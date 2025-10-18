<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation #{{ $booking->booking_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #9333EA;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #9333EA;
            font-size: 32px;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .booking-number {
            background: #9333EA;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            background: #f3f4f6;
            padding: 10px 15px;
            font-weight: bold;
            color: #9333EA;
            margin-bottom: 15px;
            border-left: 4px solid #9333EA;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .info-item {
            padding: 10px;
            background: #f9fafb;
            border-radius: 4px;
        }
        .info-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        .passenger-list {
            background: #f9fafb;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .passenger-item {
            padding: 10px;
            background: white;
            margin-bottom: 10px;
            border-left: 3px solid #D4AF37;
        }
        .amount-section {
            background: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
        }
        .amount-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .amount-row.total {
            border-top: 2px solid #9333EA;
            border-bottom: none;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #9333EA;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-confirmed { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .status-completed { background: #e5e7eb; color: #374151; }
        @media print {
            body { padding: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CARRÉ PREMIUM</h1>
        <p>Votre partenaire voyage de confiance</p>
    </div>

    <div class="booking-number">
        Réservation N° {{ $booking->booking_number }}
    </div>

    <!-- Informations Client -->
    <div class="section">
        <div class="section-title">INFORMATIONS CLIENT</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nom complet</div>
                <div class="info-value">{{ $booking->user->first_name }} {{ $booking->user->last_name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $booking->user->email }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Téléphone</div>
                <div class="info-value">{{ $booking->user->phone ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Pays</div>
                <div class="info-value">{{ $booking->user->country }}</div>
            </div>
        </div>
    </div>

    <!-- Détails de la Réservation -->
    <div class="section">
        <div class="section-title">DÉTAILS DE LA RÉSERVATION</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Type</div>
                <div class="info-value">
                    @if($booking->booking_type === 'flight')
                        ✈️ Vol
                    @elseif($booking->booking_type === 'event')
                        🎫 Événement
                    @else
                        🧳 Package Touristique
                    @endif
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Date de réservation</div>
                <div class="info-value">{{ $booking->created_at->format('d/m/Y à H:i') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Date de voyage</div>
                <div class="info-value">{{ $booking->travel_date ? $booking->travel_date->format('d/m/Y') : 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Nombre de passagers</div>
                <div class="info-value">{{ $booking->number_of_passengers }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Statut</div>
                <div class="info-value">
                    <span class="status-badge status-{{ $booking->status }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Paiement</div>
                <div class="info-value">
                    <span class="status-badge status-{{ $booking->payment_status }}">
                        {{ ucfirst($booking->payment_status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations des Passagers -->
    @if($booking->passenger_details)
    <div class="section">
        <div class="section-title">PASSAGERS</div>
        <div class="passenger-list">
            @php
                $passengers = is_string($booking->passenger_details) ? json_decode($booking->passenger_details, true) : $booking->passenger_details;
            @endphp
            @if(is_array($passengers))
                @foreach($passengers as $index => $passenger)
                <div class="passenger-item">
                    <strong>Passager {{ $index + 1 }}:</strong>
                    {{ $passenger['first_name'] ?? '' }} {{ $passenger['last_name'] ?? '' }}
                    @if(isset($passenger['passport']))
                        - Passeport: {{ $passenger['passport'] }}
                    @endif
                </div>
                @endforeach
            @endif
        </div>
    </div>
    @endif

    <!-- Montants -->
    <div class="section">
        <div class="section-title">DÉTAIL DES MONTANTS</div>
        <div class="amount-section">
            <div class="amount-row">
                <span>Montant total</span>
                <span>{{ number_format($booking->total_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
            </div>
            @if($booking->discount_amount > 0)
            <div class="amount-row" style="color: #059669;">
                <span>Réduction</span>
                <span>-{{ number_format($booking->discount_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
            </div>
            @endif
            @if($booking->tax_amount > 0)
            <div class="amount-row">
                <span>Taxes</span>
                <span>{{ number_format($booking->tax_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
            </div>
            @endif
            <div class="amount-row total">
                <span>MONTANT FINAL</span>
                <span>{{ number_format($booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
            </div>
        </div>
    </div>

    <div class="footer">
        <p><strong>Carré Premium</strong> - Votre partenaire voyage de confiance</p>
        <p>Email: contact@carrepremium.com | Tél: +225 01 01 04 02 06</p>
        <p>Document imprimé le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
