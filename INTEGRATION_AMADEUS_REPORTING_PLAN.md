# 🎯 PLAN D'INTÉGRATION AMADEUS + MODULE REPORTING FINANCIER

## 📋 OBJECTIFS

### 1. Intégration Amadeus API pour les Vols
- ✅ Remplacer les vols statiques par des données en temps réel d'Amadeus
- ✅ Implémenter toutes les fonctionnalités Amadeus disponibles
- ✅ Supprimer les seeders de vols statiques
- ✅ Mettre à jour le frontend pour utiliser uniquement Amadeus

### 2. Module de Reporting Financier
- ✅ Dashboard comptable avec statistiques avancées
- ✅ Filtres par client, période, service
- ✅ Graphiques et exports (PDF, Excel)
- ✅ Analyse du chiffre d'affaires

---

## 🔧 PARTIE 1: INTÉGRATION AMADEUS API

### A. Configuration Amadeus

#### 1. Fichier .env (À configurer manuellement)
```env
# Amadeus API Configuration
AMADEUS_API_KEY=your_api_key_here
AMADEUS_API_SECRET=your_api_secret_here
AMADEUS_ENVIRONMENT=test  # ou 'production'
AMADEUS_BASE_URL=https://test.api.amadeus.com
```

#### 2. Endpoints Amadeus à Implémenter

**Recherche de Vols:**
- `GET /v2/shopping/flight-offers` - Recherche de vols
- `POST /v1/shopping/flight-offers/pricing` - Prix des vols
- `POST /v1/booking/flight-orders` - Réservation

**Informations:**
- `GET /v1/reference-data/locations` - Aéroports
- `GET /v1/reference-data/airlines` - Compagnies aériennes
- `GET /v2/shopping/flight-offers/upselling` - Offres supplémentaires

**Gestion:**
- `GET /v1/booking/flight-orders/{id}` - Détails réservation
- `DELETE /v1/booking/flight-orders/{id}` - Annulation

### B. Modifications Backend

#### 1. Service Amadeus Amélioré
**Fichier:** `app/Services/AmadeusService.php`

**Nouvelles méthodes à ajouter:**
```php
// Recherche de vols
public function searchFlights($origin, $destination, $departureDate, $adults, $options = [])

// Obtenir les détails d'une offre
public function getFlightOffer($offerId)

// Vérifier la disponibilité et le prix
public function confirmPrice($flightOffer)

// Créer une réservation
public function createBooking($flightOffer, $travelers, $contacts)

// Récupérer une réservation
public function getBooking($bookingId)

// Annuler une réservation
public function cancelBooking($bookingId)

// Rechercher des aéroports
public function searchAirports($keyword)

// Obtenir les compagnies aériennes
public function getAirlines()

// Suggestions de destinations
public function getDestinationSuggestions($origin)
```

#### 2. Nouveau Contrôleur API
**Fichier:** `app/Http/Controllers/API/AmadeusFlightController.php`

**Routes:**
```php
// Recherche
POST /api/amadeus/flights/search
GET /api/amadeus/flights/offer/{id}
POST /api/amadeus/flights/confirm-price

// Réservation
POST /api/amadeus/bookings
GET /api/amadeus/bookings/{id}
DELETE /api/amadeus/bookings/{id}

// Référence
GET /api/amadeus/airports/search
GET /api/amadeus/airlines
GET /api/amadeus/destinations/suggestions
```

#### 3. Mise à Jour du Contrôleur Admin
**Fichier:** `app/Http/Controllers/Admin/FlightController.php`

**Changements:**
- Supprimer les méthodes CRUD de vols statiques
- Ajouter une vue pour consulter les réservations Amadeus
- Ajouter des filtres pour les réservations
- Permettre l'annulation de réservations

### C. Modifications Frontend

#### 1. Page de Recherche de Vols
**Fichier:** `carre-premium-frontend/src/pages/FlightsModern.jsx`

**Changements:**
- Supprimer les vols statiques
- Formulaire de recherche Amadeus
- Affichage des résultats en temps réel
- Filtres avancés (escales, compagnies, prix, horaires)
- Tri des résultats

#### 2. Service API Frontend
**Fichier:** `carre-premium-frontend/src/services/amadeusApi.js` (NOUVEAU)

```javascript
// Recherche de vols
export const searchFlights = async (searchParams) => {}

// Détails d'une offre
export const getFlightOffer = async (offerId) => {}

// Confirmation de prix
export const confirmPrice = async (flightOffer) => {}

// Créer une réservation
export const createBooking = async (bookingData) => {}
```

---

## 📊 PARTIE 2: MODULE DE REPORTING FINANCIER

### A. Base de Données

#### 1. Nouvelle Migration
**Fichier:** `database/migrations/xxxx_create_financial_reports_table.php`

```sql
CREATE TABLE financial_reports (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    report_type ENUM('daily', 'weekly', 'monthly', 'yearly', 'custom'),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_revenue DECIMAL(15, 2),
    total_bookings INT,
    total_clients INT,
    service_breakdown JSON,
    client_breakdown JSON,
    generated_by BIGINT UNSIGNED,
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (generated_by) REFERENCES admins(id)
);
```

### B. Backend - Module Reporting

#### 1. Service de Reporting
**Fichier:** `app/Services/FinancialReportingService.php` (NOUVEAU)

