# 🧪 GUIDE DE TEST DES APIs - CARRÉ PREMIUM

**Date:** 10 Janvier 2025  
**Base URL:** http://localhost:8000/api/v1

---

## 📋 PRÉREQUIS

### 1. Démarrer le serveur Laravel
```bash
cd carre-premium-backend
php artisan serve
```

### 2. Vérifier que la base de données est configurée
```bash
# Dans .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carre_premium
DB_USERNAME=root
DB_PASSWORD=

# Exécuter les migrations et seeders
php artisan migrate:fresh --seed
```

---

## ✅ TESTS DES APIs

### 🛫 FLIGHTS API

#### 1. Get All Flights
```bash
curl -X GET "http://localhost:8000/api/v1/flights" \
  -H "Accept: application/json"
```

**Réponse attendue:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "flight_number": "AF123",
        "departure_airport": {...},
        "arrival_airport": {...},
        "economy_price": 250000,
        ...
      }
    ]
  }
}
```

#### 2. Get Flight by ID
```bash
curl -X GET "http://localhost:8000/api/v1/flights/1" \
  -H "Accept: application/json"
```

#### 3. Search Flights
```bash
curl -X POST "http://localhost:8000/api/v1/flights/search" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "from": "Abidjan",
    "to": "Paris",
    "departure_date": "2025-02-15",
    "passengers": 2,
    "class": "economy"
  }'
```

#### 4. Get Popular Flights
```bash
curl -X GET "http://localhost:8000/api/v1/flights/popular?limit=6" \
  -H "Accept: application/json"
```

#### 5. Check Flight Availability
```bash
curl -X POST "http://localhost:8000/api/v1/flights/1/check-availability" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "class": "economy",
    "passengers": 2
  }'
```

#### 6. Get Airlines
```bash
curl -X GET "http://localhost:8000/api/v1/airlines" \
  -H "Accept: application/json"
```

#### 7. Get Airports
```bash
curl -X GET "http://localhost:8000/api/v1/airports?search=Paris" \
  -H "Accept: application/json"
```

---

### 🎭 EVENTS API

#### 1. Get All Events
```bash
curl -X GET "http://localhost:8000/api/v1/events" \
  -H "Accept: application/json"
```

#### 2. Get Event by ID
```bash
curl -X GET "http://localhost:8000/api/v1/events/1" \
  -H "Accept: application/json"
```

#### 3. Get Upcoming Events
```bash
curl -X GET "http://localhost:8000/api/v1/events/upcoming?limit=6" \
  -H "Accept: application/json"
```

#### 4. Get Events by Category
```bash
curl -X GET "http://localhost:8000/api/v1/events/category/2" \
  -H "Accept: application/json"
```

#### 5. Get Event Categories
```bash
curl -X GET "http://localhost:8000/api/v1/events/categories" \
  -H "Accept: application/json"
```

#### 6. Search Events
```bash
curl -X POST "http://localhost:8000/api/v1/events/search" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "q": "Roland Garros"
  }'
```

#### 7. Check Event Availability
```bash
curl -X POST "http://localhost:8000/api/v1/events/1/check-availability" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "quantity": 2,
    "zone_id": 1
  }'
```

---

### 🎒 PACKAGES API

#### 1. Get All Packages
```bash
curl -X GET "http://localhost:8000/api/v1/packages" \
  -H "Accept: application/json"
```

#### 2. Get Package by ID
```bash
curl -X GET "http://localhost:8000/api/v1/packages/1" \
  -H "Accept: application/json"
```

#### 3. Get VIP Packages
```bash
curl -X GET "http://localhost:8000/api/v1/packages/vip?limit=6" \
  -H "Accept: application/json"
```

#### 4. Get Packages by Type
```bash
curl -X GET "http://localhost:8000/api/v1/packages/type/helicopter" \
  -H "Accept: application/json"
```

#### 5. Search Packages
```bash
curl -X POST "http://localhost:8000/api/v1/packages/search" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "q": "Paris"
  }'
```

#### 6. Get Available Dates
```bash
curl -X GET "http://localhost:8000/api/v1/packages/1/available-dates" \
  -H "Accept: application/json"
```

#### 7. Check Package Availability
```bash
curl -X POST "http://localhost:8000/api/v1/packages/1/check-availability" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "participants": 4,
    "date": "2025-03-15"
  }'
```

---

### 🎠 CAROUSELS API

#### 1. Get Active Carousels
```bash
curl -X GET "http://localhost:8000/api/v1/carousels" \
  -H "Accept: application/json"
```

---

### ⚙️ SETTINGS API

#### 1. Get Public Settings
```bash
curl -X GET "http://localhost:8000/api/v1/settings" \
  -H "Accept: application/json"
```

#### 2. Get Currencies
```bash
curl -X GET "http://localhost:8000/api/v1/currencies" \
  -H "Accept: application/json"
```

---

### 🛒 CART API

#### 1. Add to Cart
```bash
curl -X POST "http://localhost:8000/api/v1/cart/add" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "item_type": "flight",
    "item_id": 1,
    "quantity": 1,
    "seat_class": "economy",
    "travel_date": "2025-02-15",
    "passenger_count": 2
  }'
