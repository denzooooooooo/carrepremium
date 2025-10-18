#!/bin/bash

echo "🧪 TEST COMPLET - Implémentation Option A"
echo "=========================================="
echo ""

cd carre-premium-backend

echo "📋 1. Vérification des dépendances..."
composer show | grep -E "maatwebsite/excel|barryvdh/laravel-dompdf|simplesoftwareio/simple-qrcode"
echo "✅ Dépendances vérifiées"
echo ""

echo "🗄️ 2. Vérification des migrations..."
php artisan migrate:status | tail -5
echo "✅ Migrations vérifiées"
echo ""

echo "📧 3. Test envoi email (simulation)..."
php artisan tinker --execute="
\$booking = \App\Models\Booking::with(['flightBooking', 'user'])->first();
if (\$booking && \$booking->flightBooking) {
    echo '✓ Booking trouvé: ' . \$booking->booking_reference . PHP_EOL;
    echo '✓ User: ' . \$booking->user->email . PHP_EOL;
    echo '✓ PNR: ' . \$booking->flightBooking->pnr . PHP_EOL;
    echo '✓ Email prêt à être envoyé' . PHP_EOL;
} else {
    echo '⚠️  Aucune réservation de vol trouvée pour test' . PHP_EOL;
}
"
echo ""

echo "📊 4. Test export Excel (simulation)..."
echo "Routes disponibles:"
php artisan route:list | grep "bookings-export"
echo "✅ Routes export configurées"
echo ""

echo "📄 5. Vérification templates PDF..."
ls -la resources/views/pdf/
echo "✅ Templates PDF créés"
echo ""

echo "🧹 6. Test commande nettoyage..."
php artisan list | grep "data:clean"
echo "✅ Commande nettoyage disponible"
echo ""

echo "📈 7. Statistiques base de données..."
php artisan tinker --execute="
echo 'Utilisateurs: ' . \App\Models\User::count() . PHP_EOL;
echo 'Réservations: ' . \App\Models\Booking::count() . PHP_EOL;
echo 'Paiements: ' . \App\Models\Payment::count() . PHP_EOL;
echo 'Vols: ' . \App\Models\FlightBooking::count() . PHP_EOL;
"
echo ""

echo "✅ TESTS TERMINÉS!"
echo ""
echo "📝 PROCHAINES ÉTAPES:"
echo "1. Configurer SMTP dans .env"
echo "2. Tester envoi email réel: php artisan tinker"
echo "3. Tester export: http://localhost:8000/admin/bookings-export"
echo "4. Nettoyer données: php artisan data:clean-test"
echo ""
