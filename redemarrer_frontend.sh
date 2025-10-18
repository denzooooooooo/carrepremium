#!/bin/bash

echo "🔄 Redémarrage du frontend React..."

# Tuer tous les processus npm/node en cours
echo "⏹️  Arrêt des processus en cours..."
pkill -f "react-scripts" 2>/dev/null || true
pkill -f "node.*carre-premium-frontend" 2>/dev/null || true
sleep 2

# Aller dans le dossier frontend
cd carre-premium-frontend

# Nettoyer le cache
echo "🧹 Nettoyage du cache..."
rm -rf node_modules/.cache 2>/dev/null || true
rm -rf build 2>/dev/null || true

# Redémarrer le serveur
echo "🚀 Démarrage du serveur React..."
echo ""
echo "✅ Le serveur va démarrer sur http://localhost:3000"
echo "📝 Appuyez sur Ctrl+C pour arrêter"
echo ""

npm start
