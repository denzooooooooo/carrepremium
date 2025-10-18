import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useCurrency } from '../contexts/CurrencyContext';
import { useCart } from '../contexts/CartContext';
import { packageService } from '../services/api';

const PackagesModern = () => {
  const { formatPrice } = useCurrency();
  const { addToCart } = useCart();

  const [packages, setPackages] = useState([]);
  const [loading, setLoading] = useState(true);
  const [filters, setFilters] = useState({
    search: '',
    type: 'all',
    destination: '',
    minPrice: '',
    maxPrice: '',
    sortBy: 'price_asc'
  });

  const packageTypes = [
    { value: 'all', label: 'Tous les packages', icon: '🎁' },
    { value: 'helicopter', label: 'Hélicoptère', icon: '🚁' },
    { value: 'private_jet', label: 'Jet Privé', icon: '✈️' },
    { value: 'safari', label: 'Safari', icon: '🦁' },
    { value: 'cruise', label: 'Croisière', icon: '🚢' },
    { value: 'luxury', label: 'Luxe', icon: '💎' },
    { value: 'adventure', label: 'Aventure', icon: '🏔️' }
  ];

  useEffect(() => {
    const fetchPackages = async () => {
      try {
        setLoading(true);
        console.log('🔄 Chargement des packages depuis l\'API...');
        const response = await packageService.getAllPackages(1, 20);
        console.log('✅ Réponse API packages:', response);
        
        if (response.data && response.data.data) {
          console.log('✅ Packages chargés:', response.data.data.length);
          setPackages(response.data.data);
        } else if (response.data) {
          console.log('✅ Packages chargés (format alternatif):', response.data.length);
          setPackages(response.data);
        } else {
          console.warn('⚠️ Aucune donnée de package reçue');
          setPackages([]);
        }
      } catch (error) {
        console.error('❌ Erreur lors du chargement des packages:', error);
        setPackages([]);
      } finally {
        setLoading(false);
      }
    };

    fetchPackages();
  }, []);

  const handleAddToCart = (pkg) => {
    addToCart({
      id: pkg.id,
      type: 'package',
      name: pkg.title_fr,
      destination: pkg.destination,
      duration: pkg.duration_text_fr,
      price: pkg.price,
      image: pkg.image
    });
  };

  const filteredPackages = packages.filter(pkg => {
    if (filters.search && !pkg.title_fr.toLowerCase().includes(filters.search.toLowerCase())) return false;
    if (filters.type !== 'all' && pkg.package_type !== filters.type) return false;
    if (filters.destination && !pkg.destination.toLowerCase().includes(filters.destination.toLowerCase())) return false;
    if (filters.minPrice && pkg.price < parseInt(filters.minPrice)) return false;
    if (filters.maxPrice && pkg.price > parseInt(filters.maxPrice)) return false;
    return true;
  });

  const sortedPackages = [...filteredPackages].sort((a, b) => {
    switch(filters.sortBy) {
      case 'price_asc':
        return a.price - b.price;
      case 'price_desc':
        return b.price - a.price;
      case 'duration':
        return a.duration - b.duration;
      case 'rating':
        return (b.rating || 0) - (a.rating || 0);
      default:
        return 0;
    }
  });

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      {/* Hero Section */}
      <section className="relative h-[60vh] flex items-center justify-center overflow-hidden">
        <div 
          className="absolute inset-0 bg-cover bg-center"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1540962351504-03099e0a754b?w=1920&h=1080&fit=crop')`,
          }}
        >
          <div className="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/70"></div>
        </div>

        <div className="relative z-10 container-custom px-4 text-center">
          <h1 className="text-5xl md:text-7xl font-black text-white mb-6 leading-tight">
            Packages<br />
            <span className="bg-gradient-to-r from-purple-400 to-amber-400 bg-clip-text text-transparent">VIP Exclusifs</span>
          </h1>
          <p className="text-xl text-white/90 max-w-2xl mx-auto">
            Des expériences de voyage exceptionnelles sur mesure pour vous
          </p>
        </div>
      </section>

      {/* Categories Filter */}
      <section className="bg-white dark:bg-gray-800 shadow-2xl -mt-16 relative z-20">
        <div className="container-custom py-6">
          <div className="flex flex-wrap gap-3 justify-center">
            {packageTypes.map((type) => (
              <button
                key={type.value}
                onClick={() => setFilters({...filters, type: type.value})}
                className={`px-6 py-3 rounded-full font-bold transition-all duration-300 flex items-center space-x-2 ${
                  filters.type === type.value
                    ? 'bg-gradient-to-r from-purple-600 to-amber-600 text-white shadow-lg scale-105'
                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                }`}
              >
                <span>{type.icon}</span>
                <span>{type.label}</span>
              </button>
            ))}
          </div>
        </div>
      </section>

      {/* Search & Filters */}
      <section className="py-8 bg-gray-50 dark:bg-gray-900">
        <div className="container-custom">
          <div className="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div className="md:col-span-2">
              <input
                type="text"
                placeholder="Rechercher un package..."
                value={filters.search}
                onChange={(e) => setFilters({...filters, search: e.target.value})}
                className="w-full px-6 py-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-purple-600 focus:outline-none transition-colors"
              />
            </div>
            <div>
              <input
                type="text"
                placeholder="Destination..."
                value={filters.destination}
                onChange={(e) => setFilters({...filters, destination: e.target.value})}
                className="w-full px-6 py-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-purple-600 focus:outline-none transition-colors"
              />
            </div>
            <div>
              <input
                type="number"
                placeholder="Prix min..."
                value={filters.minPrice}
                onChange={(e) => setFilters({...filters, minPrice: e.target.value})}
                className="w-full px-6 py-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-purple-600 focus:outline-none transition-colors"
              />
            </div>
            <div>
              <input
                type="number"
                placeholder="Prix max..."
                value={filters.maxPrice}
                onChange={(e) => setFilters({...filters, maxPrice: e.target.value})}
                className="w-full px-6 py-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-purple-600 focus:outline-none transition-colors"
              />
            </div>
          </div>
        </div>
      </section>

      {/* Results Section */}
      <section className="py-16">
        <div className="container-custom">
          {/* Header */}
          <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-4">
            <div>
              <h2 className="text-3xl font-black text-gray-900 dark:text-white mb-2">
                {sortedPackages.length} Packages Disponibles
              </h2>
              <p className="text-gray-600 dark:text-gray-400">
                Trouvez l'expérience parfaite pour votre voyage
              </p>
            </div>
            <select
              value={filters.sortBy}
              onChange={(e) => setFilters({...filters, sortBy: e.target.value})}
              className="px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:outline-none transition-colors"
            >
              <option value="price_asc">Prix croissant</option>
              <option value="price_desc">Prix décroissant</option>
              <option value="duration">Durée</option>
              <option value="rating">Mieux notés</option>
            </select>
          </div>

          {/* Loading State */}
          {loading ? (
            <div className="grid gap-6">
              {[1, 2, 3].map((i) => (
                <div key={i} className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl animate-pulse">
                  <div className="h-48 bg-gray-300 dark:bg-gray-700 rounded-2xl"></div>
                </div>
              ))}
            </div>
          ) : (
            <div className="grid gap-6">
              {sortedPackages.map((pkg) => (
                <div
                  key={pkg.id}
                  className="group bg-white dark:bg-gray-800 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl hover:shadow-purple-500/20 transition-all duration-500 hover:-translate-y-2"
                >
                  <div className="grid md:grid-cols-3 gap-6">
                    {/* Image */}
                    <div className="relative h-64 md:h-auto overflow-hidden">
                      <img
                        src={pkg.image}
                        alt={pkg.title_fr}
                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                      />
                      <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                      
                      {/* Badges */}
                      <div className="absolute top-4 left-4 px-4 py-2 bg-gradient-to-r from-purple-600 to-amber-600 text-white rounded-full text-xs font-bold shadow-xl">
                        VIP
                      </div>
                      
                      {pkg.is_featured && (
                        <div className="absolute top-4 right-4 px-3 py-1 bg-red-500 text-white text-xs font-black rounded-full shadow-xl animate-pulse">
                          ⭐ POPULAIRE
                        </div>
                      )}

                      {pkg.rating && (
                        <div className="absolute bottom-4 left-4 px-3 py-2 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md rounded-full flex items-center space-x-1">
                          <svg className="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                          </svg>
                          <span className="text-sm font-bold text-gray-900 dark:text-white">{pkg.rating}</span>
                        </div>
                      )}
                    </div>

                    {/* Package Details */}
                    <div className="p-6 md:col-span-2">
                      <div className="flex items-start justify-between mb-4">
                        <div className="flex-1">
                          <h3 className="text-3xl font-black text-gray-900 dark:text-white mb-2">
                            {pkg.title_fr}
                          </h3>
                          <p className="text-gray-600 dark:text-gray-400 line-clamp-2">
                            {pkg.description_fr}
                          </p>
                        </div>
                      </div>

                      <div className="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                        <div className="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                          <svg className="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clipRule="evenodd" />
                          </svg>
                          <span className="font-semibold">{pkg.duration_text_fr}</span>
                        </div>
                        <div className="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                          <svg className="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clipRule="evenodd" />
                          </svg>
                          <span className="font-semibold">{pkg.destination}</span>
                        </div>
                        <div className="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                          <svg className="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                          </svg>
                          <span className="font-semibold">Max {pkg.max_participants} pers.</span>
                        </div>
                      </div>

                      {/* Features */}
                      <div className="grid grid-cols-2 gap-2 mb-6">
                        {pkg.included_services_fr?.slice(0, 4).map((feature, i) => (
                          <div key={i} className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <svg className="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                              <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                            </svg>
                            {feature}
                          </div>
                        ))}
                      </div>

                      <div className="flex items-center justify-between">
                        <div>
                          <p className="text-sm text-gray-600 dark:text-gray-400 mb-1">À partir de</p>
                          <p className="text-4xl font-black bg-gradient-to-r from-purple-600 to-amber-600 bg-clip-text text-transparent">
                            {formatPrice(pkg.price)}
                          </p>
                        </div>

                        <div className="flex gap-3">
                          <Link
                            to={`/packages/${pkg.id}`}
                            className="px-6 py-3 border-2 border-purple-600 text-purple-600 dark:text-purple-400 font-bold rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-300"
                          >
                            Détails
                          </Link>
                          <button
                            onClick={() => handleAddToCart(pkg)}
                            className="px-6 py-3 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-2xl hover:shadow-purple-500/50 transition-all duration-300 flex items-center space-x-2"
                          >
                            <span>Réserver</span>
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}

          {/* No Results */}
          {!loading && sortedPackages.length === 0 && (
            <div className="text-center py-16">
              <svg className="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                Aucun package trouvé
              </h3>
              <p className="text-gray-600 dark:text-gray-400">
                Essayez de modifier vos critères de recherche
              </p>
            </div>
          )}
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-gradient-to-br from-purple-900 to-amber-900">
        <div className="container-custom text-center">
          <h2 className="text-4xl md:text-5xl font-black text-white mb-6">
            Besoin d'un Package Sur Mesure ?
          </h2>
          <p className="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
            Notre équipe d'experts peut créer un package personnalisé selon vos besoins et votre budget
          </p>
          <Link 
            to="/contact"
            className="inline-flex items-center px-10 py-5 bg-white text-gray-900 font-bold rounded-full hover:scale-105 transition-transform shadow-xl space-x-2"
          >
            <span>DEMANDER UN DEVIS</span>
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </Link>
        </div>
      </section>
    </div>
  );
};

export default PackagesModern;
