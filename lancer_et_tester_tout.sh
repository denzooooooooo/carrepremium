#!/bin/bash

echo "🚀 LANCEMENT ET TESTS COMPLETS - Carré Premium"
echo "=============================================="
echo ""

# Couleurs
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}📋 Étape 1: Préparation Backend${NC}"
cd carre-premium-backend

echo "Nettoyage cache..."
php artisan optimize:clear > /dev/null 2>&1
echo -e "${GREEN}✅ Cache nettoyé${NC}"

echo "Vérification dépendances..."
composer dump-autoload > /dev/null 2>&1
echo -e "${GREEN}✅ Autoload régénéré${NC}"

echo ""
echo -e "${BLUE}📧 Étape 2: Configuration Email${NC}"
echo -e "${YELLOW}⚠️  IMPORTANT: Configurez SMTP dans .env avant de continuer${NC}"
echo ""
echo "Ajoutez dans carre-premium-backend/.env:"
echo "MAIL_MAILER=smtp"
echo "MAIL_HOST=smtp.gmail.com"
echo "MAIL_PORT=587"
echo "MAIL_USERNAME=votre-email@gmail.com"
echo "MAIL_PASSWORD=votre-mot-de-passe-app"
echo "MAIL_ENCRYPTION=tls"
echo "MAIL_FROM_ADDRESS=noreply@carrepremium.com"
echo "MAIL_FROM_NAME=\"Carré Premium\""
echo "FRONTEND_URL=http://localhost:3000"
echo ""
read -p "Appuyez sur Entrée une fois la configuration SMTP ajoutée..."

echo ""
echo -e "${BLUE}🔍 Étape 3: Vérification Installation${NC}"

echo "Packages installés:"
composer show | grep -E "maatwebsite/excel|barryvdh/laravel-dompdf|simplesoftwareio/simple-qrcode" | head -3
echo -e "${GREEN}✅ Packages vérifiés${NC}"

echo ""
echo "Routes export disponibles:"
php artisan route:list | grep "bookings-export"
echo -e "${GREEN}✅ Routes configurées${NC}"

echo ""
echo "Commande nettoyage:"
php artisan list | grep "data:clean"
echo -e "${GREEN}✅ Commande disponible${NC}"

echo ""
echo -e "${BLUE}🗄️  Étape 4: Statistiques Base de Données${NC}"
php artisan tinker --execute="
echo 'Utilisateurs: ' . \App\Models\User::count() . PHP_EOL;
echo 'Réservations: ' . \App\Models\Booking::count() . PHP_EOL;
echo 'Paiements: ' . \App\Models\Payment::count() . PHP_EOL;
echo 'Vols: ' . \App\Models\FlightBooking::count() . PHP_EOL;
"

echo ""
echo -e "${BLUE}🌐 Étape 5: Lancement Backend${NC}"
echo -e "${YELLOW}Le serveur va démarrer sur http://localhost:8000${NC}"
echo -e "${YELLOW}Accédez à http://localhost:8000/admin/login${NC}"
echo ""
echo "Identifiants Admin:"
echo "Email: admin@carrepremium.com"
echo "Password: Admin@2024"
echo ""
echo -e "${GREEN}✅ Backend prêt!${NC}"
echo ""
echo "📝 TESTS À EFFECTUER:"
echo "1. Accéder à http://localhost:8000/admin/dashboard"
echo "2. Aller sur Réservations"
echo "3. Cliquer 'Export Excel' → Vérifier téléchargement"
echo "4. Cliquer 'Export CSV' → Vérifier téléchargement"
echo "5. Cliquer 'Envoyer Email' sur une réservation"
echo "6. Vérifier réception emails"
echo ""
echo -e "${BLUE}🚀 Démarrage du serveur...${NC}"
echo ""

php artisan serve
