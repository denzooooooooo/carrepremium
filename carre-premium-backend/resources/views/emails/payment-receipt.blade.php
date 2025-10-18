<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background: #f4f4f4; }
        .email-container { max-width: 600px; margin: 20px auto; background: white; }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 40px 20px; text-align: center; }
        .header h1 { font-size: 28px; margin-bottom: 10px; }
        .content { padding: 30px 20px; }
        .receipt-card { background: #f8f9fa; border: 2px solid #10b981; padding: 25px; margin: 20px 0; border-radius: 10px; }
        .receipt-header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px dashed #10b981; }
        .receipt-number { font-size: 24px; font-weight: bold; color: #10b981; }
        .info-table { width: 100%; margin: 15px 0; }
        .info-table td { padding: 12px 0; border-bottom: 1px solid #e0e0e0; }
        .info-table td:first-child { font-weight: 600; color: #666; width: 40%; }
        .info-table td:last-child { color: #333; text-align: right; }
        .amount-section { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; text-align: center; }
        .amount-label { font-size: 14px; color: #666; margin-bottom: 10px; }
        .amount-value { font-size: 36px; font-weight: bold; color: #10b981; }
        .payment-method { background: #e8f5e9; padding: 15px; border-radius: 5px; margin: 15px 0; text-align: center; }
        .success-badge { display: inline-block; background: #10b981; color: white; padding: 8px 20px; border-radius: 20px; font-weight: 600; margin: 10px 0; }
        .footer { background: #2d3748; color: white; padding: 30px 20px; text-align: center; }
        .footer p { margin: 8px 0; font-size: 14px; }
        @media only screen and (max-width: 600px) {
            .email-container { margin: 0; }
            .header, .content { padding: 20px 15px; }
            .amount-value { font-size: 28px; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>💳 Reçu de Paiement</h1>
            <p>Paiement confirmé avec succès</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p style="font-size: 18px; margin-bottom: 20px;">Bonjour <strong>{{ $user->first_name }}</strong>,</p>
            <p>Nous vous confirmons la réception de votre paiement. Voici votre reçu officiel:</p>

            <!-- Receipt Card -->
            <div class="receipt-card">
                <div class="receipt-header">
                    <div style="font-size: 14px; color: #666; margin-bottom: 5px;">REÇU N°</div>
                    <div class="receipt-number">{{ $payment->transaction_id }}</div>
                    <div style="font-size: 12px; color: #666; margin-top: 10px;">
                        {{ $payment->created_at->format('d/m/Y à H:i') }}
                    </div>
                </div>

                <!-- Payment Details -->
                <table class="info-table">
                    <tr>
                        <td>Client:</td>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td>Réservation:</td>
                        <td>{{ $booking->booking_reference }}</td>
                    </tr>
                    <tr>
                        <td>Type:</td>
                        <td>{{ ucfirst($booking->booking_type) }}</td>
                    </tr>
                    <tr>
                        <td>Statut:</td>
                        <td><span class="success-badge">✓ Payé</span></td>
                    </tr>
                </table>
            </div>

            <!-- Amount Section -->
            <div class="amount-section">
                <div class="amount-label">MONTANT PAYÉ</div>
                <div class="amount-value">{{ number_format($payment->amount, 0, ',', ' ') }} XOF</div>
            </div>

            <!-- Payment Method -->
            <div class="payment-method">
                <strong>Méthode de Paiement:</strong> {{ strtoupper($payment->payment_method) }}
                @if($payment->payment_method == 'stripe')
                <br><small>Carte bancaire</small>
                @elseif($payment->payment_method == 'mobile_money')
                <br><small>Mobile Money</small>
                @endif
            </div>

            <!-- Transaction Details -->
            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <h4 style="color: #667eea; margin-bottom: 10px;">Détails de la Transaction</h4>
                <table class="info-table">
                    <tr>
                        <td>ID Transaction:</td>
                        <td><code style="background: #e0e0e0; padding: 2px 8px; border-radius: 3px;">{{ $payment->transaction_id }}</code></td>
                    </tr>
                    <tr>
                        <td>Date:</td>
                        <td>{{ $payment->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <td>Devise:</td>
                        <td>{{ $payment->currency ?? 'XOF' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Important Note -->
            <div style="background: #e3f2fd; border-left: 4px solid #2196f3; padding: 15px; margin: 20px 0; border-radius: 5px;">
                <p style="color: #1565c0; font-weight: 600; margin-bottom: 8px;">📌 Note Importante</p>
                <p style="color: #1976d2; font-size: 14px;">
                    Ce reçu constitue une preuve officielle de paiement. Conservez-le précieusement pour vos dossiers.
                    Vous pouvez le télécharger en PDF depuis votre espace client.
                </p>
            </div>

            <!-- CTA -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.frontend_url') }}/my-bookings/{{ $booking->id }}" 
                   style="display: inline-block; background: #10b981; color: white; padding: 15px 40px; text-decoration: none; border-radius: 8px; font-weight: 600;">
                    Voir Ma Réservation
                </a>
            </div>

            <p style="margin-top: 25px; text-align: center; color: #666;">
                Merci pour votre confiance!<br>
                <strong style="color: #10b981;">L'équipe Carré Premium</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <h3 style="margin-bottom: 15px;">Carré Premium</h3>
            <p>Votre Conciergerie de Voyage Internationale</p>
            <p style="margin-top: 15px;">
                📞 +225 XX XX XX XX | ✉️ contact@carrepremium.com<br>
                🌐 www.carrepremium.com
            </p>
            <p style="margin-top: 20px; font-size: 12px; opacity: 0.8;">
                © {{ date('Y') }} Carré Premium. Tous droits réservés.<br>
                Ce reçu a été généré automatiquement.
            </p>
        </div>
    </div>
</body>
</html>
