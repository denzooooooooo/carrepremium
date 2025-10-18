#!/bin/bash

# Script pour créer toutes les pages manquantes

cd "carre-premium-frontend/src/pages"

# EventDetails.jsx
cat > EventDetails.jsx << 'EOF'
import React from 'react';
import { useParams, Link } from 'react-router-dom';

const EventDetails = () => {
  const { id } = useParams();
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom">
        <h1 className="text-4xl font-bold text-primary mb-4">Détails de l'Événement #{id}</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8">Cette page sera bientôt disponible</p>
        <Link to="/events" className="btn btn-primary">← Retour aux événements</Link>
      </div>
    </div>
  );
};

export default EventDetails;
EOF

# Packages.jsx
cat > Packages.jsx << 'EOF'
import React from 'react';
import { Link } from 'react-router-dom';

const Packages = () => {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom">
        <h1 className="text-4xl font-bold text-primary mb-4">Packages Touristiques</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8">Cette page sera bientôt disponible</p>
        <Link to="/" className="btn btn-primary">← Retour à l'accueil</Link>
      </div>
    </div>
  );
};

export default Packages;
EOF

# PackageDetails.jsx
cat > PackageDetails.jsx << 'EOF'
import React from 'react';
import { useParams, Link } from 'react-router-dom';

const PackageDetails = () => {
  const { id } = useParams();
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom">
        <h1 className="text-4xl font-bold text-primary mb-4">Détails du Package #{id}</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8">Cette page sera bientôt disponible</p>
        <Link to="/packages" className="btn btn-primary">← Retour aux packages</Link>
      </div>
    </div>
  );
};

export default PackageDetails;
EOF

# Checkout.jsx
cat > Checkout.jsx << 'EOF'
import React from 'react';
import { Link } from 'react-router-dom';

const Checkout = () => {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom">
        <h1 className="text-4xl font-bold text-primary mb-4">Paiement</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8">Cette page sera bientôt disponible</p>
        <Link to="/cart" className="btn btn-primary">← Retour au panier</Link>
      </div>
    </div>
  );
};

export default Checkout;
EOF

# Confirmation.jsx
cat > Confirmation.jsx << 'EOF'
import React from 'react';
import { useParams, Link } from 'react-router-dom';

const Confirmation = () => {
  const { bookingId } = useParams();
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom text-center">
        <div className="text-6xl mb-4">✅</div>
        <h1 className="text-4xl font-bold text-primary mb-4">Réservation Confirmée!</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8">Numéro de réservation: #{bookingId}</p>
        <Link to="/" className="btn btn-primary">← Retour à l'accueil</Link>
      </div>
    </div>
  );
};

export default Confirmation;
EOF

# About.jsx
cat > About.jsx << 'EOF'
import React from 'react';

const About = () => {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom">
        <h1 className="text-4xl font-bold text-primary mb-4">À Propos de Carré Premium</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8">Cette page sera bientôt disponible</p>
      </div>
    </div>
  );
};

export default About;
EOF

# Contact.jsx
cat > Contact.jsx << 'EOF'
import React from 'react';

const Contact = () => {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom">
        <h1 className="text-4xl font-bold text-primary mb-4">Contactez-nous</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8">Cette page sera bientôt disponible</p>
      </div>
    </div>
  );
};

export default Contact;
EOF

# FAQ.jsx
cat > FAQ.jsx << 'EOF'
import React from 'react';

const FAQ = () => {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom">
        <h1 className="text-4xl font-bold text-primary mb-4">FAQ</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8">Cette page sera bientôt disponible</p>
      </div>
    </div>
  );
};

export default FAQ;
EOF

# Terms.jsx
cat > Terms.jsx << 'EOF'
import React from 'react';

const Terms = () => {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom">
        <h1 className="text-4xl font-bold text-primary mb-4">Conditions d'Utilisation</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8">Cette page sera bientôt disponible</p>
      </div>
    </div>
  );
};

export default Terms;
EOF

# Privacy.jsx
cat > Privacy.jsx << 'EOF'
import React from 'react';

const Privacy = () => {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom">
        <h1 className="text-4xl font-bold text-primary mb-4">Politique de Confidentialité</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8">Cette page sera bientôt disponible</p>
      </div>
    </div>
  );
};

export default Privacy;
EOF

# Créer les dossiers auth et account
mkdir -p auth account

# auth/Login.jsx
cat > auth/Login.jsx << 'EOF'
import React from 'react';
import { Link } from 'react-router-dom';

const Login = () => {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom max-w-md mx-auto">
        <h1 className="text-4xl font-bold text-primary mb-4 text-center">Connexion</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8 text-center">Cette page sera bientôt disponible</p>
        <Link to="/" className="btn btn-primary w-full">← Retour à l'accueil</Link>
      </div>
    </div>
  );
};

export default Login;
EOF

# auth/Register.jsx
cat > auth/Register.jsx << 'EOF'
import React from 'react';
import { Link } from 'react-router-dom';

const Register = () => {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom max-w-md mx-auto">
        <h1 className="text-4xl font-bold text-primary mb-4 text-center">Inscription</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8 text-center">Cette page sera bientôt disponible</p>
        <Link to="/" className="btn btn-primary w-full">← Retour à l'accueil</Link>
      </div>
    </div>
  );
};

export default Register;
EOF

# account/Dashboard.jsx
cat > account/Dashboard.jsx << 'EOF'
import React from 'react';

const Dashboard = () => {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom">
        <h1 className="text-4xl font-bold text-primary mb-4">Mon Compte</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8">Cette page sera bientôt disponible</p>
      </div>
    </div>
  );
};

export default Dashboard;
EOF

# account/MyBookings.jsx
cat > account/MyBookings.jsx << 'EOF'
import React from 'react';

const MyBookings = () => {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom">
        <h1 className="text-4xl font-bold text-primary mb-4">Mes Réservations</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8">Cette page sera bientôt disponible</p>
      </div>
    </div>
  );
};

export default MyBookings;
EOF

# account/MyFavorites.jsx
cat > account/MyFavorites.jsx << 'EOF'
import React from 'react';

const MyFavorites = () => {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom">
        <h1 className="text-4xl font-bold text-primary mb-4">Mes Favoris</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8">Cette page sera bientôt disponible</p>
      </div>
    </div>
  );
};

export default MyFavorites;
EOF

# account/Profile.jsx
cat > account/Profile.jsx << 'EOF'
import React from 'react';

const Profile = () => {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-20">
      <div className="container-custom">
        <h1 className="text-4xl font-bold text-primary mb-4">Mon Profil</h1>
        <p className="text-gray-600 dark:text-gray-400 mb-8">Cette page sera bientôt disponible</p>
      </div>
    </div>
  );
};

export default Profile;
EOF

echo "✅ Toutes les pages ont été créées avec succès!"