```

**Réponse attendue:**
```json
{
  "success": true,
  "message": "Item added to cart",
  "data": {...},
  "session_id": "uuid-here"
}
```

#### 2. Get Cart
```bash
# Remplacer SESSION_ID par l'ID retourné lors de l'ajout
curl -X GET "http://localhost:8000/api/v1/cart/SESSION_ID" \
  -H "Accept: application/json"
```

#### 3. Update Cart Item
```bash
curl -X PUT "http://localhost:8000/api/v1/cart/1" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "quantity": 2,
    "passenger_count": 3
  }'
```

#### 4. Remove from Cart
```bash
curl -X DELETE "http://localhost:8000/api/v1/cart/1" \
  -H "Accept: application/json"
```

#### 5. Clear Cart
```bash
curl -X DELETE "http://localhost:8000/api/v1/cart/session/SESSION_ID" \
  -H "Accept: application/json"
```

---

### 📝 BOOKINGS API

#### 1. Create Booking
```bash
curl -X POST "http://localhost:8000/api/v1/bookings" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "booking_type": "flight",
    "item_id": 1,
    "travel_date": "2025-02-15",
    "number_of_passengers": 2,
    "passenger_details": [
      {
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "phone": "+225XXXXXXXXX",
        "passport_number": "AB123456"
      },
      {
        "first_name": "Jane",
        "last_name": "Doe",
        "email": "jane@example.com",
        "phone": "+225XXXXXXXXX",
        "passport_number": "CD789012"
      }
    ],
    "seat_class": "economy",
    "user_email": "john@example.com",
    "user_phone": "+225XXXXXXXXX"
  }'
```

**Réponse attendue:**
```json
{
  "success": true,
  "message": "Booking created successfully",
  "data": {...},
  "booking_number": "CP-XXXXXXXX"
}
```

#### 2. Get Booking by Number
```bash
# Remplacer BOOKING_NUMBER par le numéro retourné
curl -X GET "http://localhost:8000/api/v1/bookings/CP-XXXXXXXX" \
  -H "Accept: application/json"
```

---

## 🔍 VÉRIFICATION DES RÉSULTATS

### ✅ Succès
- Status code: 200 (GET) ou 201 (POST create)
- `"success": true` dans la réponse
- Données présentes dans `"data"`

### ❌ Erreurs Communes

#### 1. 404 Not Found
```json
{
  "success": false,
  "message": "Resource not found"
}
```
**Solution:** Vérifier que l'ID existe dans la base de données

#### 2. 422 Validation Error
```json
{
  "success": false,
  "errors": {
    "field_name": ["Error message"]
  }
}
```
**Solution:** Vérifier les données envoyées

#### 3. 500 Server Error
**Solution:** Vérifier les logs Laravel
```bash
tail -f carre-premium-backend/storage/logs/laravel.log
```

---

## 🧪 TESTS AUTOMATISÉS (Optionnel)

### Utiliser Postman
1. Importer la collection (créer un fichier JSON avec toutes les requêtes)
2. Configurer l'environnement avec `base_url`
3. Exécuter tous les tests

### Utiliser PHPUnit
```bash
cd carre-premium-backend
php artisan test
```

---

## 📊 CHECKLIST DE TEST

### Flights API
- [ ] GET /flights - Liste des vols
- [ ] GET /flights/{id} - Détails vol
- [ ] POST /flights/search - Recherche
- [ ] GET /flights/popular - Vols populaires
- [ ] POST /flights/{id}/check-availability - Disponibilité
- [ ] GET /airlines - Compagnies
- [ ] GET /airports - Aéroports

### Events API
- [ ] GET /events - Liste événements
- [ ] GET /events/{id} - Détails événement
- [ ] GET /events/upcoming - À venir
- [ ] GET /events/category/{id} - Par catégorie
- [ ] GET /events/categories - Catégories
- [ ] POST /events/search - Recherche
- [ ] POST /events/{id}/check-availability - Disponibilité

### Packages API
- [ ] GET /packages - Liste packages
- [ ] GET /packages/{id} - Détails package
- [ ] GET /packages/vip - VIP
- [ ] GET /packages/type/{type} - Par type
- [ ] POST /packages/search - Recherche
- [ ] GET /packages/{id}/available-dates - Dates disponibles
- [ ] POST /packages/{id}/check-availability - Disponibilité

### Carousels API
- [ ] GET /carousels - Carrousels actifs

### Settings API
- [ ] GET /settings - Paramètres publics
- [ ] GET /currencies - Devises

### Cart API
- [ ] POST /cart/add - Ajouter au panier
- [ ] GET /cart/{session_id} - Obtenir panier
- [ ] PUT /cart/{id} - Modifier item
- [ ] DELETE /cart/{id} - Supprimer item
- [ ] DELETE /cart/session/{session_id} - Vider panier

### Bookings API
- [ ] POST /bookings - Créer réservation
- [ ] GET /bookings/{booking_number} - Obtenir réservation

---

## 🎯 PROCHAINES ÉTAPES

Après avoir testé toutes les APIs:

1. **Connecter le frontend** - Remplacer les données statiques par les appels API
2. **Tester l'intégration** - Vérifier que le frontend affiche les données du backend
3. **Gérer les erreurs** - Ajouter des messages d'erreur appropriés
4. **Optimiser** - Ajouter du caching si nécessaire

---

**Toutes les APIs sont maintenant prêtes à être testées ! 🚀**
