#!/bin/bash

echo "🔧 Correction des erreurs du Dashboard..."

cd carre-premium-backend

# Vérifier si le serveur est actif
if ! curl -s http://localhost:8000 > /dev/null 2>&1; then
    echo "⚠️  Le serveur backend n'est pas actif"
    echo "Démarrez-le avec: cd carre-premium-backend && php artisan serve"
    echo ""
    echo "Puis relancez ce script"
    exit 1
fi

echo "✅ Serveur backend actif"
echo ""

# Tester le dashboard
echo "🧪 Test du dashboard..."
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/admin/dashboard)

if [ "$RESPONSE" = "200" ]; then
    echo "✅ Dashboard fonctionne correctement!"
    echo ""
    echo "🌐 Accédez au dashboard:"
    echo "   http://localhost:8000/admin/dashboard"
    echo ""
elif [ "$RESPONSE" = "500" ]; then
    echo "❌ Erreur 500 - Vérification des logs..."
    
    # Afficher les dernières erreurs
    tail -20 storage/logs/laravel.log 2>/dev/null | grep -A 5 "SQLSTATE" || echo "Pas d'erreur SQL trouvée dans les logs"
    
    echo ""
    echo "💡 Solutions possibles:"
    echo "1. Vérifier que toutes les migrations sont exécutées"
    echo "2. Vérifier les noms de colonnes dans DashboardController"
    echo "3. Vérifier les relations dans les modèles"
else
    echo "⚠️  Code de réponse inattendu: $RESPONSE"
fi

echo ""
echo "📊 Vérification des tables..."

# Vérifier les tables critiques
php artisan tinker --execute="
echo 'Users: ' . \App\Models\User::count() . PHP_EOL;
echo 'Bookings: ' . \App\Models\Booking::count() . PHP_EOL;
echo 'Events: ' . \App\Models\Event::count() . PHP_EOL;
echo 'Packages: ' . \App\Models\TourPackage::count() . PHP_EOL;
echo 'FlightBookings: ' . \App\Models\FlightBooking::count() . PHP_EOL;
"

echo ""
echo "✅ Script terminé"
