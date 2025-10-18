import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { useLanguage } from '../contexts/LanguageContext';
import { useCurrency } from '../contexts/CurrencyContext';
import { useCart } from '../contexts/CartContext';

const Flights = () => {
  const { t } = useLanguage();
  const { formatPrice } = useCurrency();
  const { addToCart } = useCart();

  const [filters, setFilters] = useState({
    from: '',
    to: '',
    date: '',
    class: 'economy',
    passengers: 1
  });

  // Données de démonstration
  const flights = [
    {
      id: 1,
      airline: 'Air France',
      logo: '✈️',
      from: 'Abidjan (ABJ)',
      to: 'Paris (CDG)',
      departure: '10:30',
      arrival: '18:45',
      duration: '6h 15min',
      date: '2024-12-15',
      stops: 'Direct',
      economyPrice: 450000,
      businessPrice: 850000,
      firstClassPrice: 1200000,
      availableSeats: 45
    },
    {
      id: 2,
      airline: 'Emirates',
      logo: '✈️',
      from: 'Abidjan (ABJ)',
      to: 'Dubai (DXB)',
      departure: '23:00',
      arrival: '09:30+1',
      duration: '8h 30min',
      date: '2024-12-15',
      stops: 'Direct',
      economyPrice: 550000,
      businessPrice: 950000,
      firstClassPrice: 1400000,
      availableSeats: 32
    },
    {
      id: 3,
      airline: 'Delta',
      logo: '✈️',
      from: 'Abidjan (ABJ)',
      to: 'New York (JFK)',
      departure: '14:00',
      arrival: '19:30',
      duration: '11h 30min',
      date: '2024-12-16',
      stops: '1 escale',
      economyPrice: 650000,
      businessPrice: 1100000,
      firstClassPrice: 1600000,
      availableSeats: 28
    },
    {
      id: 4,
      airline: 'British Airways',
      logo: '✈️',
      from: 'Abidjan (ABJ)',
      to: 'Londres (LHR)',
      departure: '08:15',
      arrival: '15:00',
      duration: '6h 45min',
      date: '2024-12-17',
      stops: 'Direct',
      economyPrice: 480000,
      businessPrice: 880000,
      firstClassPrice: 1250000,
      availableSeats: 38
    },
    {
      id: 5,
      airline: 'Turkish Airlines',
      logo: '✈️',
      from: 'Abidjan (ABJ)',
      to: 'Istanbul (IST)',
      departure: '20:00',
      arrival: '06:30+1',
      duration: '8h 30min',
      date: '2024-12-18',
      stops: 'Direct',
      economyPrice: 520000,
      businessPrice: 920000,
      firstClassPrice: 1350000,
      availableSeats: 41
    }
  ];

  const getPrice = (flight) => {
    switch(filters.class) {
      case 'business': return flight.businessPrice;
      case 'first': return flight.firstClassPrice;
      default: return flight.economyPrice;
    }
  };

  const handleAddToCart = (flight) => {
    addToCart({
      id: flight.id,
      type: 'flight',
      name: `${flight.from} → ${flight.to}`,
      airline: flight.airline,
      date: flight.date,
      departure: flight.departure,
      class: filters.class,
      passengers: filters.passengers,
      price: getPrice(flight)
    });
  };

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      {/* Hero Section */}
      <section className="bg-gradient-to-r from-primary to-purple-700 text-white py-16">
        <div className="container-custom">
          <h1 className="text-4xl md:text-5xl font-montserrat font-bold mb-4">
            Réservez votre <span className="text-gold">Vol</span>
          </h1>
          <p className="text-xl opacity-90">
            Trouvez les meilleurs vols aux meilleurs prix
          </p>
        </div>
      </section>

      {/* Search Filters */}
      <section className="bg-white dark:bg-gray-800 shadow-lg -mt-8 relative z-10">
        <div className="container-custom py-6">
          <div className="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Départ
              </label>
              <input
                type="text"
                placeholder="Ville de départ"
                value={filters.from}
                onChange={(e) => setFilters({...filters, from: e.target.value})}
                className="input"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Arrivée
              </label>
              <input
                type="text"
                placeholder="Ville d'arrivée"
                value={filters.to}
                onChange={(e) => setFilters({...filters, to: e.target.value})}
                className="input"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Date
              </label>
              <input
                type="date"
                value={filters.date}
                onChange={(e) => setFilters({...filters, date: e.target.value})}
                className="input"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Classe
              </label>
              <select
                value={filters.class}
                onChange={(e) => setFilters({...filters, class: e.target.value})}
                className="input"
              >
                <option value="economy">Économique</option>
                <option value="business">Affaires</option>
                <option value="first">Première</option>
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Passagers
              </label>
              <input
                type="number"
                min="1"
                max="9"
                value={filters.passengers}
                onChange={(e) => setFilters({...filters, passengers: parseInt(e.target.value)})}
                className="input"
              />
            </div>
          </div>
        </div>
      </section>

      {/* Results */}
      <section className="section">
        <div className="container-custom">
          <div className="flex justify-between items-center mb-8">
            <h2 className="text-2xl font-montserrat font-bold text-gray-800 dark:text-white">
              {flights.length} vols disponibles
            </h2>
            <select className="input w-auto">
              <option>Prix croissant</option>
              <option>Prix décroissant</option>
              <option>Durée</option>
              <option>Départ</option>
            </select>
          </div>

          <div className="space-y-6">
            {flights.map((flight) => (
              <div key={flight.id} className="card hover-lift">
                <div className="flex flex-col md:flex-row gap-6 p-6">
                  {/* Airline Info */}
                  <div className="flex items-center gap-4 md:w-1/4">
                    <div className="text-4xl">{flight.logo}</div>
                    <div>
                      <h3 className="font-bold text-gray-800 dark:text-white">{flight.airline}</h3>
                      <p className="text-sm text-gray-600 dark:text-gray-400">{flight.stops}</p>
                    </div>
                  </div>

                  {/* Flight Details */}
                  <div className="flex-1 md:w-2/4">
                    <div className="flex items-center justify-between">
                      <div className="text-center">
                        <p className="text-2xl font-bold text-gray-800 dark:text-white">{flight.departure}</p>
                        <p className="text-sm text-gray-600 dark:text-gray-400">{flight.from}</p>
                      </div>
                      <div className="flex-1 px-4">
                        <div className="border-t-2 border-gray-300 dark:border-gray-600 relative">
                          <span className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 px-2 text-sm text-gray-600 dark:text-gray-400">
                            {flight.duration}
                          </span>
                        </div>
                      </div>
                      <div className="text-center">
                        <p className="text-2xl font-bold text-gray-800 dark:text-white">{flight.arrival}</p>
                        <p className="text-sm text-gray-600 dark:text-gray-400">{flight.to}</p>
                      </div>
                    </div>
                    <div className="mt-4 flex gap-4 text-sm text-gray-600 dark:text-gray-400">
                      <span>📅 {flight.date}</span>
                      <span>💺 {flight.availableSeats} places disponibles</span>
                    </div>
                  </div>

                  {/* Price & Action */}
                  <div className="md:w-1/4 flex flex-col justify-between items-end">
                    <div className="text-right">
                      <p className="text-sm text-gray-600 dark:text-gray-400">À partir de</p>
                      <p className="text-3xl font-bold text-primary">{formatPrice(getPrice(flight))}</p>
                      <p className="text-xs text-gray-500 dark:text-gray-400">par personne</p>
                    </div>
                    <div className="flex gap-2 mt-4">
                      <Link to={`/flights/${flight.id}`} className="btn btn-outline">
                        Détails
                      </Link>
                      <button
                        onClick={() => handleAddToCart(flight)}
                        className="btn btn-primary"
                      >
                        Réserver
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
};

export default Flights;
