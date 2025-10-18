#!/bin/bash

echo "🔄 Redémarrage du serveur React avec la nouvelle configuration..."
echo ""

# Aller dans le dossier frontend
cd carre-premium-frontend

# Vérifier que le fichier .env existe
if [ ! -f .env ]; then
    echo "❌ Fichier .env manquant!"
    echo "Création du fichier .env..."
    echo "REACT_APP_API_URL=http://127.0.0.1:8000/api/v1" > .env
    echo "✅ Fichier .env créé"
fi

echo "📄 Configuration .env:"
cat .env
echo ""

# Tuer les processus React existants
echo "🛑 Arrêt des serveurs React existants..."
pkill -f "react-scripts start" 2>/dev/null || true
sleep 2

# Nettoyer le cache
echo "🧹 Nettoyage du cache..."
rm -rf node_modules/.cache 2>/dev/null || true

# Démarrer React
echo ""
echo "🚀 Démarrage du serveur React..."
echo "📍 URL: http://localhost:3000"
echo "📡 API: http://127.0.0.1:8000/api/v1"
echo ""
echo "⚠️  IMPORTANT: Ouvrez la console du navigateur (F12) pour voir les logs de l'API"
echo ""

npm start
