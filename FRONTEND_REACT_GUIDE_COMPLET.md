# 🎨 GUIDE COMPLET - FRONTEND REACT CARRÉ PREMIUM

## 📊 ÉTAT ACTUEL

### ✅ CONFIGURATION TERMINÉE
1. **Projet React** - Initialisé
2. **TailwindCSS** - Configuré avec thème violet/doré
3. **PostCSS** - Configuré
4. **Styles de base** - Créés avec animations
5. **App.js** - Structure de routing créée

---

## 🗂️ STRUCTURE DES FICHIERS À CRÉER

```
carre-premium-frontend/
├── public/
│   ├── index.html
│   ├── favicon.ico
│   └── images/
├── src/
│   ├── App.js ✅
│   ├── index.js
│   ├── index.css ✅
│   ├── components/
│   │   ├── common/
│   │   │   ├── Button.jsx
│   │   │   ├── Card.jsx
│   │   │   ├── Input.jsx
│   │   │   ├── Modal.jsx
│   │   │   ├── Loader.jsx
│   │   │   ├── Badge.jsx
│   │   │   └── Alert.jsx
│   │   ├── layout/
│   │   │   ├── Header.jsx
│   │   │   ├── Footer.jsx
│   │   │   ├── Navbar.jsx
│   │   │   └── MobileMenu.jsx
│   │   ├── home/
│   │   │   ├── HeroSection.jsx
│   │   │   ├── SearchBar.jsx
│   │   │   ├── Categories.jsx
│   │   │   ├── FeaturedFlights.jsx
│   │   │   ├── UpcomingEvents.jsx
│   │   │   ├── PopularPackages.jsx
│   │   │   └── Testimonials.jsx
│   │   ├── flights/
│   │   │   ├── FlightCard.jsx
│   │   │   ├── FlightFilters.jsx
│   │   │   ├── FlightSearchForm.jsx
│   │   │   └── FlightTimeline.jsx
│   │   ├── events/
│   │   │   ├── EventCard.jsx
│   │   │   ├── EventFilters.jsx
│   │   │   └── SeatSelector.jsx
│   │   ├── packages/
│   │   │   ├── PackageCard.jsx
│   │   │   ├── PackageFilters.jsx
│   │   │   └── Itinerary.jsx
│   │   ├── cart/
│   │   │   ├── CartItem.jsx
│   │   │   └── CartSummary.jsx
│   │   └── checkout/
│   │       ├── PersonalInfo.jsx
│   │       ├── PassengerInfo.jsx
│   │       ├── PaymentForm.jsx
│   │       └── OrderSummary.jsx
│   ├── pages/
│   │   ├── Home.jsx
│   │   ├── Flights.jsx
│   │   ├── FlightDetails.jsx
│   │   ├── Events.jsx
│   │   ├── EventDetails.jsx
│   │   ├── Packages.jsx
│   │   ├── PackageDetails.jsx
│   │   ├── Cart.jsx
│   │   ├── Checkout.jsx
│   │   ├── Confirmation.jsx
│   │   ├── About.jsx
│   │   ├── Contact.jsx
│   │   ├── FAQ.jsx
│   │   ├── Terms.jsx
│   │   ├── Privacy.jsx
│   │   ├── auth/
│   │   │   ├── Login.jsx
│   │   │   └── Register.jsx
│   │   └── account/
│   │       ├── Dashboard.jsx
│   │       ├── MyBookings.jsx
│   │       ├── MyFavorites.jsx
│   │       └── Profile.jsx
│   ├── contexts/
│   │   ├── LanguageContext.jsx
│   │   ├── ThemeContext.jsx
│   │   ├── CurrencyContext.jsx
│   │   ├── CartContext.jsx
│   │   └── AuthContext.jsx
│   ├── hooks/
│   │   ├── useApi.js
│   │   ├── useAuth.js
│   │   ├── useCart.js
│   │   └── useDebounce.js
│   ├── services/
│   │   ├── api.js
│   │   ├── flightService.js
│   │   ├── eventService.js
│   │   ├── packageService.js
│   │   ├── bookingService.js
│   │   └── authService.js
│   ├── utils/
│   │   ├── formatters.js
│   │   ├── validators.js
│   │   └── constants.js
│   └── assets/
│       ├── images/
│       └── icons/
├── tailwind.config.js ✅
├── postcss.config.js ✅
└── package.json
```

---

## 📦 DÉPENDANCES À INSTALLER

```bash
cd carre-premium-frontend

# Routing
npm install react-router-dom

# State Management
npm install @reduxjs/toolkit react-redux
# OU
npm install zustand

# HTTP Client
npm install axios

# Forms
npm install react-hook-form yup @hookform/resolvers

# UI Components
npm install @headlessui/react
npm install framer-motion
npm install swiper
npm install react-icons

# Date Picker
npm install react-datepicker date-fns

# Notifications
npm install react-hot-toast

# i18n
npm install react-i18next i18next

# Utils
npm install classnames
npm install lodash
```

