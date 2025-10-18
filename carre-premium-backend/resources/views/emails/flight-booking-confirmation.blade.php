<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Réservation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background: #f4f4f4; }
        .email-container { max-width: 600px; margin: 20px auto; background: white; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 20px; text-align: center; }
        .header h1 { font-size: 28px; margin-bottom: 10px; }
        .header p { font-size: 16px; opacity: 0.9; }
        .content { padding: 30px 20px; }
        .greeting { font-size: 18px; margin-bottom: 20px; }
        .booking-card { background: #f8f9fa; border-left: 4px solid #667eea; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .booking-card h3 { color: #667eea; margin-bottom: 15px; font-size: 18px; }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e0e0e0; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: 600; color: #666; }
        .info-value { color: #333; font-weight: 500; }
        .flight-segment { background: white; border: 2px solid #e0e0e0; padding: 20px; margin: 15px 0; border-radius: 10px; }
        .flight-route { display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; }
        .airport { text-align: center; flex: 1; }
        .airport-code { font-size: 24px; font-weight: bold; color: #667eea; }
        .airport-name { font-size: 12px; color: #666; margin-top: 5px; }
        .flight-arrow { flex: 0 0 60px; text-align: center; color: #667eea; font-size: 20px; }
        .flight-details { background: #f8f9fa; padding: 15px; border-radius: 5px; }
        .flight-details p { margin: 5px 0; font-size: 14px; }
        .passengers-list { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .passenger-item { padding: 10px; background: white; margin: 8px 0; border-radius: 5px; border-left: 3px solid #10b981; }
        .cta-button { display: inline-block; background: #667eea; color: white; padding: 15px 40px; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: 600; text-align: center; }
        .cta-button:hover { background: #5568d3; }
        .important-info { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .important-info h4 { color: #856404; margin-bottom: 10px; }
        .important-info ul { margin-left: 20px; }
        .important-info li { margin: 8px 0; color: #856404; }
        .footer { background: #2d3748; color: white; padding: 30px 20px; text-align: center; }
        .footer p { margin: 8px 0; font-size: 14px; }
        .footer a { color: #667eea; text-decoration: none; }
        .social-links { margin: 15px 0; }
        .social-links a { display: inline-block; margin: 0 10px; color: white; font-size: 20px; }
        @media only screen and (max-width: 600px) {
            .email-container { margin: 0; }
            .header { padding: 30px 15px; }
            .content { padding: 20px 15px; }
            .flight-route { flex-direction: column; }
            .flight-arrow { transform: rotate(90deg); margin: 10px 0; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>✈️ Confirmation de Réservation</h1>
            <p>Votre vol est confirmé!</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">Bonjour <strong>{{ $user->first_name }}</strong>,</p>
            <p>Nous avons le plaisir de confirmer votre réservation de vol. Voici les détails de votre voyage:</p>

            <!-- Booking Details Card -->
            <div class="booking-card">
                <h3>📋 Informations de Réservation</h3>
                <div class="info-row">
                    <span class="info-label">Code PNR:</span>
                    <span class="info-value"><strong>{{ $booking->pnr }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Référence:</span>
                    <span class="info-value">{{ $booking->booking_reference }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Statut:</span>
                    <span class="info-value" style="color: #10b981;">✓ Confirmé</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Prix Total:</span>
                    <span class="info-value"><strong>{{ number_format($booking->total_price, 0, ',', ' ') }} XOF</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date de Réservation:</span>
                    <span class="info-value">{{ $booking->created_at->format('d/m/Y à H:i') }}</span>
                </div>
            </div>

            <!-- Flight Segments -->
            <h3 style="margin-top: 30px; color: #667eea;">🛫 Détails des Vols</h3>
            @foreach($segments as $index => $segment)
            <div class="flight-segment">
                <div class="flight-route">
                    <div class="airport">
                        <div class="airport-code">{{ $segment['departure']['iataCode'] }}</div>
                        <div class="airport-name">{{ $segment['departure']['at'] ?? 'N/A' }}</div>
                    </div>
                    <div class="flight-arrow">
                        ✈️ →
                    </div>
                    <div class="airport">
                        <div class="airport-code">{{ $segment['arrival']['iataCode'] }}</div>
                        <div class="airport-name">{{ $segment['arrival']['at'] ?? 'N/A' }}</div>
                    </div>
                </div>
                <div class="flight-details">
                    <p><strong>Vol:</strong> {{ $segment['carrierCode'] ?? 'N/A' }} {{ $segment['number'] ?? '' }}</p>
                    <p><strong>Durée:</strong> {{ $segment['duration'] ?? 'N/A' }}</p>
                    <p><strong>Classe:</strong> {{ $segment['cabin'] ?? 'Economy' }}</p>
                    @if(isset($segment['aircraft']))
                    <p><strong>Appareil:</strong> {{ $segment['aircraft']['code'] ?? 'N/A' }}</p>
                    @endif
                </div>
            </div>
            @endforeach

            <!-- Passengers -->
            <h3 style="margin-top: 30px; color: #667eea;">👥 Passagers</h3>
            <div class="passengers-list">
                @foreach($passengers as $passenger)
                <div class="passenger-item">
                    <strong>{{ $passenger['title'] ?? 'Mr' }}. {{ $passenger['firstName'] }} {{ $passenger['lastName'] }}</strong>
                    @if(isset($passenger['dateOfBirth']))
                    <br><small>Né(e) le: {{ $passenger['dateOfBirth'] }}</small>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Extras if any -->
            @if(!empty($extras))
            <h3 style="margin-top: 30px; color: #667eea;">🎒 Services Supplémentaires</h3>
            <div class="booking-card">
                @foreach($extras as $extra)
                <div class="info-row">
                    <span class="info-label">{{ $extra['type'] ?? 'Service' }}:</span>
                    <span class="info-value">{{ $extra['description'] ?? 'N/A' }}</span>
                </div>
                @endforeach
            </div>
            @endif

            <!-- CTA Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.frontend_url') }}/my-bookings/{{ $booking->id }}" class="cta-button">
                    Voir Ma Réservation Complète
                </a>
            </div>

            <!-- Important Information -->
            <div class="important-info">
                <h4>⚠️ Informations Importantes</h4>
                <ul>
                    <li>Présentez-vous à l'aéroport <strong>3 heures avant le départ</strong> pour les vols internationaux</li>
                    <li>Munissez-vous de votre <strong>passeport valide</strong> et de ce code PNR: <strong>{{ $booking->pnr }}</strong></li>
                    <li>Vérifiez les <strong>restrictions de bagages</strong> de votre compagnie aérienne</li>
                    <li>Conservez ce mail comme preuve de réservation</li>
                    <li>En cas de modification ou annulation, contactez-nous au moins 24h avant le départ</li>
                </ul>
            </div>

            <p style="margin-top: 20px;">Pour toute question, notre équipe est à votre disposition 24/7.</p>
            <p style="margin-top: 10px;"><strong>Bon voyage avec Carré Premium! ✈️</strong></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <h3 style="margin-bottom: 15px;">Carré Premium</h3>
            <p>Votre Conciergerie de Voyage Internationale</p>
            <p style="margin-top: 15px;">
                📞 +225 XX XX XX XX<br>
                ✉️ contact@carrepremium.com<br>
                🌐 www.carrepremium.com
            </p>
            <div class="social-links">
                <a href="#">📘</a>
                <a href="#">📷</a>
                <a href="#">🐦</a>
            </div>
            <p style="margin-top: 20px; font-size: 12px; opacity: 0.8;">
                © {{ date('Y') }} Carré Premium. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>
