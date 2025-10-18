# 🔗 CONNEXION FRONTEND-BACKEND - CARRÉ PREMIUM

**Date:** 10 Janvier 2025  
**Statut:** ✅ ARCHITECTURE COMPLÈTE

---

## 📊 ARCHITECTURE GLOBALE

```
┌─────────────────────────────────────────────────────────────┐
│                    UTILISATEUR FINAL                         │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────────┐
│              FRONTEND REACT (Port 3000)                      │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  • Pages: Home, Flights, Events, Packages, Cart      │  │
│  │  • Components: Header, Footer, Cards, Forms          │  │
│  │  • Contexts: Language, Theme, Currency, Cart         │  │
│  │  • Services: API calls (axios)                       │  │
│  └──────────────────────────────────────────────────────┘  │
└──────────────────────┬──────────────────────────────────────┘
                       │ HTTP Requests (API)
                       ▼
┌─────────────────────────────────────────────────────────────┐
│              BACKEND LARAVEL (Port 8000)                     │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  API Routes (/api/*)                                  │  │
│  │  ├─ /api/flights      → FlightController             │  │
│  │  ├─ /api/events       → EventController              │  │
│  │  ├─ /api/packages     → PackageController            │  │
│  │  ├─ /api/bookings     → BookingController            │  │
│  │  ├─ /api/cart         → CartController               │  │
│  │  └─ /api/payments     → PaymentController            │  │
│  └──────────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Admin Routes (/admin/*)                             │  │
│  │  ├─ /admin/dashboard  → DashboardController          │  │
│  │  ├─ /admin/flights    → Admin\FlightController       │  │
│  │  ├─ /admin/events     → Admin\EventController        │  │
│  │  ├─ /admin/packages   → Admin\PackageController      │  │
│  │  ├─ /admin/reviews    → Admin\ReviewController       │  │
│  │  └─ /admin/promo-codes→ Admin\PromoCodeController    │  │
│  └──────────────────────────────────────────────────────┘  │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────────┐
│                   BASE DE DONNÉES MySQL                      │
│  Tables: users, flights, events, packages, bookings, etc.   │
└─────────────────────────────────────────────────────────────┘
```

---

## ✅ PAGES ADMIN DISPONIBLES

### Menu Admin Complet:

#### 📊 **Dashboard**
- URL: `/admin/dashboard`
- Statistiques en temps réel
- Graphiques de revenus
- Dernières réservations

#### 👥 **Gestion**
1. **Utilisateurs** (`/admin/users`)
   - Liste des clients
   - Détails utilisateur
   - Historique réservations

2. **Réservations** (`/admin/bookings`)
   - Toutes les réservations
   - Confirmer/Annuler
   - Imprimer tickets

#### 📦 **Produits**
3. **Vols** (`/admin/flights`)
   - Créer/Modifier/Supprimer vols
   - Gérer disponibilités
   - Prix par classe

4. **Événements** (`/admin/events`)
   - Créer événements sportifs/culturels
   - Gérer zones de sièges
   - Upload images/vidéos

5. **Packages** (`/admin/packages`)
   - Créer packages touristiques
   - Définir itinéraires
   - Gérer prix et disponibilités

6. **Catégories** (`/admin/categories`)
   - Organiser produits
   - Hiérarchie catégories

#### 🎨 **Contenu**
7. **Carrousels** (`/admin/carousels`)
   - Gérer slides homepage
   - Upload images
   - Ordre d'affichage

#### 🏪 **Marketing** ⭐ NOUVEAU
8. **Avis Clients** (`/admin/reviews`) ✅
   - Modérer avis
   - Approuver/Rejeter
   - Répondre aux clients

9. **Codes Promo** (`/admin/promo-codes`) ✅
   - Créer codes promo
   - Définir réductions
   - Statistiques d'utilisation

#### ⚙️ **Configuration**
10. **Paramètres** (`/admin/settings`)
    - Paramètres généraux
    - Devises
    - Langues

11. **Règles de Prix** (`/admin/pricing-rules`)
    - Tarification dynamique
    - Règles saisonnières

12. **APIs** (`/admin/api-config`)
    - Configuration Amadeus
    - Clés API

13. **Paiements** (`/admin/payment-gateways`)
    - Stripe
    - Orange Money
    - MTN Mobile Money

---

## 🔄 FLUX DE DONNÉES: ADMIN → FRONTEND

### Exemple: Créer un Vol

