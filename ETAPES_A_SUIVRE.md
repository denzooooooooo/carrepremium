# 📋 ÉTAPES À SUIVRE - CARRÉ PREMIUM

**Date:** 10 Janvier 2025  
**Objectif:** Rendre le site 100% fonctionnel avec données réelles

---

## 🎯 ÉTAPE 1: PRÉPARER LA BASE DE DONNÉES (15 min)

### 1.1 Configurer la base de données
```bash
cd carre-premium-backend

# Créer la base de données MySQL
mysql -u root -p
CREATE DATABASE carre_premium;
EXIT;

# Vérifier le fichier .env
nano .env
```

**Vérifier ces lignes dans .env:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carre_premium
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### 1.2 Exécuter les migrations et seeders
```bash
# Créer toutes les tables et insérer les données de test
php artisan migrate:fresh --seed

# Vous devriez voir:
# ✓ Migrations exécutées
# ✓ Seeders exécutés (Admin, Currencies, Categories, Settings, Flights, Events, Packages)
```

---

## 🚀 ÉTAPE 2: DÉMARRER LE SERVEUR BACKEND (2 min)

```bash
cd carre-premium-backend
php artisan serve

# Le serveur démarre sur: http://localhost:8000
# Gardez ce terminal ouvert
```

---

## 🧪 ÉTAPE 3: TESTER LES APIs (15 min)

### 3.1 Test rapide - Vérifier que les APIs fonctionnent

**Ouvrir un nouveau terminal et tester:**

```bash
# Test 1: Get Flights
curl -X GET "http://localhost:8000/api/v1/flights" -H "Accept: application/json"

# Vous devriez voir une réponse JSON avec des vols

# Test 2: Get Events
curl -X GET "http://localhost:8000/api/v1/events" -H "Accept: application/json"

# Test 3: Get Packages
curl -X GET "http://localhost:8000/api/v1/packages" -H "Accept: application/json"

# Test 4: Get Settings
curl -X GET "http://localhost:8000/api/v1/settings" -H "Accept: application/json"

# Test 5: Get Carousels
curl -X GET "http://localhost:8000/api/v1/carousels" -H "Accept: application/json"
```

**✅ Si vous voyez des données JSON, les APIs fonctionnent !**

**❌ Si erreur 404:** Vérifiez que le serveur Laravel est démarré

**❌ Si erreur 500:** Vérifiez les logs:
```bash
tail -f carre-premium-backend/storage/logs/laravel.log
```

---

## ⚛️ ÉTAPE 4: CONFIGURER LE FRONTEND (5 min)

### 4.1 Créer le fichier .env pour React

```bash
cd carre-premium-frontend

# Créer le fichier .env
echo "REACT_APP_API_URL=http://localhost:8000/api/v1" > .env
```

### 4.2 Installer axios si nécessaire

```bash
# Vérifier si axios est installé
npm list axios

# Si pas installé:
npm install axios
```

---

## 🔗 ÉTAPE 5: CONNECTER UNE PAGE AU BACKEND (30 min)

### 5.1 Exemple: Modifier FlightsModern.jsx

**Ouvrir:** `carre-premium-frontend/src/pages/FlightsModern.jsx`

**Remplacer le code des données statiques par:**

```javascript
import React, { useState, useEffect } from 'react';
import { flightService } from '../services/api';
import { useLanguage } from '../contexts/LanguageContext';
import { useCurrency } from '../contexts/CurrencyContext';

const FlightsModern = () => {
  const { language } = useLanguage();
  const { currency, convertPrice } = useCurrency();
  
  // États
  const [flights, setFlights] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Charger les vols depuis l'API
  useEffect(() => {
    const fetchFlights = async () => {
      try {
        setLoading(true);
        const response = await flightService.getFlights();
        
        if (response.success) {
          setFlights(response.data.data); // Les vols sont dans data.data (pagination)
        }
      } catch (err) {
        console.error('Error fetching flights:', err);
        setError('Erreur lors du chargement des vols');
      } finally {
        setLoading(false);
      }
    };

    fetchFlights();
  }, []);

  // Affichage pendant le chargement
  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="w-16 h-16 border-4 border-purple-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
          <p className="text-gray-600">Chargement des vols...</p>
        </div>
      </div>
    );
  }

  // Affichage en cas d'erreur
  if (error) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <p className="text-red-600 mb-4">{error}</p>
          <button 
            onClick={() => window.location.reload()}
            className="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700"
          >
            Réessayer
          </button>
        </div>
      </div>
    );
  }

  // Reste du code de la page (affichage des vols)
  return (
    <div className="min-h-screen bg-gray-50">
      {/* ... votre code d'affichage existant ... */}
      
      {/* Grille des vols */}
      <div className="grid grid-cols-1 gap-6">
        {flights.map((flight) => (
          <div key={flight.id} className="bg-white rounded-3xl shadow-lg p-6">
            {/* Afficher les données du vol */}
            <div className="flex items-center justify-between">
              <div>
                <h3 className="text-xl font-bold text-gray-900">
                  {flight.departure_airport?.city} → {flight.arrival_airport?.city}
                </h3>
                <p className="text-gray-600">
                  {flight.airline?.name} - {flight.flight_number}
                </p>
              </div>
              <div className="text-right">
                <p className="text-2xl font-bold text-purple-600">
                  {convertPrice(flight.economy_price)} {currency}
                </p>
                <p className="text-sm text-gray-500">par personne</p>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default FlightsModern;
```

### 5.2 Tester la page

```bash
# Dans le terminal du frontend
cd carre-premium-frontend
npm start

# Ouvrir http://localhost:3000/flights
# Vous devriez voir les vols de la base de données !
```

---

## 📄 ÉTAPE 6: RÉPÉTER POUR LES AUTRES PAGES (2-3 heures)

### Pages à modifier:

1. **HomeModern.jsx** - Carrousels, vols populaires, événements à venir
2. **EventsModern.jsx** - Liste des événements
3. **PackagesModern.jsx** - Liste des packages
4. **EventDetailsModern.jsx** - Détails événement
5. **PackageDetailsModern.jsx** - Détails package

### Template pour chaque page:

```javascript
import { useState, useEffect } from 'react';
import { nomService } from '../services/api';

const [data, setData] = useState([]);
const [loading, setLoading] = useState(true);

useEffect(() => {
  const fetchData = async () => {
    try {
      const response = await nomService.getMethode();
      setData(response.data);
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  };
  fetchData();
}, []);
```

---

## 🛒 ÉTAPE 7: TESTER LE PANIER (30 min)

### 7.1 Modifier le CartContext

**Fichier:** `carre-premium-
