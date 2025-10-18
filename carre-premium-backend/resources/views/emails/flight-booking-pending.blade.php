<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Réservation Reçue</title>
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
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
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
            margin-bottom: 20px;
            line-height: 1.8;
        }
        .status-badge {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            margin: 20px 0;
            border: 2px solid #f59e0b;
        }
        .booking-card {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
        }
        .booking-card h2 {
            color: #3b82f6;
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
        .flight-route {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 25px 0;
            font-size: 24px;
            font-weight: 700;
        }
        .timeline {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .timeline-item {
            display: flex;
            align-items: start;
            margin-bottom: 15px;
        }
        .timeline-item:last-child {
            margin-bottom: 0;
        }
        .timeline-icon {
            background: #3b82f6;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
            font-weight: bold;
        }
        .timeline-content {
            flex: 1;
        }
        .timeline-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 3px;
        }
        .timeline-desc {
            font-size: 14px;
            color: #6b7280;
        }
        .important-box {
            background: #dcfce7;
            border: 2px solid #22c55e;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
        }
        .important-box h3 {
            color: #166534;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .important-box p {
            color: #166534;
            margin: 0;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            transition: background 0.3s;
        }
        .button:hover {
            background: #2563eb;
        }
        .button-container {
            text-align: center;
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
            color: #3b82f6;
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
            <h1>✅ Demande Reçue avec Succès</h1>
            <p>Votre réservation est en cours de traitement</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <p class="greeting">Bonjour {{ $booking->customer_name }},</p>
            
            <p class="message">
                Nous avons bien reçu votre demande de réservation de vol et vous remercions pour votre confiance. 
                Votre paiement de <strong>{{ number_format($booking->total_amount, 0, ',', ' ') }} {{ $booking->currency }}</strong> 
                a été confirmé avec succès.
            </p>
            
            <div style="text-align: center;">
                <span class="status-badge">⏳ EN COURS DE TRAITEMENT</span>
            </div>
            
            <!-- Flight Route -->
            <div class="flight-route">
                {{ $flightData['departure'] ?? 'Départ' }} ✈️ {{ $flightData['arrival'] ?? 'Arrivée' }}
            </div>
            
            <!-- Booking Details Card -->
            <div class="booking-card">
                <h2>📋 Récapitulatif de votre demande</h2>
                
                <div class="detail-row">
                    <span class="detail-label">Numéro de référence</span>
                    <span class="detail-value"><strong>{{ $booking->booking_reference }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Date de demande</span>
                    <span class="detail-value">{{ $booking->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Date de voyage</span>
                    <span class="detail-value">{{ $flightData['date'] ?? 'N/A' }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Compagnie aérienne</span>
                    <span class="detail-value">{{ $flightData['airline'] ?? 'N/A' }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Classe</span>
                    <span class="detail-value">
                        @if(isset($flightData['class']))
                            @if($flightData['class'] === 'economy')
                                Économique
                            @elseif($flightData['class'] === 'business')
                                Affaires
                            @elseif($flightData['class'] === 'first')
                                Première Classe
                            @else
                                {{ ucfirst($flightData['class']) }}
                            @endif
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Nombre de passagers</span>
                    <span class="detail-value">{{ count($passengersData) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Montant payé</span>
                    <span class="detail-value"><strong>{{ number_format($booking->total_amount, 0, ',', ' ') }} {{ $booking->currency }}</strong></span>
                </div>
            </div>
            
            <!-- Timeline -->
            <div class="timeline">
                <h3 style="color: #1f2937; margin-bottom: 20px; font-size: 18px;">📅 Prochaines Étapes</h3>
                
                <div class="timeline-item">
                    <div class="timeline-icon">✓</div>
                    <div class="timeline-content">
                        <div class="timeline-title">Demande reçue</div>
                        <div class="timeline-desc">Votre demande et paiement ont été confirmés</div>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-icon">2</div>
                    <div class="timeline-content">
                        <div class="timeline-title">Traitement en cours</div>
                        <div class="timeline-desc">Notre équipe finalise votre réservation avec la compagnie aérienne</div>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-icon">3</div>
                    <div class="timeline-content">
                        <div class="timeline-title">Confirmation finale</div>
                        <div class="timeline-desc">Vous recevrez votre PNR et billets électroniques sous 2-4 heures</div>
                    </div>
                </div>
            </div>
            
            <!-- Important Box -->
            <div class="important-box">
                <h3>✨ Prix Garanti</h3>
                <p>
                    Le prix affiché lors de votre réservation est <strong>garanti</strong>. 
                    Vous ne paierez aucun frais supplémentaire. Votre réservation sera finalisée 
                    au prix exact que vous avez payé.
                </p>
            </div>
            
            <p class="message">
                Vous recevrez un nouvel email avec votre <strong>PNR (numéro de dossier)</strong> 
                et vos <strong>billets électroniques</strong> dès que notre équipe aura finalisé 
                votre réservation auprès de la compagnie aérienne.
            </p>
            
            <!-- CTA Button -->
            <div class="button-container">
                <a href="{{ config('app.frontend_url', 'http://localhost:3000') }}/my-bookings" class="button">
                    📱 Suivre ma Réservation
                </a>
            </div>
            
            <p class="message" style="margin-top: 30px; font-size: 14px; color: #6b7280;">
                <strong>Besoin d'aide ?</strong><br>
                Notre équipe est disponible 24/7 pour répondre à vos questions.<br>
                📧 contact@carrepremium.com | 📞 +225 XX XX XX XX XX
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
                Pour toute question, veuillez utiliser nos canaux de contact officiels.<br>
                Référence: {{ $booking->booking_reference }}
            </p>
        </div>
    </div>
</body>
</html>
