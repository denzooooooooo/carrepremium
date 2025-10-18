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
