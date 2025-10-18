<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Billet Électronique - {{ $pnr }}</title>
    <style>
        @page { margin: 15px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; color: #1f2937; }
        .ticket-container { border: 3px solid #667eea; padding: 0; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px 20px; text-align: center; }
        .header h1 { font-size: 22px; margin-bottom: 5px; }
        .header .pnr { font-size: 28px; font-weight: bold; letter-spacing: 3px; margin: 10px 0; }
        .section { padding: 20px; border-bottom: 2px dashed #e5e7eb; }
        .section:last-child { border-bottom: none; }
        .section-title { font-size: 14px; font-weight: bold; color: #667eea; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid #667eea; }
        .passenger-box { background: #f9fafb; padding: 15px; margin: 10px 0; border-left: 4px solid #10b981; }
        .passenger-name { font-size: 16px; font-weight: bold; color: #1f2937; margin-bottom: 5px; }
        .flight-route { display: table; width: 100%; margin: 15px 0; }
        .airport { display: table-cell; width: 35%; text-align: center; vertical-align: middle; }
        .airport-code { font-size: 32px; font-weight: bold; color: #667eea; }
        .airport-name { font-size: 9px; color: #6b7280; margin-top: 5px; }
        .flight-arrow { display: table-cell; width: 30%; text-align: center; vertical-align: middle; color: #667eea; font-size: 24px; }
        .flight-details { background: #eff6ff; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .detail-row { margin: 8px 0; }
        .detail-label { display: inline-block; width: 45%; font-weight: bold; color: #4b5563; }
        .detail-value { display: inline-block; width: 50%; color: #1f2937; }
        .barcode-section { text-align: center; padding: 20px; background: #f9fafb; }
        .barcode-section img { max-width: 100%; height: auto; }
        .qr-section { text-align: center; padding: 15px; }
        .qr-section img { width: 120px; height: 120px; }
        .important-info { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 15px 0; }
        .important-info h4 { color: #92400e; font-size: 12px; margin-bottom: 8px; }
        .important-info ul { margin-left: 20px; }
        .important-info li { margin: 5px 0; color: #78350f; font-size: 9px; }
        .footer { background: #1f2937; color: white; padding: 15px; text-align: center; font-size: 8px; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 8px; border-bottom: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Header -->
        <div class="header">
            <h1>✈️ BILLET ÉLECTRONIQUE</h1>
            <div class="pnr">{{ $pnr }}</div>
            <p style="font-size: 11px;">Code de Réservation (PNR)</p>
        </div>

        <!-- Passenger Information -->
        <div class="section">
            <div class="section-title">👤 INFORMATIONS PASSAGER(S)</div>
            @foreach($passengers as $index => $passenger)
            <div class="passenger-box">
                <div class="passenger-name">
                    {{ $passenger['title'] ?? 'Mr' }}. {{ $passenger['firstName'] }} {{ $passenger['lastName'] }}
                </div>
                @if(isset($passenger['dateOfBirth']))
                <p style="font-size: 9px; color: #6b7280;">Né(e) le: {{ date('d/m/Y', strtotime($passenger['dateOfBirth'])) }}</p>
                @endif
                @if(isset($passenger['passportNumber']))
                <p style="font-size: 9px; color: #6b7280;">Passeport: {{ $passenger['passportNumber'] }}</p>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Flight Details -->
        <div class="section">
            <div class="section-title">🛫 DÉTAILS DU VOL</div>
            
            @foreach($segments as $index => $segment)
            <div style="margin: 20px 0; {{ $index > 0 ? 'border-top: 2px dashed #e5e7eb; padding-top: 20px;' : '' }}">
                <p style="font-weight: bold; color: #667eea; margin-bottom: 10px;">
                    Segment {{ $index + 1 }}
                </p>
                
                <!-- Route -->
                <div class="flight-route">
                    <div class="airport">
                        <div class="airport-code">{{ $segment['departure']['iataCode'] }}</div>
                        <div class="airport-name">
                            {{ \Carbon\Carbon::parse($segment['departure']['at'])->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="flight-arrow">
                        ✈️ →
                    </div>
                    <div class="airport">
                        <div class="airport-code">{{ $segment['arrival']['iataCode'] }}</div>
                        <div class="airport-name">
                            {{ \Carbon\Carbon::parse($segment['arrival']['at'])->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Flight Info -->
                <div class="flight-details">
                    <div class="detail-row">
                        <span class="detail-label">Compagnie:</span>
                        <span class="detail-value">{{ $segment['carrierCode'] ?? 'N/A' }} {{ $segment['number'] ?? '' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Durée:</span>
                        <span class="detail-value">{{ $segment['duration'] ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Classe:</span>
                        <span class="detail-value">{{ ucfirst($segment['cabin'] ?? 'Economy') }}</span>
                    </div>
                    @if(isset($segment['aircraft']))
                    <div class="detail-row">
                        <span class="detail-label">Appareil:</span>
                        <span class="detail-value">{{ $segment['aircraft']['code'] ?? 'N/A' }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Booking Information -->
        <div class="section">
            <div class="section-title">📋 INFORMATIONS RÉSERVATION</div>
            <table>
                <tr>
                    <td style="font-weight: bold; width: 40%;">Référence:</td>
                    <td>{{ $booking->booking_reference }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Date de Réservation:</td>
                    <td>{{ $booking->created_at->format('d/m/Y à H:i') }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Statut:</td>
                    <td style="color: #10b981; font-weight: bold;">✓ CONFIRMÉ</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Montant Total:</td>
                    <td style="font-weight: bold;">{{ number_format($booking->total_amount, 0, ',', ' ') }} XOF</td>
                </tr>
            </table>
        </div>

        <!-- Barcode -->
        <div class="barcode-section">
            <p style="font-weight: bold; margin-bottom: 10px;">CODE-BARRES PNR</p>
            <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode" style="height: 60px;">
            <p style="margin-top: 5px; font-size: 14px; font-weight: bold; letter-spacing: 2px;">{{ $pnr }}</p>
        </div>

        <!-- QR Code -->
        <div class="qr-section">
            <p style="font-weight: bold; margin-bottom: 10px;">CODE DE VÉRIFICATION</p>
            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
            <p style="margin-top: 8px; font-size: 8px; color: #6b7280;">
                Scannez pour vérifier l'authenticité
            </p>
        </div>

        <!-- Important Information -->
        <div class="section">
            <div class="important-info">
                <h4>⚠️ INFORMATIONS IMPORTANTES</h4>
                <ul>
                    <li>Présentez-vous à l'aéroport <strong>3 heures avant le départ</strong> pour les vols internationaux</li>
                    <li>Munissez-vous de votre <strong>passeport valide</strong> et de ce billet électronique</li>
                    <li>Vérifiez les <strong>restrictions de bagages</strong> de votre compagnie aérienne</li>
                    <li>L'enregistrement en ligne est recommandé 24h avant le départ</li>
                    <li>Ce billet est <strong>nominatif et non transférable</strong></li>
                    <li>En cas de modification, contactez-nous au moins 24h avant le départ</li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="font-size: 10px; margin-bottom: 8px;"><strong>CARRÉ PREMIUM</strong></p>
            <p>Votre Conciergerie de Voyage Internationale</p>
            <p style="margin-top: 8px;">
                📞 +225 XX XX XX XX | ✉️ contact@carrepremium.com | 🌐 www.carrepremium.com
            </p>
            <p style="margin-top: 10px;">
                © {{ date('Y') }} Carré Premium. Tous droits réservés.<br>
                Document généré le {{ now()->format('d/m/Y à H:i') }}
            </p>
        </div>
    </div>
</body>
</html>
