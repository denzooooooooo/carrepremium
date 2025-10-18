#!/bin/bash

echo "🚀 Application des corrections finales pour Carré Premium..."
echo ""

# Couleurs pour les messages
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${YELLOW}📋 CORRECTIONS À APPLIQUER:${NC}"
echo "1. ✅ Traductions auth ajoutées dans translations.js"
echo "2. ⏳ Corriger Login.jsx pour utiliser les traductions"
echo "3. ⏳ Corriger Register.jsx pour utiliser les traductions"
echo "4. ⏳ Ajouter migration pour colonnes comptables"
echo "5. ⏳ Mettre à jour pages admin avec infos comptables"
echo "6. ⏳ Créer composant Chatbot fonctionnel"
echo ""

# Vérifier que nous sommes dans le bon répertoire
if [ ! -d "carre-premium-backend" ] || [ ! -d "carre-premium-frontend" ]; then
    echo -e "${RED}❌ Erreur: Répertoires backend ou frontend non trouvés${NC}"
    echo "Assurez-vous d'être dans le répertoire racine du projet"
    exit 1
fi

echo -e "${GREEN}✅ Répertoires trouvés${NC}"
echo ""

# Créer une migration pour les colonnes comptables
echo -e "${YELLOW}📝 Création de la migration pour les colonnes comptables...${NC}"
cd carre-premium-backend
php artisan make:migration add_accounting_columns_to_products_tables
cd ..
echo -e "${GREEN}✅ Migration créée${NC}"
echo ""

# Installer react-toastify si pas déjà installé
echo -e "${YELLOW}📦 Vérification des dépendances frontend...${NC}"
cd carre-premium-frontend
if ! grep -q "react-toastify" package.json; then
    echo "Installation de react-toastify..."
    npm install react-toastify
    echo -e "${GREEN}✅ react-toastify installé${NC}"
else
    echo -e "${GREEN}✅ react-toastify déjà installé${NC}"
fi
cd ..
echo ""

echo -e "${GREEN}🎉 Script terminé !${NC}"
echo ""
echo -e "${YELLOW}📋 PROCHAINES ÉTAPES MANUELLES:${NC}"
echo "1. Modifier Login.jsx pour utiliser t('auth.xxx')"
echo "2. Modifier Register.jsx pour utiliser t('auth.xxx')"
echo "3. Remplir la migration des colonnes comptables"
echo "4. Créer le composant Chatbot.jsx"
echo "5. Mettre à jour les vues admin"
echo ""
echo -e "${YELLOW}📖 Consultez TODO_CORRECTIONS_FINALES.md pour les détails${NC}"
