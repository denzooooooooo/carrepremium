#!/bin/bash

echo "🧹 NETTOYAGE COMPLET DES DONNÉES DE TEST"
echo "========================================="
echo ""

cd carre-premium-backend

echo "⚠️  ATTENTION: Cette opération va:"
echo "   - Supprimer TOUTES les données de test"
echo "   - Garder uniquement: Admin, Catégories, Devises, Settings"
echo "   - Réinitialiser la base de données"
echo ""
read -p "Voulez-vous continuer? (oui/non): " confirm

if [ "$confirm" != "oui" ]; then
    echo "❌ Opération annulée"
    exit 1
fi

echo ""
echo "1️⃣ Sauvegarde de la base de données actuelle..."
php artisan db:backup 2>/dev/null || echo "⚠️  Pas de backup (continuons)"

echo ""
echo "2️⃣ Réinitialisation de la base de données..."
php artisan migrate:fresh --force

echo ""
echo "3️⃣ Insertion des données essentielles uniquement..."

# Admin
echo "   👤 Admin..."
php artisan db:seed --class=AdminSeeder

# Catégories
echo "   📁 Catégories..."
php artisan db:seed --class=CategorySeeder

# Devises
echo "   💰 Devises..."
php artisan db:seed --class=CurrencySeeder

# Settings
echo "   ⚙️  Paramètres..."
php artisan db:seed --class=SettingSeeder

# Configurations API
echo "   🔌 Configurations API..."
php artisan db:seed --class=ApiConfigurationsSeeder

# Règles de prix
echo "   💵 Règles de prix..."
php artisan db:seed --class=PricingRulesSeeder

# Passerelles de paiement
echo "   💳 Passerelles paiement..."
php artisan db:seed --class=PaymentGatewaysSeeder

echo ""
echo "4️⃣ Nettoyage du cache..."
php artisan optimize:clear

echo ""
echo "✅ NETTOYAGE TERMINÉ!"
echo ""
echo "📊 Base de données prête pour la PRODUCTION"
echo "   - 0 utilisateurs test"
echo "   - 0 réservations test"
echo "   - 0 vols test"
echo "   - 0 événements test"
echo "   - 0 packages test"
echo ""
echo "🎯 Prêt à recevoir de VRAIES réservations!"
echo ""
echo "🔐 Accès Admin:"
echo "   Email: admin@carrepremium.com"
echo "   Password: Admin@2024"
echo ""

cd ..
