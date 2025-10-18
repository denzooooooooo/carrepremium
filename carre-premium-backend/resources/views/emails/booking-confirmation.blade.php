<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de réservation</title>
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
            background: linear-gradient(135deg, #9333EA 0%, #7C3AED 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
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
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .booking-card {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
        }
        .booking-card h2 {
            color: #9333EA;
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 600;
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
        .total-amount {
            background: linear-gradient(135deg, #9333EA 0%, #7C3AED 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 25px 0;
        }
        .total-amount .label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        .total-amount .amount {
            font-size: 32px;
            font-weight: 700;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: #9333EA;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            transition: background 0.3s;
        }
        .button:hover {
            background: #7C3AED;
        }
        .button-container {
            text-align: center;
        }
        .important-note {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 16px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .important-note strong {
            color: #92400e;
            display: block;
            margin-bottom: 8px;
        }
        .important-note p {
            color: #78350f;
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
        .icon {
            display: inline-block;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🎉 Réservation Confirmée !</h1>
            <p>Merci pour votre confiance</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <p class="greeting">Bonjour {{ $customerName }},</p>
            
            <p class="message">
                Nous avons le plaisir de confirmer votre réservation chez <strong>Carré Premium</strong>, 
                votre conciergerie de voyage premium internationale.
            </p>
            
            <!-- Booking Details Card -->
            <div class="booking-card">
                <h2>📋 Détails de la réservation</h2>
                
                <div class="detail-row">
                    <span class="detail-label">Numéro de réservation</span>
                    <span class="detail-value"><strong>{{ $bookingNumber }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Date de réservation</span>
                    <span class="detail-value">{{ $bookingDate }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Date de voyage</span>
                    <span class="detail-value">{{ $travelDate }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Nombre de passagers</span>
                    <span class="detail-value">{{ $passengerCount }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Type de réservation</span>
                    <span class="detail-value">
                        @if($bookingType === 'flight')
                            ✈️ Vol
                        @elseif($bookingType === 'event')
                            🎭 Événement
                        @else
                            🎒 Package Touristique
                        @endif
                    </span>
                </div>
            </div>
            
            <!-- Total Amount -->
            <div class="total-amount">
                <div class="label">Montant total</div>
                <div class="amount">{{ number_format($totalAmount, 0, ',', ' ') }} {{ $currency }}</div>
            </div>
            
            <p class="message">
                Votre e-ticket a été généré et est disponible dans votre espace client. 
                Vous pouvez le télécharger et l'imprimer à tout moment.
            </p>
            
            <!-- CTA Button -->
            <div class="button-container">
                <a href="{{ config('app.frontend_url', 'http://localhost:3000') }}/my-bookings/{{ $bookingNumber }}" class="button">
                    📄 Voir ma réservation
                </a>
            </div>
            
            <!-- Important Note -->
            <div class="important-note">
                <strong>📌 Important</strong>
                <p>
                    Veuillez présenter ce numéro de réservation (<strong>{{ $bookingNumber }}</strong>) 
                    ainsi qu'une pièce d'identité valide lors de votre voyage.
                </p>
            </div>
            
            <p class="message" style="margin-top: 30px;">
                Si vous avez des questions, notre équipe est à votre disposition 24/7.
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
