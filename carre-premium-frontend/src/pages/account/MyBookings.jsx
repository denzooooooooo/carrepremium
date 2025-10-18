import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import { useLanguage } from '../../contexts/LanguageContext';
import { useCurrency } from '../../contexts/CurrencyContext';
import HeaderModern from '../../components/layout/HeaderModern';
import FooterModern from '../../components/layout/FooterModern';
import axios from 'axios';

const MyBookings = () => {
  const { t } = useLanguage();
  const { user } = useAuth();
  const { formatPrice } = useCurrency();
  
  const [bookings, setBookings] = useState([]);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState('all');
  const [searchTerm, setSearchTerm] = useState('');

  useEffect(() => {
    loadBookings();
  }, []);

  const loadBookings = async () => {
    try {
      setLoading(true);
      const response = await axios.get('http://localhost:8000/api/v1/user/bookings');
      if (response.data.success) {
        // L'API retourne un objet paginé, on extrait le tableau 'data'
        const bookingsData = response.data.data.data || [];
        setBookings(bookingsData);
      }
    } catch (error) {
      console.error('Erreur chargement réservations:', error);
      setBookings([]); // Initialiser avec un tableau vide en cas d'erreur
    } finally {
      setLoading(false);
    }
  };

  const getStatusBadge = (status) => {
    const badges = {
      confirmed: { bg: 'bg-green-100 dark:bg-green-900/20', text: 'text-green-700 dark:text-green-400', label: 'Confirmée' },
      pending: { bg: 'bg-yellow-100 dark:bg-yellow-900/20', text: 'text-yellow-700 dark:text-yellow-400', label: 'En attente' },
      cancelled: { bg: 'bg-red-100 dark:bg-red-900/20', text: 'text-red-700 dark:text-red-400', label: 'Annulée' },
      completed: { bg: 'bg-blue-100 dark:bg-blue-900/20', text: 'text-blue-700 dark:text-blue-400', label: 'Terminée' }
    };
    const badge = badges[status] || badges.pending;
    return (
      <span className={`px-3 py-1 rounded-full text-xs font-semibold ${badge.bg} ${badge.text}`}>
        {badge.label}
      </span>
    );
  };

  const getTypeIcon = (type) => {
    const icons = {
      flight: 'fa-plane',
      event: 'fa-ticket-alt',
      package: 'fa-suitcase'
    };
    return icons[type] || 'fa-ticket-alt';
  };

  const filteredBookings = bookings.filter(booking => {
    const matchesFilter = filter === 'all' || booking.status === filter;
    const matchesSearch = booking.booking_number.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         booking.product_name?.toLowerCase().includes(searchTerm.toLowerCase());
    return matchesFilter && matchesSearch;
  });

  if (!user) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      <HeaderModern />
      
      <div className="py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          {/* Header */}
          <div className="mb-8">
            <h1 className="text-4xl font-black text-gray-900 dark:text-white mb-2">Mes Réservations</h1>
            <p className="text-gray-600 dark:text-gray-400">Gérez toutes vos réservations en un seul endroit</p>
          </div>

          {/* Filters */}
          <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-8">
            <div className="flex flex-col md:flex-row gap-4">
              {/* Search */}
              <div className="flex-1">
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i className="fas fa-search text-gray-400"></i>
                  </div>
                  <input
                    type="text"
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    placeholder="Rechercher par numéro de réservation..."
                    className="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0"
                  />
                </div>
              </div>

              {/* Status Filter */}
              <div className="flex gap-2">
                {[
                  { value: 'all', label: 'Toutes', icon: 'fa-list' },
                  { value: 'confirmed', label: 'Confirmées', icon: 'fa-check-circle' },
                  { value: 'pending', label: 'En attente', icon: 'fa-clock' },
                  { value: 'cancelled', label: 'Annulées', icon: 'fa-times-circle' }
                ].map(filterOption => (
                  <button
                    key={filterOption.value}
                    onClick={() => setFilter(filterOption.value)}
                    className={`px-4 py-3 rounded-xl font-semibold transition-colors ${
                      filter === filterOption.value
                        ? 'bg-purple-600 text-white'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                    }`}
                  >
                    <i className={`fas ${filterOption.icon} mr-2`}></i>
                    <span className="hidden sm:inline">{filterOption.label}</span>
                  </button>
                ))}
              </div>
            </div>
          </div>

          {/* Bookings List */}
          {loading ? (
            <div className="flex items-center justify-center py-20">
              <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
            </div>
          ) : filteredBookings.length === 0 ? (
            <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
              <i className="fas fa-ticket-alt text-6xl text-gray-300 dark:text-gray-700 mb-4"></i>
              <h3 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                {searchTerm || filter !== 'all' ? 'Aucune réservation trouvée' : 'Aucune réservation'}
              </h3>
              <p className="text-gray-600 dark:text-gray-400 mb-6">
                {searchTerm || filter !== 'all' 
                  ? 'Essayez de modifier vos filtres de recherche'
                  : 'Commencez à explorer nos offres et réservez votre première expérience'}
              </p>
              <div className="flex flex-wrap gap-4 justify-center">
                <Link to="/flights" className="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition-colors">
                  <i className="fas fa-plane mr-2"></i>
                  Réserver un vol
                </Link>
                <Link to="/events" className="inline-flex items-center px-6 py-3 bg-amber-600 text-white font-bold rounded-xl hover:bg-amber-700 transition-colors">
                  <i className="fas fa-ticket-alt mr-2"></i>
                  Voir les événements
                </Link>
                <Link to="/packages" className="inline-flex items-center px-6 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition-colors">
                  <i className="fas fa-suitcase mr-2"></i>
                  Découvrir les packages
                </Link>
              </div>
            </div>
          ) : (
            <div className="space-y-4">
              {filteredBookings.map((booking) => (
                <div key={booking.id} className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 hover:shadow-2xl transition-shadow">
                  <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    {/* Booking Info */}
                    <div className="flex-1">
                      <div className="flex items-center gap-3 mb-3">
                        <div className="w-12 h-12 bg-purple-100 dark:bg-purple-900/20 rounded-xl flex items-center justify-center">
                          <i className={`fas ${getTypeIcon(booking.product_type)} text-purple-600 text-xl`}></i>
                        </div>
                        <div>
                          <h3 className="text-lg font-bold text-gray-900 dark:text-white">{booking.product_name || 'Réservation'}</h3>
                          <p className="text-sm text-gray-600 dark:text-gray-400">
                            Réservation #{booking.booking_number}
                          </p>
                        </div>
                      </div>
                      
                      <div className="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                          <p className="text-gray-500 dark:text-gray-400 mb-1">Date</p>
                          <p className="font-semibold text-gray-900 dark:text-white">
                            {new Date(booking.created_at).toLocaleDateString('fr-FR')}
                          </p>
                        </div>
                        <div>
                          <p className="text-gray-500 dark:text-gray-400 mb-1">Passagers</p>
                          <p className="font-semibold text-gray-900 dark:text-white">
                            {booking.number_of_passengers || 1}
                          </p>
                        </div>
                        <div>
                          <p className="text-gray-500 dark:text-gray-400 mb-1">Montant</p>
                          <p className="font-semibold text-purple-600">
                            {formatPrice(booking.final_amount)}
                          </p>
                        </div>
                        <div>
                          <p className="text-gray-500 dark:text-gray-400 mb-1">Statut</p>
                          {getStatusBadge(booking.status)}
                        </div>
                      </div>
                    </div>

                    {/* Actions */}
                    <div className="flex flex-col gap-2">
                      <Link
                        to={`/account/bookings/${booking.id}`}
                        className="px-6 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition-colors text-center"
                      >
                        <i className="fas fa-eye mr-2"></i>
                        Voir détails
                      </Link>
                      {booking.status === 'confirmed' && (
                        <button className="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                          <i className="fas fa-download mr-2"></i>
                          Télécharger
                        </button>
                      )}
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}

          {/* Pagination (si nécessaire) */}
          {filteredBookings.length > 0 && (
            <div className="mt-8 flex justify-center">
              <p className="text-gray-600 dark:text-gray-400">
                {filteredBookings.length} réservation{filteredBookings.length > 1 ? 's' : ''} trouvée{filteredBookings.length > 1 ? 's' : ''}
              </p>
            </div>
          )}
        </div>
      </div>

      <FooterModern />
    </div>
  );
};

export default MyBookings;
