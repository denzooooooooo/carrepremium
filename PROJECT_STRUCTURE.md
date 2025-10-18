# 🏢 CARRÉ PREMIUM - STRUCTURE DU PROJET

## 📁 Architecture du Projet

```
carre-premium/
├── carre-premium-backend/          # Backend Laravel
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Admin/          # Contrôleurs Admin
│   │   │   │   │   ├── DashboardController.php
│   │   │   │   │   ├── AdminAuthController.php
│   │   │   │   │   ├── UserController.php
│   │   │   │   │   ├── FlightController.php
│   │   │   │   │   ├── EventController.php
│   │   │   │   │   ├── PackageController.php
│   │   │   │   │   ├── BookingController.php
│   │   │   │   │   ├── PaymentController.php
│   │   │   │   │   ├── CategoryController.php
│   │   │   │   │   ├── CarouselController.php
│   │   │   │   │   ├── SettingsController.php
│   │   │   │   │   ├── ReviewController.php
│   │   │   │   │   ├── PromoCodeController.php
│   │   │   │   │   └── ReportController.php
│   │   │   │   ├── API/            # API Controllers
│   │   │   │   │   ├── AuthController.php
│   │   │   │   │   ├── FlightController.php
│   │   │   │   │   ├── EventController.php
│   │   │   │   │   ├── PackageController.php
│   │   │   │   │   ├── BookingController.php
│   │   │   │   │   ├── CartController.php
│   │   │   │   │   ├── FavoriteController.php
│   │   │   │   │   ├── ReviewController.php
│   │   │   │   │   ├── ChatController.php
│   │   │   │   │   ├── ChatbotController.php
│   │   │   │   │   └── RecommendationController.php
│   │   │   ├── Middleware/
│   │   │   │   ├── AdminAuth.php
│   │   │   │   ├── CheckRole.php
│   │   │   │   └── LocaleMiddleware.php
│   │   │   └── Requests/
│   │   ├── Models/
│   │   │   ├── Admin.php
│   │   │   ├── User.php
│   │   │   ├── Category.php
│   │   │   ├── Airline.php
│   │   │   ├── Airport.php
│   │   │   ├── Flight.php
│   │   │   ├── Event.php
│   │   │   ├── EventSeatZone.php
│   │   │   ├── TourPackage.php
│   │   │   ├── Booking.php
│   │   │   ├── Payment.php
│   │   │   ├── Cart.php
│   │   │   ├── Favorite.php
│   │   │   ├── Review.php
│   │   │   ├── Currency.php
│   │   │   ├── ChatMessage.php
│   │   │   ├── ChatbotConversation.php
│   │   │   ├── Notification.php
│   │   │   ├── Page.php
│   │   │   ├── Carousel.php
│   │   │   ├── Setting.php
│   │   │   ├── PromoCode.php
│   │   │   └── ActivityLog.php
│   │   ├── Services/
│   │   │   ├── FlightSearchService.php
│   │   │   ├── RecommendationService.php
│   │   │   ├── PaymentService.php
│   │   │   ├── NotificationService.php
│   │   │   ├── ChatbotService.php
│   │   │   └── CurrencyService.php
│   │   └── Traits/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── routes/
│   │   ├── api.php
│   │   ├── admin.php
│   │   └── web.php
│   └── resources/
│       └── views/
│           └── admin/              # Vues Admin (Blade)
├── carre-premium-frontend/         # Frontend React
│   ├── public/
│   ├── src/
│   │   ├── components/
│   │   │   ├── common/
│   │   │   ├── layout/
│   │   │   ├── flights/
│   │   │   ├── events/
│   │   │   ├── packages/
│   │   │   ├── cart/
│   │   │   └── chat/
│   │   ├── pages/
│   │   │   ├── Home.jsx
│   │   │   ├── Flights.jsx
│   │   │   ├── Events.jsx
│   │   │   ├── Packages.jsx
│   │   │   ├── Cart.jsx
│   │   │   └── Checkout.jsx
│   │   ├── admin/                  # Admin Dashboard React
│   │   │   ├── Dashboard.jsx
│   │   │   ├── Flights.jsx
│   │   │   ├── Events.jsx
│   │   │   ├── Bookings.jsx
│   │   │   └── Settings.jsx
│   │   ├── contexts/
│   │   ├── hooks/
│   │   ├── services/
│   │   └── utils/
│   └── package.json
└── database_schema.sql             # Schéma de base de données

```

## 🎯 Fonctionnalités Principales

