<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Facture - {{ $invoiceNumber }}</title>
    <style>
        @page { margin: 20px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1f2937; }
        .invoice-header { display: table; width: 100%; margin-bottom: 30px; }
        .company-info { display: table-cell; width: 50%; vertical-align: top; }
        .invoice-info { display: table-cell; width: 50%; text-align: right; vertical-align: top; }
        .company-logo { font-size: 24px; font-weight: bold; color: #667eea; margin-bottom: 10px; }
        .invoice-title { font-size: 32px; font-weight: bold; color: #667eea; margin-bottom: 10px; }
        .invoice-number { font-size: 18px; color: #6b7280; }
        .client-box { background: #f9fafb; border-left: 4px solid #667eea; padding: 20px; margin: 20px 0; }
        .client-box h3 { color: #667eea; margin-bottom: 10px; font-size: 14px; }
        .items-table { width: 100%; border-collapse: collapse; margin: 30px 0; }
        .items-table thead { background: #667eea; color: white; }
        .items-table th { padding: 12px; text-align: left; font-size: 11px; }
        .items-table td { padding: 12px; border-bottom: 1px solid #e5e7eb; }
        .items-table tbody tr:hover { background: #f9fafb; }
        .totals-table { width: 40%; margin-left: auto; margin-top: 20px; }
        .totals-table td { padding: 10px; }
        .totals-table .total-row { font-size: 16px; font-weight: bold; background: #eff6ff; border-top: 2px solid #667eea; }
        .payment-info { background: #d1fae5; border-left: 4px solid #10b981; padding: 15px; margin: 20px 0; }
        .payment-info h4 { color: #065f46; margin-bottom: 8px; }
        .footer { margin-top: 50px; padding-top: 20px; border-top: 2px solid #e5e7eb; text-align: center; font-size: 9px; color: #6b7280; }
        .qr-code { text-align: right; margin-top: 20px; }
        .qr-code img { width: 100px; height: 100px; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="invoice-header">
        <div class="company-info">
            <div class="company-logo">{{ $company['name'] }}</div>
            <p>{{ $company['address'] }}</p>
            <p>Tél: {{ $company['phone'] }}</p>
            <p>Email: {{ $company['email'] }}</p>
            <p>{{ $company['website'] }}</p>
            <p style="margin-top: 10px; font-size: 9px;">
                SIRET: {{ $company['siret'] }}<br>
                N° TVA: {{ $company['tva'] }}
            </p>
        </div>
        <div class="invoice-info">
            <div class="invoice-title">FACTURE</div>
            <div class="invoice-number">{{ $invoiceNumber }}</div>
            <p style="margin-top: 15px; font-size: 10px;">
                <strong>Date:</strong> {{ now()->format('d/m/Y') }}<br>
                <strong>Échéance:</strong> {{ now()->addDays(30)->format('d/m/Y') }}
            </p>
        </div>
    </div>

    <!-- Client Information -->
    <div class="client-box">
        <h3>FACTURÉ À:</h3>
        <p style="font-size: 14px; font-weight: bold; margin-bottom: 5px;">
            {{ $user->first_name }} {{ $user->last_name }}
        </p>
        <p>{{ $user->email }}</p>
        @if($user->phone)
        <p>Tél: {{ $user->phone }}</p>
        @endif
        @if($user->country)
        <p>{{ $user->country }}</p>
        @endif
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">DESCRIPTION</th>
                <th style="width: 15%; text-align: center;">QTÉ</th>
                <th style="width: 20%; text-align: right;">PRIX UNIT.</th>
                <th style="width: 15%; text-align: right;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ ucfirst($booking->booking_type) }}</strong><br>
                    <small style="color: #6b7280;">
                        Réf: {{ $booking->booking_reference }}
                        @if($booking->booking_type == 'flight' && $booking->flightBooking)
                        <br>PNR: {{ $booking->flightBooking->pnr }}
                        @endif
                    </small>
                </td>
                <td style="text-align: center;">1</td>
                <td style="text-align: right;">{{ number_format($subtotal, 0, ',', ' ') }} XOF</td>
                <td style="text-align: right;">{{ number_format($subtotal, 0, ',', ' ') }} XOF</td>
            </tr>
        </tbody>
    </table>

    <!-- Totals -->
    <table class="totals-table">
        <tr>
            <td>Sous-total HT:</td>
            <td style="text-align: right;">{{ number_format($subtotal, 0, ',', ' ') }} XOF</td>
        </tr>
        <tr>
            <td>TVA (18%):</td>
            <td style="text-align: right;">{{ number_format($tva, 0, ',', ' ') }} XOF</td>
        </tr>
        <tr class="total-row">
            <td>TOTAL TTC:</td>
            <td style="text-align: right;">{{ number_format($total, 0, ',', ' ') }} XOF</td>
        </tr>
    </table>

    <!-- Payment Information -->
    @if($payment && $payment->status == 'completed')
    <div class="payment-info">
        <h4>✓ PAIEMENT REÇU</h4>
        <p style="font-size: 10px;">
            <strong>Méthode:</strong> {{ strtoupper($payment->payment_method) }}<br>
            <strong>Transaction:</strong> {{ $payment->transaction_id }}<br>
            <strong>Date:</strong> {{ $payment->created_at->format('d/m/Y à H:i') }}
        </p>
    </div>
    @else
    <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0;">
        <h4 style="color: #92400e; margin-bottom: 8px;">⏳ EN ATTENTE DE PAIEMENT</h4>
        <p style="font-size: 10px; color: #78350f;">
            Montant dû: <strong>{{ number_format($total, 0, ',', ' ') }} XOF</strong><br>
            Échéance: {{ now()->addDays(7)->format('d/m/Y') }}
        </p>
    </div>
    @endif

    <!-- QR Code -->
    <div class="qr-code">
        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
        <p style="font-size: 8px; color: #6b7280; margin-top: 5px;">Code de vérification</p>
    </div>

    <!-- Terms -->
    <div style="background: #f9fafb; padding: 15px; margin-top: 30px; font-size: 9px; color: #6b7280;">
        <p style="font-weight: bold; margin-bottom: 8px;">CONDITIONS DE PAIEMENT:</p>
        <ul style="margin-left: 20px;">
            <li>Paiement à réception de facture</li>
            <li>Pénalités de retard: 3% par mois</li>
            <li>Escompte pour paiement anticipé: 2%</li>
            <li>En cas de litige, seul le tribunal de commerce d'Abidjan est compétent</li>
        </ul>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>{{ $company['name'] }}</strong> - {{ $company['siret'] }}</p>
        <p>{{ $company['address'] }} | Tél: {{ $company['phone'] }} | Email: {{ $company['email'] }}</p>
        <p style="margin-top: 8px;">© {{ date('Y') }} {{ $company['name'] }}. Tous droits réservés.</p>
        <p style="margin-top: 5px;">Facture générée le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
