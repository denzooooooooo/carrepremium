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