### Backend (Laravel)
- ✅ API RESTful complète
- ✅ Authentification JWT (Admin & Users)
- ✅ CRUD complet pour toutes les entités
- ✅ Gestion des rôles et permissions
- ✅ Upload de fichiers (images/vidéos)
- ✅ Système de recherche avancé
- ✅ Recommandations personnalisées
- ✅ Intégration APIs externes
- ✅ Multi-devises avec conversion
- ✅ Multilingue (FR/EN)
- ✅ Système de paiement
- ✅ Chatbot IA
- ✅ Notifications en temps réel
- ✅ Logs d'activité

### Frontend (React)
- ✅ Interface moderne et responsive
- ✅ Thème clair/sombre
- ✅ Carrousels images/vidéos
- ✅ Recherche avancée avec filtres
- ✅ Panier fonctionnel
- ✅ Checkout multi-étapes
- ✅ Chat en direct
- ✅ Chatbot intégré
- ✅ WhatsApp integration
- ✅ Système de favoris
- ✅ Avis et notes
- ✅ Profil utilisateur

### Admin Dashboard
- ✅ Dashboard avec statistiques
- ✅ Gestion des vols
- ✅ Gestion des événements
- ✅ Gestion des packages
- ✅ Gestion des réservations
- ✅ Gestion des paiements
- ✅ Gestion des utilisateurs
- ✅ Gestion du contenu (CMS)
- ✅ Gestion des carrousels
- ✅ Paramètres du site
- ✅ Rapports et statistiques
- ✅ Logs d'activité

## 🎨 Charte Graphique

### Couleurs
- **Fond**: Blanc (#FFFFFF)
- **Texte Important**: Doré (#D4AF37)
- **Footer**: Violet (#9333EA)
- **Boutons**: Violet (#9333EA)
- **Accents**: Violet (#9333EA)

### Typographie
- **Titres**: Montserrat (Bold, SemiBold)
- **Corps**: Poppins (Regular, Medium)
- **Taille de base**: 16px

### Icônes
- Font Awesome 6 Pro
- Heroicons
- Lucide React

## 🔧 Technologies Utilisées

### Backend
- Laravel 12
- MySQL 8.0
- JWT Authentication
- Laravel Sanctum
- Laravel Queue
- Laravel Notifications
- Intervention Image

### Frontend
- React 18
- React Router v6
- Redux Toolkit
- Axios
- TailwindCSS
- Framer Motion
- React Query
- Socket.io Client

### Outils
- Composer
- NPM/Yarn
- Git
- Postman (API Testing)

## 📦 APIs Externes à Intégrer

1. **Vols**: Amadeus API / Skyscanner API
2. **Paiement**: Stripe, PayPal, Mobile Money
3. **Chatbot**: OpenAI GPT / Dialogflow
4. **WhatsApp**: WhatsApp Business API
5. **Email**: SendGrid / Mailgun
6. **SMS**: Twilio
7. **Cartes**: Google Maps API
8. **Devises**: Exchange Rate API

## 🚀 Prochaines Étapes

### Phase 1: Backend & Base de Données ✅ (En cours)
1. ✅ Installation Laravel
2. ✅ Création du schéma de base de données
3. ⏳ Création des migrations
4. ⏳ Création des modèles
5. ⏳ Création des contrôleurs Admin
6. ⏳ Création des API Controllers
7. ⏳ Middleware et authentification
8. ⏳ Services et logique métier
9. ⏳ Seeders avec données de test

### Phase 2: Admin Dashboard (Backend)
1. Routes admin
2. Contrôleurs admin complets
3. Vues Blade pour admin
4. Dashboard avec statistiques
5. CRUD complet pour toutes les entités

### Phase 3: Frontend React
1. Setup React + TailwindCSS
2. Composants de base
3. Pages principales
4. Intégration API
5. Système de thème
6. Multilingue

### Phase 4: Fonctionnalités Avancées
1. Chatbot IA
2. Recommandations personnalisées
3. Chat en temps réel
4. Notifications push
5. Intégration WhatsApp

### Phase 5: Tests & Déploiement
1. Tests unitaires
2. Tests d'intégration
3. Optimisation performances
4. SEO
5. Déploiement production

## 📝 Notes Importantes

- Toutes les données doivent être en français ET anglais
- Le site doit être 100% fonctionnel
- Design inspiré de Corsair, TicketPlus
- Focus sur l'UX/UI professionnelle
- Performance et sécurité prioritaires
