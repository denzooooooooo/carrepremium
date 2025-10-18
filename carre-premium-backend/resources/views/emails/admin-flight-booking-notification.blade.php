<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Demande de Réservation Vol</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .info-label {
            font-weight: 600;
            color: #6b7280;
        }
        .info-value {
            color: #111827;
        }
        .passenger-card {
            background: #f9fafb;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        .alert {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 24px;">🔔 Nouvelle Demande de Réservation</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Référence: {{ $booking->booking_reference }}</p>
    </div>

    <div class="content">
        <div class="alert">
            <strong>⚡ ACTION REQUISE</strong><br>
            Une nouvelle demande de réservation de vol nécessite votre traitement manuel via Amadeus Production.
        </div>

        <!-- Informations de la Réservation -->
        <div class="section">
            <div class="section-title">📋 Détails de la Réservation</div>
            <div class="info-row">
                <span class="info-label">Référence:</span>
                <span class="info-value"><strong>{{ $booking->booking_reference }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Date de demande:</span>
                <span class="info-value">{{ $booking->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Montant total:</span>
                <span class="info-value"><strong>{{ number_format($booking->total_amount, 0, ',', ' ') }} {{ $booking->currency }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Statut paiement:</span>
                <span class="info-value">{{ $booking->payment_status }}</span>
            </div>
        </div>

        <!-- Informations du Vol -->
        <div class="section">
            <div class="section-title">✈️ Détails du Vol</div>
            <div class="info-row">
                <span class="info-label">Trajet:</span>
                <span class="info-value"><strong>{{ $flightData['departure'] ?? 'N/A' }} → {{ $flightData['arrival'] ?? 'N/A' }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Date de départ:</span>
                <span class="info-value">{{ $flightData['date'] ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Compagnie:</span>
                <span class="info-value">{{ $flightData['airline'] ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Classe:</span>
                <span class="info-value">{{ $flightData['class'] ?? 'economy' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Passagers:</span>
                <span class="info-value">{{ count($passengersData) }}</span>
            </div>
        </div>

        <!-- Passagers -->
        <div class="section">
            <div class="section-title">👥 Informations Passagers</div>
            @foreach($passengersData as $index => $passenger)
            <div class="passenger-card">
                <strong>Passager {{ $index + 1 }}</strong><br>
                Nom: {{ $passenger['title'] ?? '' }} {{ $passenger['firstName'] ?? '' }} {{ $passenger['lastName'] ?? '' }}<br>
                Date de naissance: {{ $passenger['dateOfBirth'] ?? 'N/A' }}<br>
                Nationalité: {{ $passenger['nationality'] ?? 'N/A' }}<br>
                Passeport: {{ $passenger['passportNumber'] ?? 'N/A' }}<br>
                @if($index === 0 && isset($passenger['email']))
                Email: {{ $passenger['email'] }}<br>
                Téléphone: {{ $passenger['phone'] ?? 'N/A' }}
                @endif
            </div>
            @endforeach
        </div>

        <!-- Options Supplémentaires -->
        @if(!empty($servicesData))
        <div class="section">
            <div class="section-title">🎯 Options Sélectionnées</div>
            @if(isset($servicesData['baggage']) && $servicesData['baggage'])
            <div class="info-row">
                <span class="info-label">🧳 Bagage supplémentaire:</span>
                <span class="info-value">✓ Oui</span>
            </div>
            @endif
            @if(isset($servicesData['meal']) && $servicesData['meal'])
            <div class="info-row">
                <span class="info-label">🍽️ Repas spécial:</span>
                <span class="info-value">✓ Oui</span>
            </div>
            @endif
            @if(isset($servicesData['insurance']) && $servicesData['insurance'])
            <div class="info-row">
                <span class="info-label">🛡️ Assurance voyage:</span>
                <span class="info-value">✓ Oui</span>
            </div>
            @endif
            @if(isset($servicesData['seat_selection']) && $servicesData['seat_selection'] && !empty($servicesData['seats']))
            <div class="info-row">
                <span class="info-label">💺 Sièges sélectionnés:</span>
                <span class="info-value">{{ implode(', ', $servicesData['seats'] ?? []) }}</span>
            </div>
            @endif
        </div>
        @endif

        <!-- Actions à Effectuer -->
        <div class="section" style="border-left-color: #f59e0b;">
            <div class="section-title" style="color: #f59e0b;">⚡ Actions à Effectuer</div>
            <ol style="margin: 0; padding-left: 20px;">
                <li>Vérifier le paiement dans le système</li>
                <li>Se connecter à Amadeus Production</li>
                <li>Créer la réservation avec les informations ci-dessus</li>
                <li>Ajouter les services auxiliaires demandés</li>
                <li>Obtenir le PNR et les e-tickets</li>
                <li>Mettre à jour la réservation dans l'admin</li>
                <li>Envoyer la confirmation au client avec PNR et billets</li>
            </ol>
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/admin/bookings/' . $booking->id) }}" class="button">
                Voir la Réservation dans l'Admin
            </a>
        </div>
    </div>

    <div class="footer">
        <p>Carré Premium - Conciergerie de Voyage Premium</p>
        <p>Cet email a été envoyé automatiquement. Ne pas répondre.</p>
    </div>
</body>
</html>
