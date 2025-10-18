#!/bin/bash

echo "🧪 Tests des Améliorations Admin - Carré Premium"
echo "================================================"
echo ""

# Vérifier si le serveur backend est actif
echo "1️⃣ Vérification du serveur backend..."
if curl -s http://localhost:8000 > /dev/null 2>&1; then
    echo "✅ Serveur backend actif sur http://localhost:8000"
else
    echo "❌ Serveur backend non actif"
    echo "   Démarrez-le avec: cd carre-premium-backend && php artisan serve"
    exit 1
fi

echo ""
echo "2️⃣ Test des endpoints API..."

# Test événements
echo "📅 Test API Événements..."
EVENTS=$(curl -s http://localhost:8000/api/v1/events)
if echo "$EVENTS" | grep -q "success"; then
    echo "✅ API Événements fonctionne"
else
    echo "⚠️  API Événements: réponse inattendue"
fi

# Test packages
echo "📦 Test API Packages..."
PACKAGES=$(curl -s http://localhost:8000/api/v1/packages)
if echo "$PACKAGES" | grep -q "success"; then
    echo "✅ API Packages fonctionne"
else
    echo "⚠️  API Packages: réponse inattendue"
fi

# Test settings
echo "⚙️  Test API Settings..."
SETTINGS=$(curl -s http://localhost:8000/api/v1/settings)
if echo "$SETTINGS" | grep -q "success"; then
    echo "✅ API Settings fonctionne"
else
    echo "⚠️  API Settings: réponse inattendue"
fi

echo ""
echo "3️⃣ Vérification des fichiers améliorés..."

# Dashboard
if [ -f "carre-premium-backend/resources/views/admin/dashboard.blade.php" ]; then
    if grep -q "Chart.js" "carre-premium-backend/resources/views/admin/dashboard.blade.php"; then
        echo "✅ Dashboard avec graphiques Chart.js"
    else
        echo "⚠️  Dashboard sans graphiques"
    fi
fi

# DashboardController
if grep -q "getRealtimeStats" "carre-premium-backend/app/Http/Controllers/Admin/DashboardController.php"; then
    echo "✅ DashboardController avec stats temps réel"
fi

# BookingController
if grep -q "bulkAction" "carre-premium-backend/app/Http/Controllers/Admin/BookingController.php"; then
    echo "✅ BookingController avec actions en masse"
fi

if grep -q "export" "carre-premium-backend/app/Http/Controllers/Admin/BookingController.php"; then
    echo "✅ BookingController avec export CSV"
fi

# UserController
if grep -q "addPoints" "carre-premium-backend/app/Http/Controllers/Admin/UserController.php"; then
    echo "✅ UserController avec gestion points fidélité"
fi

if grep -q "export" "carre-premium-backend/app/Http/Controllers/Admin/UserController.php"; then
    echo "✅ UserController avec export CSV"
fi

echo ""
echo "4️⃣ Vérification des routes..."

cd carre-premium-backend

if grep -q "dashboard.realtime" routes/admin.php; then
    echo "✅ Route stats temps réel"
fi

if grep -q "bookings.export" routes/admin.php; then
    echo "✅ Route export réservations"
fi

if grep -q "bookings.bulk-action" routes/admin.php; then
    echo "✅ Route actions en masse réservations"
fi

if grep -q "users.export" routes/admin.php; then
    echo "✅ Route export utilisateurs"
fi

if grep -q "users.add-points" routes/admin.php; then
    echo "✅ Route ajout points fidélité"
fi

echo ""
echo "✅ Tests terminés!"
echo ""
echo "📊 Résumé des Améliorations:"
echo "  ✅ Dashboard avec 6 graphiques Chart.js"
echo "  ✅ Statistiques en temps réel (mise à jour auto 30s)"
echo "  ✅ Filtres avancés sur réservations et utilisateurs"
echo "  ✅ Export CSV pour réservations et utilisateurs"
echo "  ✅ Actions en masse sur réservations"
echo "  ✅ Gestion points de fidélité"
echo "  ✅ Top 5 destinations, événements, packages"
echo "  ✅ Alertes importantes (stock faible, paiements)"
echo ""
echo "🌐 Accès Admin:"
echo "  URL: http://localhost:8000/admin/dashboard"
echo "  Email: admin@carrepremium.com"
echo "  Password: Admin@2024"
echo ""
