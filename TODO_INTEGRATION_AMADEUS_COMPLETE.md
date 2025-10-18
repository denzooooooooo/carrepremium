# 🎯 TODO - Intégration Complète Amadeus Flight Create Orders

## Objectif
Implémenter la réservation réelle via Amadeus avec:
- ✅ Recherche de vols (FAIT)
- ⏳ Confirmation du prix avant réservation
- ⏳ Ajout des services auxiliaires (bagages, repas, sièges)
- ⏳ Création de la réservation (PNR)
- ⏳ Émission des billets électroniques
- ⏳ Envoi des confirmations par email

## Étapes à Suivre

### 1. Backend - AmadeusService.php
- [x] Méthode `searchFlights()` - FAIT
- [x] Méthode `getAccessToken()` - FAIT
- [x] Méthode `createBooking()` - EXISTE mais à améliorer
- [ ] Ajouter méthode `addSeatSelection()`
- [ ] Ajouter méthode `addBaggageOption()`
- [ ] Ajouter méthode `addMealPreference()`
- [ ] Améliorer `createBooking()` pour inclure les services

### 2. Backend - BookingController.php
- [ ] Endpoint `POST /api/bookings/flights/confirm-price`
- [ ] Endpoint `POST /api/bookings/flights/create`
- [ ] Validation des données passagers
- [ ] Gestion des erreurs Amadeus
- [ ] Sauvegarde en BDD

### 3. Frontend - bookingService.js
- [ ] Méthode `confirmFlightPrice()`
- [ ] Méthode `createFlightBooking()`
- [ ] Gestion des erreurs

### 4. Frontend - FlightDetailsComplete.jsx
- [ ] Appeler `confirmFlightPrice()` avant paiement
- [ ] Appeler `createFlightBooking()` après paiement
- [ ] Afficher le PNR et e-tickets
- [ ] Gérer les erreurs de réservation

### 5. Tests
- [ ] Test recherche → sélection → réservation
- [ ] Test avec options (bagages, repas, sièges)
- [ ] Test annulation
- [ ] Test emails de confirmation

## Priorité
🔴 HAUTE - Nécessaire pour production réelle
