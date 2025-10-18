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
