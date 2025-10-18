import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import { useLanguage } from '../contexts/LanguageContext';
import { useCurrency } from '../contexts/CurrencyContext';
import HeaderModern from '../components/layout/HeaderModern';
import FooterModern from '../components/layout/FooterModern';
import bookingService from '../services/bookingService';
import { toast } from 'react-toastify';

const BookingDetails = () => {
  const { id } = useParams();
  const { t } = useLanguage();
  const { formatPrice } = useCurrency();
  const navigate = useNavigate();
  
  const [booking, setBooking] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadBookingDetails();
  }, [id]);

  const loadBookingDetails = async () => {
    try {
      setLoading(true);
      const response = await bookingService.getBookingDetails(id);
      if (response.success) {
        setBooking(response.data);
      }
    } catch (error) {
      toast.error('Erreur lors du chargement des détails');
      navigate('/my-bookings');
    } finally {
      setLoading(false);
    }
  };

  const handleDownload = async (type) => {
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

  const handleCancel = async () => {
    if (!window.confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) return;
    
    try {
      const result = await bookingService.cancelBooking(booking.id, 'Annulation client');
      if (result.success) {
        toast.success('Réservation annulée avec succès');
        loadBookingDetails();
      }
    } catch (error) {
      toast.error('Erreur lors de l\'annulation');
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900">
        <div className="animate-spin rounded-full h-16 w-16 border-b-4 border-purple-600"></div>
      </div>
    );
  }

  if (!booking) {
    return null;
  }

  const statusInfo = bookingService.formatStatus(booking.status);
  const typeInfo = bookingService.formatType(booking.booking_type);

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      <HeaderModern />
      
      <div className="py-12">
        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          {/* Back Button */}
          <Link
            to="/my-bookings"
            className="inline-flex items-center text-purple-600 hover:text-purple-700 font-semibold mb-6"
          >
            <i className="fas fa-arrow-left mr-2"></i>
            {t('backToBookings', 'Retour aux réservations')}
          </Link>

          {/* Header */}
          <div className="bg-gradient-to-r from-purple-600 to-amber-600 rounded-3xl p-8 mb-8 text-white">
            <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
              <div>
                <div className="flex items-center space-x-3 mb-2">
                  <i className={`fas ${typeInfo.icon} text-3xl`}></i>
                  <h1 className="text-3xl font-black">{booking.booking_number}</h1>
                </div>
                <p className="text-white/80">
                  <i className="fas fa-calendar mr-2"></i>
                  {new Date(booking.created_at).toLocaleDateString('fr-FR', { 
                    year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
                  })}
                </p>
              </div>
              <div className="text-right">
                <span className={`inline-block px-6 py-3 rounded-full text-lg font-bold ${statusInfo.bgColor} ${statusInfo.textColor}`}>
                  {statusInfo.label}
                </span>
              </div>
            </div>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Main Content */}
            <div className="lg:col-span-2 space-y-6">
              {/* Booking Details */}
              <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <h2 className="text-2xl font-black text-gray-900 dark:text-white mb-6">
                  {t('bookingDetails', 'Détails de la Réservation')}
                </h2>

                {/* Flight Details */}
                {booking.booking_type === 'flight' && booking.flight_booking && (
                  <div className="space-y-4">
                    <div className="grid grid-cols-2 gap-4">
                      <div>
                        <p className="text-sm text-gray-600 dark:text-gray-400 mb-1">PNR</p>
                        <p className="text-lg font-bold text-gray-900 dark:text-white">{booking.flight_booking.pnr}</p>
                      </div>
                      <div>
                        <p className="text-sm text-gray-600 dark:text-gray-400 mb-1">E-Ticket</p>
                        <p className="text-lg font-bold text-gray-900 dark:text-white">{booking.flight_booking.eticket_number}</p>
                      </div>
                    </div>
                    
                    {booking.passenger_details && (
                      <div>
                        <p className="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Passagers</p>
                        <div className="space-y-2">
                          {JSON.parse(booking.passenger_details).map((passenger, idx) => (
                            <div key={idx} className="p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                              <p className="font-semibold text-gray-900 dark:text-white">
                                {passenger.first_name} {passenger.last_name}
                              </p>
                              <p className="text-sm text-gray-600 dark:text-gray-400">
                                {passenger.passport_number}
                              </p>
                            </div>
                          ))}
                        </div>
                      </div>
                    )}
                  </div>
                )}

                {/* Event Details */}
                {booking.booking_type === 'event' && booking.event && (
                  <div className="space-y-4">
                    <div>
                      <p className="text-sm text-gray-600 dark:text-gray-400 mb-1">Événement</p>
                      <p className="text-xl font-bold text-gray-900 dark:text-white">{booking.event.title_fr}</p>
                    </div>
                    <div className="grid grid-cols-2 gap-4">
                      <div>
                        <p className="text-sm text-gray-600 dark:text-gray-400 mb-1">Date</p>
                        <p className="font-bold text-gray-900 dark:text-white">{booking.event.event_date}</p>
                      </div>
                      <div>
                        <p className="text-sm text-gray-600 dark:text-gray-400 mb-1">Lieu</p>
                        <p className="font-bold text-gray-900 dark:text-white">{booking.event.venue_name}</p>
                      </div>
                    </div>
                  </div>
                )}

                {/* Package Details */}
                {booking.booking_type === 'package' && booking.package && (
                  <div className="space-y-4">
                    <div>
                      <p className="text-sm text-gray-600 dark:text-gray-400 mb-1">Package</p>
                      <p className="text-xl font-bold text-gray-900 dark:text-white">{booking.package.title_fr}</p>
                    </div>
                    <div className="grid grid-cols-2 gap-4">
                      <div>
                        <p className="text-sm text-gray-600 dark:text-gray-400 mb-1">Destination</p>
                        <p className="font-bold text-gray-900 dark:text-white">{booking.package.destination}</p>
                      </div>
                      <div>
                        <p className="text-sm text-gray-600 dark:text-gray-400 mb-1">Durée</p>
                        <p className="font-bold text-gray-900 dark:text-white">{booking.package.duration} jours</p>
                      </div>
                    </div>
                  </div>
                )}
              </div>

              {/* Payment Information */}
              <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <h2 className="text-2xl font-black text-gray-900 dark:text-white mb-6">
                  {t('paymentInfo', 'Informations de Paiement')}
                </h2>

                <div className="space-y-4">
                  <div className="flex justify-between">
                    <span className="text-gray-600 dark:text-gray-400">Montant de base</span>
                    <span className="font-semibold text-gray-900 dark:text-white">{formatPrice(booking.total_amount)}</span>
                  </div>
                  {booking.tax_amount > 0 && (
                    <div className="flex justify-between">
                      <span className="text-gray-600 dark:text-gray-400">Taxes</span>
                      <span className="font-semibold text-gray-900 dark:text-white">{formatPrice(booking.tax_amount)}</span>
                    </div>
                  )}
                  {booking.discount_amount > 0 && (
                    <div className="flex justify-between text-green-600">
                      <span>Réduction</span>
                      <span className="font-semibold">-{formatPrice(booking.discount_amount)}</span>
                    </div>
                  )}
                  <div className="border-t-2 border-gray-200 dark:border-gray-700 pt-4">
                    <div className="flex justify-between items-center">
                      <span className="text-xl font-bold text-gray-900 dark:text-white">Total</span>
                      <span className="text-3xl font-black bg-gradient-to-r from-purple-600 to-amber-600 bg-clip-text text-transparent">
                        {formatPrice(booking.final_amount)}
                      </span>
                    </div>
                  </div>

                  {booking.payment && (
                    <div className="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                      <div className="flex justify-between text-sm">
                        <span className="text-gray-600 dark:text-gray-400">Méthode de paiement</span>
                        <span className="font-semibold text-gray-900 dark:text-white capitalize">{booking.payment.payment_method}</span>
                      </div>
                      <div className="flex justify-between text-sm mt-2">
                        <span className="text-gray-600 dark:text-gray-400">Statut du paiement</span>
                        <span className={`font-semibold ${booking.payment.status === 'completed' ? 'text-green-600' : 'text-yellow-600'}`}>
                          {booking.payment.status === 'completed' ? 'Payé' : 'En attente'}
                        </span>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            </div>

            {/* Sidebar - Actions */}
            <div className="lg:col-span-1">
              <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sticky top-24">
                <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-4">
                  {t('actions', 'Actions')}
                </h3>

                <div className="space-y-3">
                  {booking.payment_status === 'paid' && (
                    <>
                      <button
                        onClick={() => handleDownload('receipt')}
                        className="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition-colors"
                      >
                        <i className="fas fa-file-invoice mr-2"></i>
                        {t('downloadReceipt', 'Télécharger le reçu')}
                      </button>

                      {booking.booking_type === 'flight' && (
                        <button
                          onClick={() => handleDownload('ticket')}
                          className="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors"
                        >
                          <i className="fas fa-ticket-alt mr-2"></i>
                          {t('downloadTicket', 'Télécharger le billet')}
                        </button>
                      )}

                      <button
                        onClick={() => handleDownload('confirmation')}
                        className="w-full flex items-center justify-center px-4 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition-colors"
                      >
                        <i className="fas fa-file-check mr-2"></i>
                        {t('downloadConfirmation', 'Télécharger la confirmation')}
                      </button>
                    </>
                  )}

                  {(booking.status === 'pending' || booking.status === 'confirmed') && (
                    <button
                      onClick={handleCancel}
                      className="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors"
                    >
                      <i className="fas fa-times-circle mr-2"></i>
                      {t('cancelBooking', 'Annuler la réservation')}
                    </button>
                  )}

                  <Link
                    to="/contact"
                    className="w-full flex items-center justify-center px-4 py-3 border-2 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                  >
                    <i className="fas fa-headset mr-2"></i>
                    {t('contactSupport', 'Contacter le support')}
                  </Link>
                </div>

                {/* Info Box */}
                <div className="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border-l-4 border-blue-500">
                  <p className="text-sm text-blue-700 dark:text-blue-400">
                    <i className="fas fa-info-circle mr-2"></i>
                    {t('bookingHelp', 'Besoin d\'aide ? Notre équipe est disponible 24/7')}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <FooterModern />
    </div>
  );
};

export default BookingDetails;
