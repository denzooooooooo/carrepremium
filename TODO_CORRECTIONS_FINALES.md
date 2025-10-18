# 📋 TODO - CORRECTIONS FINALES AVANT LIVRAISON

## ✅ STATUT: EN COURS

---

## 🔧 PROBLÈMES À CORRIGER

### 1. Pages Login/Register en Anglais ⏳
**Problème:** Les pages s'affichent en anglais au lieu de français par défaut

**Solution:**
- [x] Ajouter traductions auth dans translations.js
- [ ] Modifier Login.jsx pour utiliser t('auth.xxx')
- [ ] Modifier Register.jsx pour utiliser t('auth.xxx')
- [ ] Vérifier que la langue par défaut est FR dans LanguageContext

**Fichiers à modifier:**
- carre-premium-frontend/src/pages/Login.jsx
- carre-premium-frontend/src/pages/Register.jsx
- carre-premium-frontend/src/contexts/LanguageContext.jsx

---

### 2. Recherche de Vols - Erreur de Chargement ⏳
**Problème:** Message "Erreur de chargement de vols" lors de la recherche

**Causes possibles:**
- API Amadeus non configurée (clés manquantes)
- Endpoint backend non accessible
- Erreur dans amadeusService.js

**Solution:**
- [ ] Vérifier la configuration Amadeus dans .env
- [ ] Tester l'endpoint backend /api/v1/amadeus/flights/search
- [ ] Ajouter gestion d'erreur plus claire
- [ ] Créer un mode "démo" avec données fictives si API non configurée

**Fichiers à vérifier:**
- carre-premium-backend/.env
- carre-premium-backend/app/Services/AmadeusService.php
- carre-premium-frontend/src/services/amadeusService.js
- carre-premium-frontend/src/components/flights/FlightSearch.jsx

---

### 3. Chatbot Ne Fonctionne Pas ⏳
**Problème:** Le chatbot ne répond pas

**Solution:**
- [ ] Vérifier ChatbotButton.jsx
- [ ] Implémenter un chatbot simple avec réponses prédéfinies
- [ ] Ou intégrer une API de chatbot (Dialogflow, OpenAI)
- [ ] Ajouter des réponses automatiques de base

**Fichiers à modifier:**
- carre-premium-frontend/src/components/ChatbotButton.jsx
- Créer: carre-premium-frontend/src/components/Chatbot.jsx
- Créer: carre-premium-backend/app/Http/Controllers/API/ChatbotController.php

---

### 4. Informations Comptables Manquantes ⏳
**Problème:** Prix d'achat, marge bénéficiaire non affichés sur pages admin

**Solution:**
- [ ] Ajouter colonnes dans migration flights (cost_price, profit_margin)
- [ ] Ajouter colonnes dans migration events (cost_price, profit_margin)
- [ ] Ajouter colonnes dans migration tour_packages (cost_price, profit_margin)
- [ ] Modifier les vues admin pour afficher ces informations
- [ ] Ajouter calcul automatique de la marge

**Fichiers à modifier:**
- Créer nouvelle migration pour ajouter colonnes
- carre-premium-backend/resources/views/admin/flights/index.blade.php
- carre-premium-backend/resources/views/admin/events/index.blade.php
- carre-premium-backend/resources/views/admin/packages/index.blade.php
- carre-premium-backend/resources/views/admin/flights/show.blade.php
- carre-premium-backend/resources/views/admin/events/show.blade.php
- carre-premium-backend/resources/views/admin/packages/show.blade.php

---

## 🧪 TESTS À EFFECTUER

### Backend APIs
- [ ] Test inscription utilisateur (POST /api/v1/auth/register)
- [ ] Test connexion utilisateur (POST /api/v1/auth/login)
- [ ] Test recherche vols (POST /api/v1/amadeus/flights/search)
- [ ] Test création réservation
- [ ] Test génération PDF
- [ ] Test paiement

### Frontend Pages
- [ ] Test page Login en français
- [ ] Test page Register en français
- [ ] Test recherche de vols
- [ ] Test ajout au panier
- [ ] Test checkout
- [ ] Test profil utilisateur
- [ ] Test téléchargement PDF

### Admin
- [ ] Test ajout vol avec infos comptables
- [ ] Test ajout événement avec infos comptables
- [ ] Test rapports financiers
- [ ] Test modification paramètres

---

## 📅 PLANNING

**Étape 1:** Corriger Login/Register (30 min)
**Étape 2:** Corriger recherche de vols (45 min)
**Étape 3:** Activer chatbot basique (30 min)
**Étape 4:** Ajouter infos comptables (1h)
**Étape 5:** Tests complets (1h)

**Total estimé:** 3h30

---

## ✅ PROGRESSION

- [x] Traductions auth ajoutées
- [ ] Login.jsx corrigé
- [ ] Register.jsx corrigé
- [ ] Recherche vols corrigée
- [ ] Chatbot activé
- [ ] Infos comptables ajoutées
- [ ] Tests complets effectués

**Statut:** 1/7 (14%)
