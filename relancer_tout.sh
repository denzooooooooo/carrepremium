#!/bin/bash

echo "🚀 RELANCEMENT COMPLET DU PROJET CARRÉ PREMIUM"
echo "=============================================="
echo ""

# Couleurs
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Arrêter tous les serveurs existants
echo -e "${YELLOW}🛑 Arrêt des serveurs existants...${NC}"
pkill -f "php artisan serve" 2>/dev/null || true
pkill -f "react-scripts start" 2>/dev/null || true
sleep 2
echo -e "${GREEN}✅ Serveurs arrêtés${NC}"
echo ""

# 2. Démarrer le backend Laravel
echo -e "${BLUE}🔧 Démarrage du backend Laravel...${NC}"
cd carre-premium-backend
php artisan serve --host=127.0.0.1 --port=8000 > /dev/null 2>&1 &
LARAVEL_PID=$!
echo -e "${GREEN}✅ Backend Laravel démarré (PID: $LARAVEL_PID)${NC}"
echo -e "   📍 URL: http://127.0.0.1:8000${NC}"
echo -e "   📍 Admin: http://127.0.0.1:8000/admin${NC}"
echo -e "   📍 API: http://127.0.0.1:8000/api/v1${NC}"
echo ""

# Attendre que Laravel démarre
sleep 3

# 3. Tester l'API
echo -e "${BLUE}🧪 Test de l'API...${NC}"
API_TEST=$(curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8000/api/v1/events)
if [ "$API_TEST" = "200" ]; then
    echo -e "${GREEN}✅ API fonctionne correctement (HTTP 200)${NC}"
else
    echo -e "${YELLOW}⚠️  API retourne: HTTP $API_TEST${NC}"
fi
echo ""

# 4. Démarrer le frontend React
echo -e "${BLUE}⚛️  Démarrage du frontend React...${NC}"
cd ../carre-premium-frontend

# Vérifier le fichier .env
if [ ! -f .env ]; then
    echo "REACT_APP_API_URL=http://127.0.0.1:8000/api/v1" > .env
    echo -e "${GREEN}✅ Fichier .env créé${NC}"
fi

# Nettoyer le cache
rm -rf node_modules/.cache 2>/dev/null || true

# Démarrer React
echo -e "${BLUE}   Démarrage en cours...${NC}"
PORT=3000 npm start > /dev/null 2>&1 &
REACT_PID=$!
echo -e "${GREEN}✅ Frontend React démarré (PID: $REACT_PID)${NC}"
echo -e "   📍 URL: http://localhost:3000${NC}"
echo ""

# 5. Attendre que React démarre
echo -e "${YELLOW}⏳ Attente du démarrage de React (30 secondes)...${NC}"
sleep 30

# 6. Résumé final
echo ""
echo "=============================================="
echo -e "${GREEN}🎉 TOUT EST LANCÉ !${NC}"
echo "=============================================="
echo ""
echo -e "${BLUE}📊 SERVEURS ACTIFS:${NC}"
echo ""
echo -e "  ${GREEN}✅ Backend Laravel${NC}"
echo -e "     🌐 Admin: http://127.0.0.1:8000/admin"
echo -e "     🔑 Email: admin@carrepremium.com"
echo -e "     🔑 Mot de passe: Admin@2024"
echo -e "     📡 API: http://127.0.0.1:8000/api/v1"
echo ""
echo -e "  ${GREEN}✅ Frontend React${NC}"
echo -e "     🌐 Site: http://localhost:3000"
echo -e "     📄 Événements: http://localhost:3000/events"
echo ""
echo "=============================================="
echo -e "${YELLOW}📝 INSTRUCTIONS:${NC}"
echo ""
echo "1. Ouvrez votre navigateur"
echo "2. Allez sur: http://localhost:3000/events"
echo "3. Appuyez sur F12 pour ouvrir la console"
echo "4. Vous devriez voir:"
echo "   🔄 Chargement des événements depuis l'API..."
echo "   ✅ Réponse API reçue"
echo "   ✅ Événements chargés: 9"
echo ""
echo "=============================================="
echo -e "${BLUE}🧪 TEST DE SYNCHRONISATION:${NC}"
echo ""
echo "1. Allez sur: http://127.0.0.1:8000/admin"
echo "2. Connectez-vous (admin@carrepremium.com / Admin@2024)"
echo "3. Allez dans 'Événements'"
echo "4. Modifiez un événement (changez le titre)"
echo "5. Sauvegardez"
echo "6. Retournez sur: http://localhost:3000/events"
echo "7. Rafraîchissez (Cmd+Shift+R ou Ctrl+Shift+R)"
echo "8. ✅ Le changement doit apparaître !"
echo ""
echo "=============================================="
echo -e "${YELLOW}🛑 POUR ARRÊTER LES SERVEURS:${NC}"
echo ""
echo "  pkill -f 'php artisan serve'"
echo "  pkill -f 'react-scripts start'"
echo ""
echo "=============================================="
echo ""
echo -e "${GREEN}✨ Bon développement ! ✨${NC}"
echo ""

# Garder le script actif
wait