---

## 🎨 COMPOSANTS PRIORITAIRES

### 1. **Layout Components** (Jour 1)

#### Header.jsx
```jsx
import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { useLanguage } from '../../contexts/LanguageContext';
import { useTheme } from '../../contexts/ThemeContext';
import { useCurrency } from '../../contexts/CurrencyContext';
import { useCart } from '../../contexts/CartContext';

const Header = () => {
  const { language, setLanguage } = useLanguage();
  const { theme, toggleTheme } = useTheme();
  const { currency, setCurrency } = useCurrency();
  const { cart } = useCart();
  
  return (
    <header className="bg-white dark:bg-gray-900 shadow-md sticky top-0 z-50">
      <div className="container-custom">
        <div className="flex items-center justify-between h-20">
          {/* Logo */}
          <Link to="/" className="flex items-center">
            <span className="text-2xl font-montserrat font-bold gradient-text">
              Carré Premium
            </span>
          </Link>
          
          {/* Navigation */}
          <nav className="hidden md:flex items-center space-x-8">
            <Link to="/flights" className="hover:text-primary">Vols</Link>
            <Link to="/events" className="hover:text-primary">Événements</Link>
            <Link to="/packages" className="hover:text-primary">Packages</Link>
          </nav>
          
          {/* Actions */}
          <div className="flex items-center space-x-4">
            {/* Language Switcher */}
            <select 
              value={language} 
              onChange={(e) => setLanguage(e.target.value)}
              className="input"
            >
              <option value="fr">🇫🇷 FR</option>
              <option value="en">🇬🇧 EN</option>
            </select>
            
            {/* Currency Switcher */}
            <select 
              value={currency} 
              onChange={(e) => setCurrency(e.target.value)}
              className="input"
            >
              <option value="XOF">XOF</option>
              <option value="EUR">EUR</option>
              <option value="USD">USD</option>
            </select>
            
            {/* Theme Toggle */}
            <button onClick={toggleTheme} className="btn-ghost">
              {theme === 'light' ? '🌙' : '☀️'}
            </button>
            
            {/* Cart */}
            <Link to="/cart" className="relative btn-ghost">
              🛒
              {cart.length > 0 && (
                <span className="absolute -top-2 -right-2 bg-primary text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                  {cart.length}
                </span>
              )}
            </Link>
            
            {/* Account */}
            <Link to="/account" className="btn-primary">
              Mon Compte
            </Link>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;
```

#### Footer.jsx
```jsx
import React from 'react';
import { Link } from 'react-router-dom';

const Footer = () => {
  return (
    <footer className="bg-primary text-white py-12">
      <div className="container-custom">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          {/* About */}
          <div>
            <h3 className="text-xl font-bold mb-4">Carré Premium</h3>
            <p className="text-sm opacity-90">
              Votre partenaire de confiance pour vos voyages et événements.
            </p>
          </div>
          
          {/* Links */}
          <div>
            <h4 className="font-semibold mb-4">Liens Rapides</h4>
            <ul className="space-y-2 text-sm">
              <li><Link to="/flights" className="hover:text-gold">Vols</Link></li>
              <li><Link to="/events" className="hover:text-gold">Événements</Link></li>
              <li><Link to="/packages" className="hover:text-gold">Packages</Link></li>
            </ul>
          </div>
          
          {/* Support */}
          <div>
            <h4 className="font-semibold mb-4">Support</h4>
            <ul className="space-y-2 text-sm">
              <li><Link to="/faq" className="hover:text-gold">FAQ</Link></li>
              <li><Link to="/contact" className="hover:text-gold">Contact</Link></li>
              <li><Link to="/terms" className="hover:text-gold">CGU</Link></li>
            </ul>
          </div>
          
          {/* Contact */}
          <div>
            <h4 className="font-semibold mb-4">Contact</h4>
            <ul className="space-y-2 text-sm">
              <li>📧 contact@carrepremium.com</li>
              <li>📞 +225 XX XX XX XX XX</li>
              <li>📍 Abidjan, Côte d'Ivoire</li>
            </ul>
          </div>
        </div>
        
        <div className="border-t border-white/20 mt-8 pt-8 text-center text-sm">
          <p>&copy; 2024 Carré Premium. Tous droits réservés.</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
```

### 2. **Home Page** (Jour 2)

