import React from 'react';
import { useNavigate } from 'react-router-dom';
import { useLanguage } from '../../contexts/LanguageContext';
import { useCurrency } from '../../contexts/CurrencyContext';
import amadeusService from '../../services/amadeusService';

const FlightResults = ({ results, loading }) => {
  const navigate = useNavigate();
  const { t } = useLanguage();
  const { currency, convertPrice } = useCurrency();

  if (loading) {
    return (
      <div className="flex justify-center items-center py-20">
        <div className="animate-spin rounded-full h-16 w-16 border-t-4 border-purple-600"></div>
        <p className="ml-4 text-xl text-gray-600">{t('searching', 'Recherche en cours...')}</p>
      </div>
    );
  }

  if (!results || !results.data || results.data.offers.length === 0) {
    return (
      <div className="text-center py-20 bg-white rounded-2xl shadow-lg">
        <i className="fas fa-plane-slash text-6xl text-gray-400 mb-4"></i>
        <h3 className="text-2xl font-bold text-gray-700 mb-2">
          {t('noFlightsFound', 'Aucun vol trouvé')}
        </h3>
        <p className="text-gray-500">
          {t('tryModifyingSearch', 'Essayez de modifier vos critères de recherche')}
        </p>
      </div>
    );
  }

  const { offers, dictionaries } = results.data;

  return (
    <div className="space-y-6">
      {/* En-tête des résultats */}
      <div className="flex justify-between items-center mb-6 bg-white rounded-xl shadow-md p-6">
        <div>
          <h3 className="text-3xl font-bold text-gray-800">
            {offers.length} {t('flightsFound', 'vol(s) trouvé(s)')}
          </h3>
          <p className="text-gray-600 mt-1">
            {t('realTimeResults', 'Résultats en temps réel via Amadeus')}
          </p>
        </div>
        <div className="text-right">
          <p className="text-sm text-gray-500">{t('sortBy', 'Trier par')}</p>
          <select className="mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
            <option value="price">{t('price', 'Prix')}</option>
            <option value="duration">{t('duration', 'Durée')}</option>
            <option value="departure">{t('departureTime', 'Heure de départ')}</option>
          </select>
        </div>
      </div>

      {/* Liste des vols */}
      {offers.map((offer) => {
        const formatted = amadeusService.formatFlightOffer(offer, dictionaries);
        const departureDateTime = amadeusService.formatDateTime(formatted.outbound.departure.time);
        const arrivalDateTime = amadeusService.formatDateTime(formatted.outbound.arrival.time);
        
        return (
          <div
            key={offer.id}
            className="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1"
            onClick={() => navigate(`/flight/${offer.id}`, { state: { offer, dictionaries } })}
          >
            <div className="p-6">
              {/* Vol aller */}
              <div className="flex items-center justify-between">
                {/* Informations de vol */}
                <div className="flex-1">
                  <div className="flex items-center gap-8">
                    {/* Départ */}
                    <div className="text-center">
                      <div className="text-3xl font-bold text-gray-800">
                        {departureDateTime.time}
                      </div>
                      <div className="text-lg font-semibold text-gray-600 mt-1">
                        {formatted.outbound.departure.airport}
                      </div>
                      <div className="text-sm text-gray-500">
                        {departureDateTime.date}
                      </div>
                    </div>
                    
                    {/* Durée et escales */}
                    <div className="flex-1 px-4">
                      <div className="text-center mb-2">
                        <span className="text-sm font-medium text-gray-600">
                          {amadeusService.formatDuration(formatted.outbound.duration)}
                        </span>
                      </div>
                      <div className="relative">
                        <div className="border-t-2 border-gray-300 relative">
                          <i className="fas fa-plane absolute -top-3 left-1/2 transform -translate-x-1/2 text-purple-600 text-xl"></i>
                        </div>
                      </div>
                      <div className="text-center mt-2">
                        <span className="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                          {formatted.outbound.stops === 0 
                            ? t('direct', 'Direct') 
                            : `${formatted.outbound.stops} ${t('stop(s)', 'escale(s)')}`}
                        </span>
                      </div>
                    </div>
                    
                    {/* Arrivée */}
                    <div className="text-center">
                      <div className="text-3xl font-bold text-gray-800">
                        {arrivalDateTime.time}
                      </div>
                      <div className="text-lg font-semibold text-gray-600 mt-1">
                        {formatted.outbound.arrival.airport}
                      </div>
                      <div className="text-sm text-gray-500">
                        {arrivalDateTime.date}
                      </div>
                    </div>
                  </div>
                  
                  {/* Compagnie aérienne */}
                  <div className="mt-4 flex items-center gap-2 text-gray-600">
                    <i className="fas fa-plane text-purple-600"></i>
                    <span className="font-medium">{formatted.airlines}</span>
                  </div>
                </div>
                
                {/* Prix et action */}
                <div className="text-right ml-8 pl-8 border-l-2 border-gray-200">
                  <div className="mb-2">
                    <span className="text-sm text-gray-500">{t('from', 'À partir de')}</span>
                  </div>
                  <div className="text-4xl font-bold text-purple-600 mb-1">
                    {Math.round(convertPrice(formatted.price.total)).toLocaleString()} 
                  </div>
                  <div className="text-lg text-gray-600 mb-3">
                    {currency.symbol}
                  </div>
                  <div className="text-sm text-gray-500 mb-4">
                    <i className="fas fa-chair mr-1"></i>
                    {formatted.availableSeats} {t('seatsLeft', 'siège(s) restant(s)')}
                  </div>
                  <button className="w-full bg-gradient-to-r from-purple-600 to-purple-800 hover:from-purple-700 hover:to-purple-900 text-white font-bold px-8 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                    {t('select', 'Sélectionner')}
                  </button>
                </div>
              </div>

              {/* Vol retour si disponible */}
              {formatted.inbound && (
                <div className="mt-6 pt-6 border-t-2 border-gray-100">
                  <div className="flex items-center gap-2 mb-4">
                    <i className="fas fa-exchange-alt text-purple-600"></i>
                    <span className="font-semibold text-gray-700">{t('returnFlight', 'Vol retour')}</span>
                  </div>
                  <div className="flex items-center gap-8">
                    {/* Départ retour */}
                    <div className="text-center">
                      <div className="text-2xl font-bold text-gray-800">
                        {amadeusService.formatDateTime(formatted.inbound.departure.time).time}
                      </div>
                      <div className="text-sm font-semibold text-gray-600">
                        {formatted.inbound.departure.airport}
                      </div>
                    </div>
                    
                    {/* Durée retour */}
                    <div className="flex-1 px-4">
                      <div className="text-center text-sm text-gray-600">
                        {amadeusService.formatDuration(formatted.inbound.duration)}
                      </div>
                      <div className="border-t-2 border-gray-300 my-2"></div>
                      <div className="text-center text-xs text-gray-500">
                        {formatted.inbound.stops === 0 
                          ? t('direct', 'Direct') 
                          : `${formatted.inbound.stops} ${t('stop(s)', 'escale(s)')}`}
                      </div>
                    </div>
                    
                    {/* Arrivée retour */}
                    <div className="text-center">
                      <div className="text-2xl font-bold text-gray-800">
                        {amadeusService.formatDateTime(formatted.inbound.arrival.time).time}
                      </div>
                      <div className="text-sm font-semibold text-gray-600">
                        {formatted.inbound.arrival.airport}
                      </div>
                    </div>
                  </div>
                </div>
              )}
            </div>
          </div>
        );
      })}

      {/* Message informatif */}
      <div className="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div className="flex items-start">
          <i className="fas fa-info-circle text-blue-500 text-xl mt-1 mr-3"></i>
          <div>
            <p className="text-sm text-blue-800">
              <strong>{t('note', 'Note')}:</strong> {t('pricesRealTime', 'Les prix affichés sont en temps réel et peuvent varier. Cliquez sur un vol pour voir les détails complets et finaliser votre réservation.')}
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default FlightResults;
