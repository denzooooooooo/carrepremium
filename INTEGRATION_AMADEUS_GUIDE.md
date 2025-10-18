# 🚀 GUIDE D'INTÉGRATION COMPLÈTE - CARRÉ PREMIUM

## 📋 Vue d'Ensemble

Ce document détaille l'architecture complète pour rendre la plateforme **100% fonctionnelle** avec :
- ✈️ Intégration Amadeus pour les billets d'avion
- 🎫 Gestion automatisée des événements avec QR codes
- 🏝️ Gestion des packages touristiques
- 💰 Calcul automatique des marges et commissions
- 🧾 Génération automatique de reçus et billets
- 💳 Paiement en ligne sécurisé

---

## 1️⃣ BILLETS D'AVION - INTÉGRATION AMADEUS

### Architecture Technique

```
Client → Site Web → Laravel Backend → Amadeus API → Compagnies Aériennes
                         ↓
                    Base de Données
                         ↓
                    Génération E-Ticket + Reçu
```

### Tables Base de Données Nécessaires

#### Table: `api_configurations`
```sql
CREATE TABLE api_configurations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    provider VARCHAR(50) NOT NULL, -- 'amadeus', 'sabre', etc.
    api_key VARCHAR(255) NOT NULL,
    api_secret VARCHAR(255) NOT NULL,
    endpoint_url VARCHAR(255) NOT NULL,
    is_production BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Table: `flight_bookings` (Extension de bookings)
```sql
CREATE TABLE flight_bookings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    booking_id BIGINT NOT NULL,
    pnr VARCHAR(10) NOT NULL, -- Passenger Name Record
    eticket_number VARCHAR(20) NOT NULL,
    amadeus_booking_ref VARCHAR(50),
    flight_segments JSON, -- Détails des segments de vol
    base_price DECIMAL(10,2) NOT NULL,
    taxes DECIMAL(10,2) NOT NULL,
    margin_amount DECIMAL(10,2) NOT NULL,
    margin_percentage DECIMAL(5,2) NOT NULL,
    final_price DECIMAL(10,2) NOT NULL,
    ticket_status ENUM('issued', 'cancelled', 'refunded') DEFAULT 'issued',
    ticket_pdf_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);
