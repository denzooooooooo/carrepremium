<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement confirmé</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f3f4f6;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header .icon {
            font-size: 60px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .header p {
            font-size: 16px;
            opacity: 0.95;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #1f2937;
        }
        .message {
            font-size: 15px;
            color: #4b5563;
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .payment-card {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
        }
        .payment-card .label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 10px;
        }
        .payment-card .amount {
            font-size: 42px;
            font-weight: 700;
            margin: 15px 0;
        }
        .payment-card .status {
            background: rgba(255, 255, 255, 0.2);
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 10px;
        }
        .details-grid {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #6b7280;
        }
        .detail-value {
            color: #1f2937;
            font-weight: 500;
        }
        .success-badge {
            background: #d1fae5;
            color: #065f46;
            padding: 12px 20px;
            border-radius: 8px;
            text-align: center;
            margin: 25px 0;
            font-weight: 600;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: #10b981;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            transition: background 0.3s;
        }
        .button:hover {
            background: #059669;
        }
        .button-container {
            text-align: center;
        }
        .info-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 16px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .info-box strong {
            color: #1e40af;
            display: block;
            margin-bottom: 8px;
        }
        .info-box p {
            color: #1e3a8a;
            margin: 0;
            font-size: 14px;
        }
        .footer {
            background: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer-logo {
            font-size: 22px;
            font-weight: 700;
            color: #9333EA;
            margin-bottom: 15px;
        }
        .footer-contact {
            color: #6b7280;
            font-size: 14px;
            margin: 8px 0;
        }
        .footer-disclaimer {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 20px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="icon">✅</div>
            <h1>Paiement Confirmé !</h1>
            <p>Votre transaction a été effectuée avec succès</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <p class="greeting">Bonjour {{ $customerName }},</p>
            
            <p class="message">
                Nous vous confirmons que votre paiement a été traité avec succès. 
                Votre réservation est maintenant confirmée et vous pouvez voyager en toute sérénité.
            </p>
            
            <!-- Payment Card -->
            <div class="payment-card">
                <div class="label">Montant payé</div>
                <div class="amount">{{ number_format($amount, 0, ',', ' ') }} {{ $currency }}</div>
                <div class="status">✓ PAIEMENT RÉUSSI</div>
            </div>
            
            <!-- Payment Details -->
            <div class="details-grid">
                <div class="detail-row">
                    <span class="detail-label">Numéro de réservation</span>
                    <span class="detail-value"><strong>{{ $bookingNumber }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Date du paiement</span>
                    <span class="detail-value">{{ $paymentDate }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Méthode de paiement</span>
                    <span class="detail-value">
                        @if($paymentMethod === 'stripe')
                            💳 Carte bancaire
                        @elseif($paymentMethod === 'orange_money')
                            📱 Orange Money
                        @elseif($paymentMethod === 'mtn_momo')
                            📱 MTN Mobile Money
                        @else
                            {{ ucfirst($paymentMethod) }}
                        @endif
                    </span>
                </div>
            </div>
            
            <!-- Success Badge -->
            <div class="success-badge">
                🎉 Votre réservation est confirmée et prête pour votre voyage !
            </div>
            
            <p class="message">
                Un reçu détaillé ainsi que votre e-ticket sont disponibles dans votre espace client. 
                Vous pouvez les télécharger et les imprimer à tout moment.
            </p>
            
            <!-- CTA Button -->
            <div class="button-container">
                <a href="{{ config('app.frontend_url', 'http://localhost:3000') }}/my-bookings/{{ $bookingNumber }}" class="button">
                    📄 Télécharger mon reçu
                </a>
            </div>
            
            <!-- Info Box -->
            <div class="info-box">
                <strong>💡 Bon à savoir</strong>
                <p>
                    Vous recevrez un email de rappel 24h avant votre date de voyage. 
                    N'oubliez pas d'apporter une pièce d'identité valide.
                </p>
            </div>
            
            <p class="message" style="margin-top: 30px;">
                Merci d'avoir choisi <strong>Carré Premium</strong> pour votre voyage. 
                Nous vous souhaitons un excellent séjour !
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">✨ Carré Premium</div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 15px;">
                Votre Conciergerie de Voyage Premium Internationale
            </p>
            <p class="footer-contact">📧 contact@carrepremium.com</p>
            <p class="footer-contact">📞 +225 XX XX XX XX XX</p>
            <p class="footer-contact">🌐 www.carrepremium.com</p>
            
            <p class="footer-disclaimer">
                Cet email a été envoyé automatiquement. Merci de ne pas y répondre directement.<br>
                Pour toute question, veuillez utiliser nos canaux de contact officiels.
            </p>
        </div>
    </div>
</body>
</html>