**Méthodes:**
```php
// Chiffre d'affaires global
public function getTotalRevenue($startDate, $endDate, $filters = [])

// CA par service
public function getRevenueByService($startDate, $endDate)

// CA par client
public function getRevenueByClient($startDate, $endDate, $limit = 10)

// CA par période
public function getRevenueByPeriod($startDate, $endDate, $groupBy = 'day')

// Statistiques de réservations
public function getBookingStatistics($startDate, $endDate)

// Top clients
public function getTopClients($startDate, $endDate, $limit = 10)

// Taux de conversion
public function getConversionRate($startDate, $endDate)

// Panier moyen
public function getAverageBasket($startDate, $endDate)

// Génération de rapport PDF
public function generatePDFReport($reportData)

// Génération de rapport Excel
public function generateExcelReport($reportData)
```

#### 2. Contrôleur Admin Reporting
**Fichier:** `app/Http/Controllers/Admin/ReportingController.php` (NOUVEAU)

**Méthodes:**
```php
public function index() // Dashboard reporting
public function revenue() // Rapport CA
public function clients() // Rapport clients
public function services() // Rapport par service
public function export($type) // Export PDF/Excel
public function customReport(Request $request) // Rapport personnalisé
```

### C. Frontend Admin - Dashboard Reporting

#### 1. Page de Reporting
**Fichier:** `carre-premium-backend/resources/views/admin/reporting/index.blade.php` (NOUVEAU)

**Sections:**
```html
<!-- Filtres -->
- Période (date début/fin, prédéfinis: aujourd'hui, semaine, mois, année)
- Service (tous, vols, événements, packages)
- Client (recherche par nom/email)
- Statut (tous, payé, en attente, annulé)

<!-- KPIs -->
- Chiffre d'affaires total
- Nombre de réservations
- Panier moyen
- Taux de conversion

<!-- Graphiques -->
- Évolution du CA (ligne)
- CA par service (camembert)
- Top 10 clients (barres)
- Réservations par jour (barres)

<!-- Tableaux -->
- Détail des transactions
- Liste des clients
- Répartition par service

<!-- Actions -->
- Exporter en PDF
- Exporter en Excel
- Imprimer
- Envoyer par email
```

#### 2. Graphiques avec Chart.js
**Installation:**
```bash
npm install chart.js
```

**Composants:**
- Graphique en ligne (évolution CA)
- Graphique en camembert (répartition services)
- Graphique en barres (top clients)

---

## 📝 ÉTAPES D'IMPLÉMENTATION

### Phase 1: Configuration Amadeus (Jour 1)
1. ✅ Mettre à jour `AmadeusService.php` avec toutes les méthodes
2. ✅ Créer `AmadeusFlightController.php`
3. ✅ Ajouter les routes API
4. ✅ Tester les endpoints Amadeus

### Phase 2: Frontend Amadeus (Jour 2)
1. ✅ Créer `amadeusApi.js`
2. ✅ Mettre à jour `FlightsModern.jsx`
3. ✅ Supprimer les données statiques
4. ✅ Tester la recherche de vols

### Phase 3: Admin Amadeus (Jour 3)
1. ✅ Mettre à jour la page admin des vols
2. ✅ Ajouter la gestion des réservations Amadeus
3. ✅ Tester les annulations

### Phase 4: Module Reporting (Jour 4-5)
1. ✅ Créer la migration `financial_reports`
2. ✅ Créer `FinancialReportingService.php`
3. ✅ Créer `ReportingController.php`
4. ✅ Créer les vues admin de reporting
5. ✅ Intégrer Chart.js
6. ✅ Tester tous les filtres et exports

---

## 🔑 POINTS IMPORTANTS

### Amadeus API
- **Mode Test:** Utiliser l'environnement de test pour le développement
- **Rate Limiting:** Amadeus a des limites d'appels API
- **Cache:** Mettre en cache les résultats de recherche (5-10 minutes)
- **Gestion d'erreurs:** Prévoir des fallbacks en cas d'indisponibilité

### Reporting
- **Performance:** Utiliser des index sur les dates et statuts
- **Cache:** Mettre en cache les rapports fréquemment consultés
- **Permissions:** Seuls les admins et comptables peuvent accéder
- **Exports:** Limiter la taille des exports (max 10 000 lignes)

---

## 📚 DOCUMENTATION À CRÉER

1. **Guide d'utilisation Amadeus** pour les admins
2. **Guide de reporting** pour les comptables
3. **Documentation API** pour les développeurs
4. **FAQ** pour les utilisateurs

---

## 🎯 RÉSULTAT ATTENDU

### Vols Amadeus
- ✅ Recherche en temps réel
- ✅ Prix actualisés
- ✅ Réservation directe
- ✅ Gestion des annulations
- ✅ Historique des réservations

### Reporting Financier
- ✅ Dashboard complet avec KPIs
- ✅ Filtres multiples (client, période, service)
- ✅ Graphiques interactifs
- ✅ Exports PDF et Excel
- ✅ Analyse détaillée du CA
- ✅ Top clients et services
- ✅ Rapports personnalisés

---

**Date de création:** 2025-01-10  
**Statut:** 📋 PLANIFICATION COMPLÈTE  
**Prochaine étape:** Validation du plan et début de l'implémentation
