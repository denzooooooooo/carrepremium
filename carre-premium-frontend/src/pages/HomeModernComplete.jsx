import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { useCurrency } from '../contexts/CurrencyContext';

const HomeModernComplete = () => {
  const { formatPrice } = useCurrency();
  const [searchData, setSearchData] = useState({
    from: '',
    to: '',
    date: '',
    passengers: 1
  });

  const handleSearch = (e) => {
    e.preventDefault();
    console.log('Recherche:', searchData);
    // Rediriger vers la page de résultats
    window.location.href = '/flights';
  };

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      {/* Hero Section avec Image d'Avion */}
      <section className="relative h-screen flex items-center justify-center overflow-hidden">
        <div 
          className="absolute inset-0 bg-cover bg-center"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1920&h=1080&fit=crop')`,
          }}
        >
          <div className="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/60"></div>
        </div>

        <div className="relative z-10 container-custom px-4">
          <div className="max-w-4xl mx-auto text-center">
            <h1 className="text-5xl md:text-7xl font-black text-white mb-6 leading-tight">
              Voyagez avec<br />
              Carré Premium<br />
              <span className="text-yellow-400">Votre Partenaire de Confiance</span>
            </h1>
            
            <p className="text-xl text-white/90 mb-8">
              Billets d'avion • Événements Sportifs • Concerts • Packages VIP
            </p>