#### Home.jsx
```jsx
import React from 'react';
import HeroSection from '../components/home/HeroSection';
import SearchBar from '../components/home/SearchBar';
import Categories from '../components/home/Categories';
import FeaturedFlights from '../components/home/FeaturedFlights';
import UpcomingEvents from '../components/home/UpcomingEvents';
import PopularPackages from '../components/home/PopularPackages';
import Testimonials from '../components/home/Testimonials';

const Home = () => {
  return (
    <div className="animate-fade-in">
      <HeroSection />
      <SearchBar />
      <Categories />
      <FeaturedFlights />
      <UpcomingEvents />
      <PopularPackages />
      <Testimonials />
    </div>
  );
};

export default Home;
```

---

## 🔌 INTÉGRATION API

### api.js
```javascript
import axios from 'axios';

const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://127.0.0.1:8000/api';

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Request interceptor
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Response interceptor
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;
```

### flightService.js
```javascript
import api from './api';

export const flightService = {
  search: (params) => api.get('/flights/search', { params }),
  getById: (id) => api.get(`/flights/${id}`),
  getAirlines: () => api.get('/airlines'),
  getAirports: () => api.get('/airports'),
};
```

---

## 🎯 ORDRE DE DÉVELOPPEMENT RECOMMANDÉ

### **Semaine 1: Base & Layout**
- Jour 1: Contexts (Language, Theme, Currency, Cart)
- Jour 2: Layout (Header, Footer)
- Jour 3: Composants communs (Button, Card, Input, Modal)
- Jour 4: Services API
- Jour 5: Page d'accueil (structure)

### **Semaine 2: Pages Produits**
- Jour 1-2: Recherche de vols
- Jour 3: Liste des événements
- Jour 4: Liste des packages
- Jour 5: Pages détails

### **Semaine 3: Panier & Checkout**
- Jour 1-2: Panier
- Jour 3-4: Checkout
- Jour 5: Confirmation

### **Semaine 4: Compte & Finitions**
- Jour 1-2: Authentification
- Jour 3: Dashboard utilisateur
- Jour 4: Pages supplémentaires
- Jour 5: Tests & optimisations

---

## 🚀 COMMANDES UTILES

```bash
# Démarrer le serveur de développement
npm start

# Build pour production
npm run build

# Tests
npm test

# Linter
npm run lint
```

---

## 📝 VARIABLES D'ENVIRONNEMENT

Créer `.env` :
```
REACT_APP_API_URL=http://127.0.0.1:8000/api
REACT_APP_STRIPE_KEY=pk_test_...
REACT_APP_GOOGLE_MAPS_KEY=...
```

---

## ✅ CHECKLIST DÉVELOPPEMENT

### Configuration
- [x] Projet React créé
- [x] TailwindCSS configuré
- [x] PostCSS configuré
- [x] Styles de base créés
- [x] App.js avec routing
- [ ] Dépendances installées
- [ ] Variables d'environnement

### Contexts
- [ ] LanguageContext
- [ ] ThemeContext
- [ ] CurrencyContext
- [ ] CartContext
- [ ] AuthContext

### Layout
- [ ] Header
- [ ] Footer
- [ ] Navbar
- [ ] MobileMenu

### Composants Communs
- [ ] Button
- [ ] Card
- [ ] Input
- [ ] Modal
- [ ] Loader
- [ ] Badge
- [ ] Alert

### Pages
- [ ] Home
- [ ] Flights
- [ ] FlightDetails
- [ ] Events
- [ ] EventDetails
- [ ] Packages
- [ ] PackageDetails
- [ ] Cart
- [ ] Checkout
- [ ] Confirmation
- [ ] Login/Register
- [ ] Dashboard
- [ ] MyBookings
- [ ] Profile

### Services
- [ ] API configuration
- [ ] flightService
- [ ] eventService
- [ ] packageService
- [ ] bookingService
- [ ] authService

### Fonctionnalités
- [ ] Multilingue
- [ ] Multi-devises
- [ ] Thème clair/sombre
- [ ] Recherche
- [ ] Filtres
- [ ] Panier
- [ ] Paiement
- [ ] Authentification

---

## 🎉 RÉSULTAT ATTENDU

Un site web moderne avec:
- ✅ Design professionnel violet/doré
- ✅ Responsive (mobile, tablet, desktop)
- ✅ Animations fluides
- ✅ Performance optimisée
- ✅ SEO friendly
- ✅ Accessible
- ✅ Multilingue
- ✅ Multi-devises

---

## 📞 PROCHAINES ÉTAPES IMMÉDIATES

1. **Installer les dépendances**
```bash
cd carre-premium-frontend
npm install react-router-dom axios react-hook-form framer-motion react-icons
```

2. **Créer les contexts**
3. **Créer Header et Footer**
4. **Créer la page d'accueil**
5. **Intégrer l'API backend**

---

**Frontend React prêt à être développé !** 🚀