```

#### Table: `pricing_rules`
```sql
CREATE TABLE pricing_rules (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    product_type ENUM('flight', 'event', 'package') NOT NULL,
    rule_name VARCHAR(100) NOT NULL,
    margin_type ENUM('percentage', 'fixed') NOT NULL,
    margin_value DECIMAL(10,2) NOT NULL,
    min_price DECIMAL(10,2),
    max_price DECIMAL(10,2),
    is_active BOOLEAN DEFAULT TRUE,
    priority INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Service Amadeus

**Fichier: `app/Services/AmadeusService.php`**

Fonctionnalités:
- ✅ Recherche de vols en temps réel
- ✅ Vérification de disponibilité
- ✅ Création de réservation (PNR)
- ✅ Émission de billet (E-ticket)
- ✅ Annulation et remboursement
- ✅ Récupération des détails de vol

### Workflow Complet

```
1. CLIENT RECHERCHE VOL
   ↓
2. AMADEUS API: Flight Offers Search
   ↓
3. AFFICHAGE RÉSULTATS + MARGE APPLIQUÉE
   ↓
4. CLIENT SÉLECTIONNE + PAIE
   ↓
5. AMADEUS API: Create Booking (PNR)
   ↓
6. AMADEUS API: Issue Ticket (E-ticket)
   ↓
7. GÉNÉRATION REÇU PDF
   ↓
8. ENVOI EMAIL: E-ticket + Reçu + Instructions
   ↓
9. STOCKAGE BDD + ARCHIVAGE
```

---

## 2️⃣ ÉVÉNEMENTS - GESTION COMPLÈTE

### Tables Nécessaires

#### Table: `event_tickets`
```sql
CREATE TABLE event_tickets (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    booking_id BIGINT NOT NULL,
    event_id BIGINT NOT NULL,
    ticket_number VARCHAR(20) UNIQUE NOT NULL,
    qr_code VARCHAR(255) NOT NULL, -- Chemin vers QR code
    qr_data TEXT NOT NULL, -- Données encodées
    seat_zone_id BIGINT,
    seat_number VARCHAR(20),
    base_price DECIMAL(10,2) NOT NULL,
    margin_amount DECIMAL(10,2) NOT NULL,
    final_price DECIMAL(10,2) NOT NULL,
    ticket_status ENUM('valid', 'used', 'cancelled') DEFAULT 'valid',
    used_at TIMESTAMP NULL,
    ticket_pdf_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (event_id) REFERENCES events(id)
);
```

#### Table: `event_inventory`
```sql
CREATE TABLE event_inventory (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    event_id BIGINT NOT NULL,
    seat_zone_id BIGINT,
    total_tickets INT NOT NULL,
    sold_tickets INT DEFAULT 0,
    reserved_tickets INT DEFAULT 0,
    available_tickets INT NOT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id),
    FOREIGN KEY (seat_zone_id) REFERENCES event_seat_zones(id)
);
```

### Service Événements

**Fichier: `app/Services/EventTicketService.php`**

Fonctionnalités:
- ✅ Vérification disponibilité en temps réel
- ✅ Réservation temporaire (10 min)
- ✅ Génération QR code unique
- ✅ Génération billet PDF avec QR
- ✅ Validation à l'entrée (scan QR)
- ✅ Gestion des annulations

### Format QR Code

```json
{
  "ticket_id": "TKT-2024-001234",
  "event_id": 15,
  "event_name": "Roland Garros 2024",
  "holder_name": "Jean Dupont",
  "seat": "Tribune A - Rang 12 - Siège 45",
  "date": "2024-06-05",
  "time": "14:00",
  "validation_code": "ABC123XYZ789",
  "issued_at": "2024-01-15T10:30:00Z"
}
```

---

## 3️⃣ PACKAGES TOURISTIQUES

### Tables Nécessaires

#### Table: `package_bookings`
```sql
CREATE TABLE package_bookings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    booking_id BIGINT NOT NULL,
    package_id BIGINT NOT NULL,
    confirmation_number VARCHAR(20) UNIQUE NOT NULL,
    travel_date DATE NOT NULL,
    participants_count INT NOT NULL,
    participants_details JSON NOT NULL,
    base_price DECIMAL(10,2) NOT NULL,
    margin_amount DECIMAL(10,2) NOT NULL,
    final_price DECIMAL(10,2) NOT NULL,
    voucher_pdf_path VARCHAR(255),
    itinerary_pdf_path VARCHAR(255),
    status ENUM('confirmed', 'pending', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (package_id) REFERENCES tour_packages(id)
);
```

#### Table: `package_inventory`
```sql
CREATE TABLE package_inventory (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    package_id BIGINT NOT NULL,
    available_date DATE NOT NULL,
    max_participants INT NOT NULL,
    booked_participants INT DEFAULT 0,
    available_spots INT NOT NULL,
    price_override DECIMAL(10,2),
    is_available BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (package_id) REFERENCES tour_packages(id),
    UNIQUE KEY unique_package_date (package_id, available_date)
);
```

---

## 4️⃣ SYSTÈME DE MARGES ET PRICING

### Configuration des Marges

**Dans l'Admin:**

```php
// Exemples de règles de pricing
[
    'flights' => [
        'domestic' => ['type' => 'percentage', 'value' => 5],
        'international' => ['type' => 'percentage', 'value' => 8],
        'business_class' => ['type' => 'percentage', 'value' => 10]
    ],
    'events' => [
        'sports' => ['type' => 'percentage', 'value' => 15],
        'concerts' => ['type' => 'percentage', 'value' => 20],
        'vip' => ['type' => 'fixed', 'value' => 50000]
    ],
    'packages' => [
        'helicopter' => ['type' => 'percentage', 'value' => 25],
        'private_jet' => ['type' => 'percentage', 'value' => 20],
        'standard' => ['type' => 'percentage', 'value' => 15]
    ]
]
```

### Service de Calcul

**Fichier: `app/Services/PricingService.php`**

```php
class PricingService {
    public function calculateFinalPrice($basePrice, $productType, $category) {
        // 1. Récupérer la règle applicable
        // 2. Calculer la marge
        // 3. Ajouter les taxes
        // 4. Retourner prix final + détails
    }
}
```

---

## 5️⃣ GÉNÉRATION DE DOCUMENTS

### Types de Documents

1. **E-Ticket Avion** (PDF)
   - Informations passager
   - Détails du vol (PNR, E-ticket number)
   - Itinéraire complet
   - Conditions tarifaires
   - QR code de vérification

2. **Billet Événement** (PDF)
   - QR code principal
   - Informations événement
   - Siège/Zone
   - Instructions d'accès
   - Conditions d'utilisation

3. **Voucher Package** (PDF)
   - Numéro de confirmation
   - Itinéraire détaillé
   - Services inclus/exclus
   - Contacts d'urgence
   - Instructions

4. **Reçu de Paiement** (PDF)
   - Détails transaction
   - Décomposition prix (base + marge + taxes)
   - Méthode de paiement
   - Informations légales

### Service de Génération

**Fichier: `app/Services/DocumentGeneratorService.php`**

Utilise: **DomPDF** ou **TCPDF**

---

## 6️⃣ WORKFLOW COMPLET DE RÉSERVATION

### Étape par Étape

```
1. CLIENT SÉLECTIONNE PRODUIT
   ↓
2. VÉRIFICATION DISPONIBILITÉ TEMPS RÉEL
   ↓
3. AJOUT AU PANIER
   ↓
4. CALCUL PRIX FINAL (base + marge + taxes)
   ↓
5. CHECKOUT - INFORMATIONS PASSAGERS
   ↓
6. PAIEMENT EN LIGNE
   ↓
7. SI VOL: Amadeus Create Booking + Issue Ticket
   SI ÉVÉNEMENT: Génération QR + Réservation siège
   SI PACKAGE: Confirmation + Génération voucher
   ↓
8. GÉNÉRATION TOUS LES DOCUMENTS PDF
   ↓
9. ENVOI EMAIL AVEC PIÈCES JOINTES
   ↓
10. MISE À JOUR INVENTAIRE
   ↓
11. CRÉATION NOTIFICATION ADMIN
   ↓
12. ARCHIVAGE TRANSACTION
```

---

## 7️⃣ INTÉGRATIONS PAIEMENT

### Providers Recommandés

1. **Stripe** (International)
2. **PayPal** (International)
3. **Mobile Money** (Côte d'Ivoire)
   - Orange Money
   - MTN Money
   - Moov Money
4. **Virement Bancaire**

### Table: `payment_gateways`
```sql
CREATE TABLE payment_gateways (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    gateway_name VARCHAR(50) NOT NULL,
    gateway_type VARCHAR(50) NOT NULL,
    api_key VARCHAR(255),
    api_secret VARCHAR(255),
    merchant_id VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    supported_currencies JSON,
    transaction_fee_percentage DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 8️⃣ SÉCURITÉ ET CONFORMITÉ

### Mesures de Sécurité

1. **Chiffrement des Données Sensibles**
   - API Keys chiffrées
   - Données bancaires jamais stockées
   - PCI DSS Compliance

2. **Validation des Transactions**
   - 3D Secure pour cartes
   - Vérification OTP pour Mobile Money
   - Double authentification admin

3. **Audit Trail**
   - Logs de toutes les transactions
   - Historique des modifications
   - Traçabilité complète

---

## 9️⃣ INTERFACE ADMIN - GESTION COMPLÈTE

### Fonctionnalités Admin

1. **Dashboard**
   - Statistiques temps réel
   - Revenus et marges
   - Réservations du jour

2. **Gestion Vols**
   - Synchronisation Amadeus
   - Gestion des PNR
   - Annulations/Remboursements

3. **Gestion Événements**
   - Création événements
   - Gestion inventaire
   - Scan QR codes (app mobile)

4. **Gestion Packages**
   - Catalogue complet
   - Calendrier disponibilités
   - Confirmations manuelles

5. **Configuration Marges**
   - Règles de pricing
   - Ajustements dynamiques
   - Promotions

6. **Rapports**
   - Rapport financier
   - Rapport de ventes
   - Export Excel/PDF

---

## 🔟 PROCHAINES ÉTAPES D'IMPLÉMENTATION

### Phase 1: Infrastructure (Semaine 1-2)
- [ ] Créer toutes les tables supplémentaires
- [ ] Configurer les services de base
- [ ] Mettre en place l'architecture API

### Phase 2: Intégration Amadeus (Semaine 3-4)
- [ ] Service Amadeus complet
- [ ] Tests avec API Sandbox
- [ ] Génération E-tickets

### Phase 3: Événements & Packages (Semaine 5-6)
- [ ] Service QR codes
- [ ] Gestion inventaire
- [ ] Génération billets/vouchers

### Phase 4: Paiement & Documents (Semaine 7-8)
- [ ] Intégration gateways
- [ ] Génération PDF
- [ ] Système d'emails

### Phase 5: Tests & Déploiement (Semaine 9-10)
- [ ] Tests end-to-end
- [ ] Formation admin
- [ ] Mise en production

---

## 📞 INFORMATIONS REQUISES DU CLIENT

### Pour Amadeus
- [ ] Identifiants API (Client ID + Secret)
- [ ] Type de compte (Test/Production)
- [ ] Marges souhaitées par type de vol

### Pour Événements
- [ ] Liste complète des événements
- [ ] Prix de base par catégorie
- [ ] Marges souhaitées

### Pour Packages
- [ ] Catalogue complet
- [ ] Prix fournisseurs
- [ ] Marges souhaitées

### Pour Paiement
- [ ] Comptes marchands (Stripe, PayPal, etc.)
- [ ] Numéros Mobile Money
- [ ] Coordonnées bancaires

---

## 📚 DOCUMENTATION TECHNIQUE

Tous les services seront documentés avec:
- Diagrammes de flux
- Exemples de code
- Guide d'utilisation admin
- API Documentation

---

**Prêt à implémenter ! 🚀**
