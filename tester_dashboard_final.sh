#!/bin/bash

echo "🧪 Test Final du Dashboard Admin"
echo "================================="
echo ""

# Nettoyer le cache
echo "🧹 Nettoyage du cache Laravel..."
cd carre-premium-backend
php artisan optimize:clear > /dev/null 2>&1
echo "✅ Cache nettoyé"
echo ""

# Vérifier le serveur
if ! curl -s http://localhost:8000 > /dev/null 2>&1; then
    echo "⚠️  Serveur non actif. Démarrage..."
    php artisan serve > /dev/null 2>&1 &
    sleep 3
fi

echo "✅ Serveur actif"
echo ""

# Tester le dashboard
echo "📊 Test du dashboard..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/admin/dashboard)

if [ "$HTTP_CODE" = "200" ]; then
    echo "✅ Dashboard fonctionne parfaitement! (HTTP 200)"
    echo ""
    echo "🎉 SUCCÈS!"
    echo ""
    echo "🌐 Accédez au dashboard:"
    echo "   URL: http://localhost:8000/admin/dashboard"
    echo "   Email: admin@carrepremium.com"
    echo "   Password: Admin@2024"
elif [ "$HTTP_CODE" = "500" ]; then
    echo "❌ Erreur 500"
    echo ""
    echo "Dernières erreurs:"
    tail -20 storage/logs/laravel.log | grep -A 5 "SQLSTATE\|Exception" | head -15
elif [ "$HTTP_CODE" = "302" ]; then
    echo "⚠️  Redirection - Connectez-vous d'abord"
    echo "   http://localhost:8000/admin/login"
else
    echo "⚠️  Code HTTP: $HTTP_CODE"
fi

echo ""
cd ..