```
1. ADMIN crée un vol
   ↓
   /admin/flights/create (Blade View)
   ↓
   POST /admin/flights (FlightController@store)
   ↓
   Enregistré dans MySQL (table flights)
   ↓
2. FRONTEND récupère les vols
   ↓
   GET /api/flights (API\FlightController@index)
   ↓
   JSON Response avec tous les vols
   ↓
   Affichage sur /flights (React)
```

### Exemple: Réservation

```
1. CLIENT sélectionne un vol sur Frontend
   ↓
   Ajoute au panier (CartContext)
   ↓
   Clique "Réserver"
   ↓
   POST /api/bookings (API\BookingController@store)
   ↓
   Enregistré dans MySQL (table bookings)
   ↓
2. ADMIN voit la réservation
   ↓
   /admin/bookings (Admin\BookingController@index)
   ↓
   Peut confirmer/annuler
```

---

## 📡 API ENDPOINTS (Frontend ↔ Backend)

### Vols
```javascript
GET    /api/flights           // Liste tous les vols
GET    /api/flights/{id}      // Détails d'un vol
POST   /api/flights/search    // Recherche vols
```

### Événements
```javascript
GET    /api/events            // Liste événements
GET    /api/events/{id}       // Détails événement
GET    /api/events/category/{type}  // Par catégorie
```

### Packages
```javascript
GET    /api/packages          // Liste packages
GET    /api/packages/{id}     // Détails package
```

### Panier
```javascript
GET    /api/cart              // Contenu panier
POST   /api/cart/add          // Ajouter article
DELETE /api/cart/{id}         // Retirer article
```

### Réservations
```javascript
POST   /api/bookings          // Créer réservation
GET    /api/bookings/{id}     // Détails réservation
```

### Paiements
```javascript
POST   /api/payments/stripe   // Payer avec Stripe
POST   /api/payments/mobile-money  // Mobile Money
```

---

## 🔧 CONFIGURATION ACTUELLE

### Frontend (React)
**Fichier:** `carre-premium-frontend/src/services/api.js`

```javascript
import axios from 'axios';

const API_URL = 'http://127.0.0.1:8000/api';

export const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Exemples d'appels
export const getFlights = () => api.get('/flights');
export const getEvents = () => api.get('/events');
export const createBooking = (data) => api.post('/bookings', data);
```

### Backend (Laravel)
**Fichier:** `carre-premium-backend/routes/api.php`

Routes API configurées pour:
- Vols
- Événements
- Packages
- Réservations
- Paiements
- Panier

**CORS activé** pour permettre les requêtes depuis React.

---

## ✅ CONNEXION FONCTIONNELLE

### Ce qui fonctionne:
1. ✅ **Admin peut créer** des vols/événements/packages
2. ✅ **Frontend peut récupérer** les données via API
3. ✅ **Clients peuvent réserver** via le frontend
4. ✅ **Admin peut gérer** les réservations
5. ✅ **Paiements** sont traités et enregistrés
6. ✅ **Avis clients** peuvent être modérés
7. ✅ **Codes promo** peuvent être appliqués

### Flux complet:
```
ADMIN crée produit
    ↓
Enregistré en BDD
    ↓
API expose les données
    ↓
FRONTEND affiche
    ↓
CLIENT réserve
    ↓
Enregistré en BDD
    ↓
ADMIN gère réservation
```

---

## 🎯 EXEMPLE PRATIQUE

### 1. Admin crée un événement

**Action:** Aller sur `/admin/events/create`
- Remplir: Titre, Date, Lieu, Prix
- Upload image
- Définir zones de sièges
- Cliquer "Enregistrer"

**Résultat:** Événement enregistré dans `events` table

### 2. Frontend affiche l'événement

**Automatique:** React appelle `GET /api/events`
- Reçoit JSON avec tous les événements
- Affiche sur `/events`
- Client peut cliquer pour voir détails

### 3. Client réserve

**Action:** Client clique "Réserver"
- Sélectionne sièges
- Ajoute au panier
- Procède au paiement
- `POST /api/bookings`

**Résultat:** Réservation dans `bookings` table

### 4. Admin voit la réservation

**Action:** Admin va sur `/admin/bookings`
- Voit la nouvelle réservation
- Peut confirmer
- Peut imprimer ticket

---

## 📝 RÉSUMÉ

### ✅ Toutes les pages admin sont dans le menu
### ✅ Frontend et Backend sont connectés via API
### ✅ Les modifications admin apparaissent sur le frontend
### ✅ Les réservations clients arrivent dans l'admin
### ✅ Le système est 100% fonctionnel

**Le cycle complet Admin → Frontend → Client → Admin fonctionne ! 🎉**
