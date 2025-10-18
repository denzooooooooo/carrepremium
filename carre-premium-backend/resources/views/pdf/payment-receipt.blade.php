<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Reçu de Paiement - {{ $payment->transaction_id }}</title>
    <style>
        @page { margin: 20px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        .header { background: #10b981; color: white; padding: 30px 20px; text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 24px; margin-bottom: 5px; }
        .header p { font-size: 14px; opacity: 0.9; }
        .company-info { text-align: center; margin-bottom: 30px; }
        .company-info h2 { font-size: 18px; color: #10b981; margin-bottom: 10px; }
        .company-info p { font-size: 10px; color: #666; }
        .receipt-box { border: 3px solid #10b981; padding: 20px; margin: 20px 0; background: #f9fafb; }
        .receipt-number { text-align: center; font-size: 20px; font-weight: bold; color: #10b981; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 2px dashed #10b981; }
        .info-table { width: 100%; margin: 15px 0; border-collapse: collapse; }
        .info-table td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        .info-table td:first-child { font-weight: bold; width: 40%; color: #666; }
        .info-table td:last-child { text-align: right; }
        .amount-box { background: white; border: 2px solid #10b981; padding: 20px; text-align: center; margin: 20px 0; }
        .amount-label { font-size: 12px; color: #666; margin-bottom: 10px; }
        .amount-value { font-size: 32px; font-weight: bold; color: #10b981; }
        .qr-section { text-align: center; margin: 30px 0; padding: 20px; background: #f9fafb; }
        .qr-section img { width: 150px; height: 150px; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 2px solid #e5e7eb; text-align: center; font-size: 9px; color: #666; }
        .watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 80px; color: rgba(16, 185, 129, 0.05); font-weight: bold; z-index: -1; }
        .status-badge { display: inline-block; background: #10b981; color: white; padding: 5px 15px; border-radius: 15px; font-size: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="watermark">PAYÉ</div>

    <!-- Company Info -->
    <div class="company-info">
        <h2>{{ $company['name'] }}</h2>
        <p>{{ $company['address'] }}</p>
        <p>Tél: {{ $company['phone'] }} | Email: {{ $company['email'] }}</p>
        <p>{{ $company['website'] }}</p>
    </div>

    <!-- Header -->
    <div class="header">
        <h1>💳 REÇU DE PAIEMENT</h1>
        <p>Document Officiel</p>
    </div>

    <!-- Receipt Box -->
    <div class="receipt-box">
        <div class="receipt-number">
            REÇU N° {{ $payment->transaction_id }}
        </div>

        <table class="info-table">
            <tr>
                <td>Date d'émission:</td>
                <td>{{ $payment->created_at->format('d/m/Y à H:i') }}</td>
            </tr>
            <tr>
                <td>Client:</td>
                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td>Téléphone:</td>
                <td>{{ $user->phone ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Référence Réservation:</td>
                <td><strong>{{ $booking->booking_reference }}</strong></td>
            </tr>
            <tr>
                <td>Type de Service:</td>
                <td>{{ ucfirst($booking->booking_type) }}</td>
            </tr>
            <tr>
                <td>Statut:</td>
                <td><span class="status-badge">✓ PAYÉ</span></td>
            </tr>
        </table>
    </div>

    <!-- Amount Section -->
    <div class="amount-box">
        <div class="amount-label">MONTANT TOTAL PAYÉ</div>
        <div class="amount-value">{{ number_format($payment->amount, 0, ',', ' ') }} XOF</div>
    </div>

    <!-- Payment Details -->
    <table class="info-table" style="background: white; padding: 15px;">
        <tr>
            <td colspan="2" style="font-weight: bold; color: #10b981; border-bottom: 2px solid #10b981; padding-bottom: 10px;">DÉTAILS DU PAIEMENT</td>
        </tr>
        <tr>
            <td>ID Transaction:</td>
            <td><code style="background: #f3f4f6; padding: 3px 8px; border-radius: 3px;">{{ $payment->transaction_id }}</code></td>
        </tr>
        <tr>
            <td>Méthode de Paiement:</td>
            <td>{{ strtoupper($payment->payment_method) }}</td>
        </tr>
        <tr>
            <td>Date de Transaction:</td>
            <td>{{ $payment->created_at->format('d/m/Y H:i:s') }}</td>
        </tr>
        <tr>
            <td>Devise:</td>
            <td>{{ $payment->currency ?? 'XOF' }}</td>
        </tr>
        <tr>
            <td>Statut:</td>
            <td style="color: #10b981; font-weight: bold;">{{ ucfirst($payment->status) }}</td>
        </tr>
    </table>

    <!-- QR Code -->
    <div class="qr-section">
        <p style="margin-bottom: 10px; font-weight: bold;">Code de Vérification</p>
        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
        <p style="margin-top: 10px; font-size: 9px; color: #666;">
            Scannez ce code pour vérifier l'authenticité de ce reçu
        </p>
    </div>

    <!-- Important Note -->
    <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0;">
        <p style="font-weight: bold; color: #92400e; margin-bottom: 8px;">📌 Note Importante</p>
        <p style="font-size: 10px; color: #78350f;">
            Ce reçu constitue une preuve officielle de paiement. Conservez-le précieusement pour vos dossiers comptables.
            En cas de litige, présentez ce document avec le code de transaction.
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>{{ $company['name'] }}</strong></p>
        <p>{{ $company['address'] }}</p>
        <p>Tél: {{ $company['phone'] }} | Email: {{ $company['email'] }}</p>
        <p style="margin-top: 10px;">© {{ date('Y') }} {{ $company['name'] }}. Tous droits réservés.</p>
        <p style="margin-top: 5px;">Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
