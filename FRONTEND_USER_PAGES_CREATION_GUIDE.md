# 📱 GUIDE DE CRÉATION DES PAGES UTILISATEUR FRONTEND

## ✅ PAGES CRÉÉES

### 1. Login.jsx ✅ (CRÉÉ)
**Emplacement:** `carre-premium-frontend/src/pages/Login.jsx`

**Fonctionnalités:**
- Formulaire de connexion avec email/mot de passe
- Affichage/masquage du mot de passe
- Gestion des erreurs
- Lien vers inscription
- Lien mot de passe oublié
- Boutons de connexion sociale (Google, Facebook)
- Design moderne avec TailwindCSS
- Responsive
- Support multilingue
- Thème clair/sombre

---

## 📋 PAGES À CRÉER (4 restantes)

### 2. Register.jsx (À CRÉER)
**Emplacement:** `carre-premium-frontend/src/pages/Register.jsx`

**Champs du formulaire:**
- Prénom (first_name)
- Nom (last_name)
- Email
- Téléphone (optionnel)
- Mot de passe
- Confirmation mot de passe
- Date de naissance (optionnel)
- Genre (optionnel)
- Pays (par défaut: Côte d'Ivoire)
- Langue préférée (FR/EN)
- Devise préférée (XOF/EUR/USD)
- Case à cocher CGU

**Fonctionnalités:**
- Validation en temps réel
- Force du mot de passe
- Gestion des erreurs
- Redirection après inscription
- Lien vers connexion

---

### 3. Profile.jsx (À CRÉER)
**Emplacement:** `carre-premium-frontend/src/pages/Profile.jsx`

**Sections:**

**A. Informations Personnelles**
- Avatar avec upload
- Prénom, Nom
- Email (non modifiable)
- Téléphone
- Date de naissance
- Genre
- Nationalité
- Numéro de passeport

**B. Adresse**
- Adresse complète
- Ville
- Pays
- Code postal

**C. Préférences**
- Langue (FR/EN)
- Devise (XOF/EUR/USD)

**D. Sécurité**
- Changement de mot de passe
- Vérification email
- Suppression de compte

**E. Statistiques**
- Total réservations
- Montant dépensé
- Points de fidélité
- Graphique des réservations

---

### 4. MyBookings.jsx (À CRÉER)
**Emplacement:** `carre-premium-frontend/src/pages/MyBookings.jsx`

**Fonctionnalités:**
- Liste paginée des réservations
- Filtres:
  - Par type (vol, événement, package)
  - Par statut (confirmé, annulé, complété)
  - Par date
  - Recherche par numéro
- Tri (date, montant, statut)
- Cartes de réservation avec:
  - Numéro de réservation
  - Type
  - Date
  - Montant
  - Statut
  - Boutons d'action:
    - Voir détails
    - Télécharger reçu
    - Télécharger billet
    - Télécharger confirmation
    - Annuler (si possible)

---

### 5. BookingDetails.jsx (À CRÉER)
**Emplacement:** `carre-premium-frontend/src/pages/BookingDetails.jsx`

**Sections:**

**A. En-tête**
- Numéro de réservation
- Statut avec badge coloré
- Date de réservation

**B. Détails selon le type**

**Pour les vols:**
- PNR
- Numéro e-ticket
- Compagnie aérienne
- Détails du vol (départ, arrivée, durée)
- Informations passagers
- Classe de voyage

**Pour les événements:**
- Nom de l'événement
- Date et heure
- Lieu
- Zone/Siège
- Nombre de billets

**Pour les packages:**
- Nom du package
- Destination
- Durée
- Date de départ
- Nombre de participants
- Services inclus

**C. Informations de paiement**
- Montant total
- Taxes
- Réductions
- Montant final
- Devise
- Méthode de paiement
- Statut du paiement

**D. Actions**
- Télécharger reçu PDF
- Télécharger billet PDF
- Télécharger confirmation PDF
- Annuler la réservation
- Contacter le support

---

## 🔧 INTÉGRATIONS NÉCESSAIRES

### 1. Mise à jour de App.js

```javascript
import { AuthProvider } from './contexts/AuthContext';
import Login from './pages/Login';
import Register from './pages/Register';
import Profile from './pages/Profile';
import MyBookings from './pages/MyBookings';
import BookingDetails from './pages/BookingDetails';
import ProtectedRoute from './components/ProtectedRoute';

function App() {
  return (
    <AuthProvider>
      <Router>
        <Routes>
          {/* Routes publiques */}
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          
          {/* Routes protégées */}
          <Route path="/profile" element={
            <ProtectedRoute>
              <Profile />
            </ProtectedRoute>
          } />
          <Route path="/my-bookings" element={
            <ProtectedRoute>
              <MyBookings />
            </ProtectedRoute>
          } />
          <Route path="/my-bookings/:id" element={
            <ProtectedRoute>
              <BookingDetails />
            </ProtectedRoute>
          } />
          
          {/* Autres routes... */}
        </Routes>
      </Router>
    </AuthProvider>
  );
}
```

---

### 2. Créer ProtectedRoute.jsx

**Emplacement:** `carre-premium-frontend/src/components/ProtectedRoute.jsx`

```javascript
import { Navigate, useLocation } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

const ProtectedRoute = ({ children }) => {
  const { isAuthenticated, loading } = useAuth();
  const location = useLocation();

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
      </div>
    );
  }

  if (!isAuthenticated) {
    return <Navigate to="/login" state={{ from: location }} replace />;
  }

  return children;
};

export default ProtectedRoute;
```

---

### 3. Mise à jour de HeaderModern.jsx

**Ajouter le menu utilisateur:**

```javascript
import { useAuth } from '../contexts/AuthContext';

const HeaderModern = () => {
  const { user, isAuthenticated, logout } = useAuth();
  
  return (
    <header>
      {/* ... */}
      
      {isAuthenticated ? (
        <div className="relative">
          <button className="flex items-center space-x-2">
            <img 
              src={user.avatar || '/default-avatar.png'} 
              alt={user.first_name}
              className="w-10 h-10 rounded-full"
            />
            <span>{user.first_name}</span>
          </button>
          
          {/* Dropdown Menu */}
          <div className="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg">
            <Link to="/profile">Mon Profil</Link>
            <Link to="/my-bookings">Mes Réservations</Link>
            <button onClick={logout}>Déconnexion</button>
          </div>
        </div>
      ) : (
        <div className="flex space-x-4">
          <Link to="/login">Connexion</Link>
          <Link to="/register">Inscription</Link>
        </div>
      )}
    </header>
  );
};
```

---

### 4. Service API pour les réservations

**Emplacement:** `carre-premium-frontend/src/services/bookingService.js`

```javascript
import axios from 'axios';

const API_URL = 'http://localhost:8000/api/v1';

export const bookingService = {
  // Liste des réservations
  getMyBookings: async (page = 1) => {
    const response = await axios.get(`${API_URL}/user/bookings?page=${page}`);
    return response.data;
  },

  // Détails d'une réservation
  getBookingDetails: async (id) => {
    const response = await axios.get(`${API_URL}/user/bookings/${id}`);
    return response.data;
  },

  // Télécharger le reçu
  downloadReceipt: async (id) => {
    const response = await axios.get(`${API_URL}/user/bookings/${id}/receipt`, {
      responseType: 'blob'
    });
    return response.data;
  },

  // Télécharger le billet
  downloadTicket: async (id) => {
    const response = await axios.get(`${API_URL}/user/bookings/${id}/ticket`, {
      responseType: 'blob'
    });
    return response.data;
  },

  // Télécharger la confirmation
  downloadConfirmation: async (id) => {
    const response = await axios.get(`${API_URL}/user/bookings/${id}/confirmation`, {
      responseType: 'blob'
    });
    return response.data;
  },

  // Annuler une réservation
  cancelBooking: async (id, reason) => {
    const response = await axios.post(`${API_URL}/user/bookings/${id}/cancel`, {
      reason
    });
    return response.data;
  },

  // Statistiques
  getStatistics: async () => {
    const response = await axios.get(`${API_URL}/user/statistics`);
    return response.data;
  }
};
```

---

### 5. Notifications Toast

**Installer react-toastify:**
```bash
npm install react-toastify
```

**Dans App.js:**
```javascript
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

function App() {
  return (
    <>
      <ToastContainer
        position="top-right"
        autoClose={3000}
        hideProgressBar={false}
        newestOnTop
        closeOnClick
        rtl={false}
        pauseOnFocusLoss
        draggable
        pauseOnHover
      />
      {/* Routes... */}
    </>
  );
}
```

**Utilisation:**
```javascript
import { toast } from 'react-toastify';

// Succès
toast.success('Connexion réussie !');

// Erreur
toast.error('Email ou mot de passe incorrect');

// Info
toast.info('Vérifiez votre email');

// Warning
toast.warning('Session expirée');
```

---

## 📦 DÉPENDANCES À INSTALLER

```bash
cd carre-premium-frontend

# Notifications
npm install react-toastify

# Gestion des formulaires (optionnel)
npm install react-hook-form

# Validation (optionnel)
npm install yup

# Graphiques pour le profil (optionnel)
npm install recharts
```

---

## 🎨 DESIGN GUIDELINES

### Couleurs
- **Primaire:** Violet (#9333EA)
- **Secondaire:** Doré (#D4AF37)
- **Succès:** Vert (#10B981)
- **Erreur:** Rouge (#EF4444)
- **Warning:** Orange (#F59E0B)
- **Info:** Bleu (#3B82F6)

### Typographie
- **Titres:** Montserrat Bold
- **Corps:** Poppins Regular
- **Taille de base:** 16px

### Composants
- **Boutons:** Arrondis (rounded-xl), ombre portée
- **Cartes:** Arrondies (rounded-3xl), ombre 2xl
- **Inputs:** Bordure 2px, focus violet
- **Badges:** Arrondis (rounded-full), couleurs selon statut

---

## ✅ CHECKLIST DE COMPLÉTION

- [x] Login.jsx créé
- [ ] Register.jsx à créer
- [ ] Profile.jsx à créer
- [ ] MyBookings.jsx à créer
- [ ] BookingDetails.jsx à créer
- [ ] ProtectedRoute.jsx à créer
- [ ] bookingService.js à créer
- [ ] Mise à jour App.js
- [ ] Mise à jour HeaderModern.jsx
- [ ] Installation react-toastify
- [ ] Tests des pages

---

## 🧪 TESTS À EFFECTUER

1. **Inscription:**
   - Créer un compte
   - Vérifier la validation
   - Vérifier la redirection

2. **Connexion:**
   - Se connecter
   - Vérifier le token
   - Vérifier la redirection

3. **Profil:**
   - Afficher les informations
   - Modifier le profil
   - Changer le mot de passe
   - Upload avatar

4. **Réservations:**
   - Afficher la liste
   - Filtrer et trier
   - Voir les détails
   - Télécharger les PDF
   - Annuler une réservation

5. **Navigation:**
   - Routes protégées
   - Redirection si non connecté
   - Menu utilisateur
   - Déconnexion

---

**Développé par:** BLACKBOXAI  
**Client:** Carré Premium  
**Date:** 10 Janvier 2025  
**Statut:** Login.jsx ✅ | 4 pages restantes ⏳
