import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useLanguage } from '../contexts/LanguageContext';
import { useCurrency } from '../contexts/CurrencyContext';
import HeaderModern from '../components/layout/HeaderModern';
import FooterModern from '../components/layout/FooterModern';
import bookingService from '../services/bookingService';
import { toast } from 'react-toastify';

const MyBookings = () => {
  const { t } = useLanguage();
  const { formatPrice } = useCurrency();
  
  const [bookings, setBookings] = useState([]);
  const [loading, setLoading] = useState(true);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [filters, setFilters] = useState({
    type: 'all',
    status: 'all',
    search: ''
  });

  useEffect(() => {
    loadBookings();
  }, [currentPage]);

  const loadBookings = async () => {
    try {
      setLoading(true);
      const response = await bookingService.getMyBookings(currentPage);
      if (response.success) {
        setBookings(response.data.data);
        setTotalPages(response.data.last_page);
      }
    } catch (error) {
      toast.error('Erreur lors du chargement des réservations');
    } finally {
      setLoading(false);
    }
  };

  const handleDownload = async (type, booking) => {
    try {
      let result;
      switch(type) {
        case 'receipt':
          result = await bookingService.downloadReceipt(booking.id, booking.booking_number);
          break;
        case 'ticket':
          result = await bookingService.downloadTicket(booking.id, booking.booking_number);
          break;
        case 'confirmation':
          result = await bookingService.downloadConfirmation(booking.id, booking.booking_number);
          break;
      }
      if (result.success) {
        toast.success('Téléchargement réussi !');
      } else {
        toast.error(result.message);
      }
    } catch (error) {
      toast.error('Erreur lors du téléchargement');
    }
  };

  const handleCancel = async (booking) => {
    if (!window.confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) return;
    
    try {
      const result = await bookingService.cancelBooking(booking.id, 'Annulation client');
      if (result.success) {
        toast.success('Réservation annulée avec succès');
        loadBookings();
      }
    } catch (error) {
      toast.error('Erreur lors de l\'annulation');
    }
  };

  const filteredBookings = bookings.filter(booking => {
    if (filters.type !== 'all' && booking.booking_type !== filters.type) return false;
    if (filters.status !== 'all' && booking.status !== filters.status) return false;
    if (filters.search && !booking.booking_number.toLowerCase().includes(filters.search.toLowerCase())) return false;
    return true;
  });

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      <HeaderModern />
      
      <div className="py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          {/* Header */}
          <div className="mb-8">
            <h1 className="text-4xl font-black text-gray-900 dark:text-white mb-2">
              {t('myBookings', 'Mes Réservations')}
            </h1>
            <p className="text-gray-600 dark:text-gray-400">
              {t('manageBookings', 'Gérez vos réservations et téléchargez vos documents')}
            </p>
          </div>

          {/* Filters */}
          <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-8">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
              {/* Search */}
              <div className="md:col-span-2">
                <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                  {t('search', 'Rechercher')}
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i className="fas fa-search text-gray-400"></i>
                  </div>
                  <input
                    type="text"
                    placeholder={t('searchByNumber', 'Rechercher par numéro...')}
                    value={filters.search}
                    onChange={(e) => setFilters({...filters, search: e.target.value})}
                    className="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0"
                  />
                </div>
              </div>

              {/* Type Filter */}
              <div>
                <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                  {t('type', 'Type')}
                </label>
                <select
                  value={filters.type}
                  onChange={(e) => setFilters({...filters, type: e.target.value})}
                  className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0"
                >
                  <option value="all">{t('all', 'Tous')}</option>
                  <option value="flight">{t('flights', 'Vols')}</option>
                  <option value="event">{t('events', 'Événements')}</option>
                  <option value="package">{t('packages', 'Packages')}</option>
                </select>
              </div>

              {/* Status Filter */}
              <div>
                <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                  {t('status', 'Statut')}
                </label>
                <select
                  value={filters.status}
                  onChange={(e) => setFilters({...filters, status: e.target.value})}
                  className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0"
                >
                  <option value="all">{t('all', 'Tous')}</option>
                  <option value="pending">{t('pending', 'En attente')}</option>
                  <option value="confirmed">{t('confirmed', 'Confirmée')}</option>
                  <option value="cancelled">{t('cancelled', 'Annulée')}</option>
                  <option value="completed">{t('completed', 'Complétée')}</option>
                </select>
              </div>
            </div>
          </div>

          {/* Bookings List */}
          {loading ? (
            <div className="grid gap-6">
              {[1, 2, 3].map((i) => (
                <div key={i} className="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg animate-pulse">
                  <div className="h-32 bg-gray-300 dark:bg-gray-700 rounded"></div>
                </div>
              ))}
            </div>
          ) : filteredBookings.length === 0 ? (
            <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
              <i className="fas fa-inbox text-6xl text-gray-400 mb-4"></i>
              <h3 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                {t('noBookings', 'Aucune réservation')}
              </h3>
              <p className="text-gray-600 dark:text-gray-400 mb-6">
                {t('noBookingsDesc', 'Vous n\'avez pas encore de réservation')}
              </p>
              <Link
                to="/flights"
                className="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold rounded-xl hover:shadow-lg transition-all"
              >
                <i className="fas fa-plane mr-2"></i>
                {t('bookNow', 'Réserver maintenant')}
              </Link>
            </div>
          ) : (
            <div className="space-y-6">
              {filteredBookings.map((booking) => {
                const statusInfo = bookingService.formatStatus(booking.status);
                const typeInfo = bookingService.formatType(booking.booking_type);
                
                return (
                  <div key={booking.id} className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow">
                    <div className="p-6">
                      <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                        {/* Booking Info */}
                        <div className="flex-1">
                          <div className="flex items-center space-x-3 mb-2">
                            <i className={`fas ${typeInfo.icon} ${typeInfo.color} text-xl`}></i>
                            <h3 className="text-xl font-black text-gray-900 dark:text-white">
                              {booking.booking_number}
                            </h3>
                            <span className={`px-3 py-1 rounded-full text-xs font-bold ${statusInfo.bgColor} ${statusInfo.textColor}`}>
                              {statusInfo.label}
                            </span>
                          </div>
                          <p className="text-sm text-gray-600 dark:text-gray-400">
                            <i className="fas fa-calendar mr-2"></i>
                            {new Date(booking.created_at).toLocaleDateString('fr-FR', { 
                              year: 'numeric', month: 'long', day: 'numeric' 
                            })}
                          </p>
                        </div>

                        {/* Price */}
                        <div className="text-right">
                          <p className="text-sm text-gray-600 dark:text-gray-400 mb-1">
                            {t('totalAmount', 'Montant total')}
                          </p>
                          <p className="text-3xl font-black bg-gradient-to-r from-purple-600 to-amber-600 bg-clip-text text-transparent">
                            {formatPrice(booking.final_amount)}
                          </p>
                        </div>
                      </div>

                      {/* Actions */}
                      <div className="flex flex-wrap gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <Link
                          to={`/my-bookings/${booking.id}`}
                          className="flex-1 md:flex-none px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-center"
                        >
                          <i className="fas fa-eye mr-2"></i>
                          {t('viewDetails', 'Voir détails')}
                        </Link>

                        {booking.payment_status === 'paid' && (
                          <>
                            <button
                              onClick={() => handleDownload('receipt', booking)}
                              className="flex-1 md:flex-none px-4 py-2 bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400 font-semibold rounded-lg hover:bg-green-200 dark:hover:bg-green-900/40 transition-colors"
                            >
                              <i className="fas fa-file-invoice mr-2"></i>
                              {t('receipt', 'Reçu')}
                            </button>

                            {booking.booking_type === 'flight' && (
                              <button
                                onClick={() => handleDownload('ticket', booking)}
                                className="flex-1 md:flex-none px-4 py-2 bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 font-semibold rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/40 transition-colors"
                              >
                                <i className="fas fa-ticket-alt mr-2"></i>
                                {t('ticket', 'Billet')}
                              </button>
                            )}

                            <button
                              onClick={() => handleDownload('confirmation', booking)}
                              className="flex-1 md:flex-none px-4 py-2 bg-purple-100 dark:bg-purple-900/20 text-purple-700 dark:text-purple-400 font-semibold rounded-lg hover:bg-purple-200 dark:hover:bg-purple-900/40 transition-colors"
                            >
                              <i className="fas fa-file-check mr-2"></i>
                              {t('confirmation', 'Confirmation')}
                            </button>
                          </>
                        )}

                        {(booking.status === 'pending' || booking.status === 'confirmed') && (
                          <button
                            onClick={() => handleCancel(booking)}
                            className="flex-1 md:flex-none px-4 py-2 bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-400 font-semibold rounded-lg hover:bg-red-200 dark:hover:bg-red-900/40 transition-colors"
                          >
                            <i className="fas fa-times-circle mr-2"></i>
                            {t('cancel', 'Annuler')}
                          </button>
                        )}
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          )}

          {/* Pagination */}
          {totalPages > 1 && (
            <div className="mt-8 flex justify-center">
              <nav className="flex items-center space-x-2">
                <button
                  onClick={() => setCurrentPage(p => Math.max(1, p - 1))}
                  disabled={currentPage === 1}
                  className="px-4 py-2 border-2 border-gray-300 dark:border-gray-700 rounded-lg font-semibold disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                >
                  <i className="fas fa-chevron-left"></i>
                </button>
                
                {[...Array(totalPages)].map((_, i) => (
                  <button
                    key={i}
                    onClick={() => setCurrentPage(i + 1)}
                    className={`px-4 py-2 rounded-lg font-semibold transition-colors ${
                      currentPage === i + 1
                        ? 'bg-purple-600 text-white'
                        : 'border-2 border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800'
                    }`}
                  >
                    {i + 1}
                  </button>
                ))}
                
                <button
                  onClick={() => setCurrentPage(p => Math.min(totalPages, p + 1))}
                  disabled={currentPage === totalPages}
                  className="px-4 py-2 border-2 border-gray-300 dark:border-gray-700 rounded-lg font-semibold disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                >
                  <i className="fas fa-chevron-right"></i>
                </button>
              </nav>
            </div>
          )}
        </div>
      </div>

      <FooterModern />
    </div>
  );
};

export default MyBookings;
