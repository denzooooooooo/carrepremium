#!/bin/bash

echo "🚀 Lancement de Carré Premium - Backend & Frontend"
echo "=================================================="

# Couleurs pour les messages
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Fonction pour tuer les processus en arrière-plan à la sortie
cleanup() {
    echo -e "\n${RED}🛑 Arrêt des serveurs...${NC}"
    kill $(jobs -p) 2>/dev/null
    exit
}

trap cleanup SIGINT SIGTERM

# Vérifier si le backend est déjà en cours d'exécution
if lsof -Pi :8000 -sTCP:LISTEN -t >/dev/null ; then
    echo -e "${RED}⚠️  Le port 8000 est déjà utilisé. Arrêt du processus...${NC}"
    kill $(lsof -t -i:8000) 2>/dev/null
    sleep 2
fi

# Vérifier si le frontend est déjà en cours d'exécution
if lsof -Pi :3000 -sTCP:LISTEN -t >/dev/null ; then
    echo -e "${RED}⚠️  Le port 3000 est déjà utilisé. Arrêt du processus...${NC}"
    kill $(lsof -t -i:3000) 2>/dev/null
    sleep 2
fi

echo ""
echo -e "${BLUE}📦 Démarrage du Backend Laravel (Port 8000)...${NC}"
cd carre-premium-backend
php artisan serve --host=127.0.0.1 --port=8000 > ../backend.log 2>&1 &
BACKEND_PID=$!
cd ..

# Attendre que le backend démarre
echo "⏳ Attente du démarrage du backend..."
sleep 3

# Vérifier si le backend est bien démarré
if lsof -Pi :8000 -sTCP:LISTEN -t >/dev/null ; then
    echo -e "${GREEN}✅ Backend démarré avec succès sur http://localhost:8000${NC}"
else
    echo -e "${RED}❌ Erreur: Le backend n'a pas pu démarrer${NC}"
    cat backend.log
    exit 1
fi

echo ""
echo -e "${BLUE}⚛️  Démarrage du Frontend React (Port 3000)...${NC}"
cd carre-premium-frontend
npm start > ../frontend.log 2>&1 &
FRONTEND_PID=$!
cd ..

# Attendre que le frontend démarre
echo "⏳ Attente du démarrage du frontend..."
sleep 5

# Vérifier si le frontend est bien démarré
if lsof -Pi :3000 -sTCP:LISTEN -t >/dev/null ; then
    echo -e "${GREEN}✅ Frontend démarré avec succès sur http://localhost:3000${NC}"
else
    echo -e "${RED}❌ Erreur: Le frontend n'a pas pu démarrer${NC}"
    cat frontend.log
    kill $BACKEND_PID 2>/dev/null
    exit 1
fi

echo ""
echo -e "${GREEN}=================================================="
echo "✅ SERVEURS LANCÉS AVEC SUCCÈS!"
echo "==================================================${NC}"
echo ""
echo -e "${BLUE}📍 URLs d'accès:${NC}"
echo "   🌐 Frontend:        http://localhost:3000"
echo "   🔧 Backend API:     http://localhost:8000/api/v1"
echo "   👨‍💼 Admin Panel:     http://localhost:8000/admin"
echo ""
echo -e "${BLUE}📋 Pages disponibles:${NC}"
echo "   • Accueil:          http://localhost:3000"
echo "   • Connexion:        http://localhost:3000/login"
echo "   • Inscription:      http://localhost:3000/register"
echo "   • Vols:             http://localhost:3000/flights"
echo "   • Événements:       http://localhost:3000/events"
echo "   • Packages:         http://localhost:3000/packages"
echo ""
echo -e "${BLUE}📝 Logs:${NC}"
echo "   • Backend:  tail -f backend.log"
echo "   • Frontend: tail -f frontend.log"
echo ""
echo -e "${RED}Pour arrêter les serveurs, appuyez sur Ctrl+C${NC}"
echo ""

# Garder le script en cours d'exécution
wait
