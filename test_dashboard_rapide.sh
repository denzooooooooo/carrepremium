#!/bin/bash

echo "🧪 Test Rapide du Dashboard"
echo "============================"
echo ""

# Vérifier si le serveur est actif
if ! curl -s http://localhost:8000 > /dev/null 2>&1; then
    echo "❌ Serveur backend non actif"
    echo "Démarrez-le avec: cd carre-premium-backend && php artisan serve"
    exit 1
fi

echo "✅ Serveur actif"
echo ""

# Tester le dashboard
echo "📊 Test du dashboard admin..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/admin/dashboard)

if [ "$HTTP_CODE" = "200" ]; then
    echo "✅ Dashboard fonctionne! (HTTP 200)"
    echo ""
    echo "🌐 Accédez au dashboard:"
    echo "   http://localhost:8000/admin/dashboard"
    echo "   Email: admin@carrepremium.com"
    echo "   Password: Admin@2024"
elif [ "$HTTP_CODE" = "500" ]; then
    echo "❌ Erreur 500 - Problème dans le code"
    echo ""
    echo "Vérifiez les logs Laravel:"
    echo "   tail -50 carre-premium-backend/storage/logs/laravel.log"
elif [ "$HTTP_CODE" = "302" ]; then
    echo "⚠️  Redirection (302) - Probablement pas connecté"
    echo ""
    echo "Connectez-vous d'abord:"
    echo "   http://localhost:8000/admin/login"
else
    echo "⚠️  Code HTTP inattendu: $HTTP_CODE"
fi

echo ""
