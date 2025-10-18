import api from './api';

/**
 * Service pour l'intégration Amadeus API
 * Gère toutes les interactions avec les endpoints Amadeus
 */

const amadeusService = {
  /**
   * Rechercher des vols
   * @param {Object} searchParams - Paramètres de recherche
   * @returns {Promise}
   */
  searchFlights: async (searchParams) => {
    try {
      const response = await api.post('/amadeus/flights/search', {
        origin: searchParams.origin,
        destination: searchParams.destination,
        departureDate: searchParams.departureDate,
        returnDate: searchParams.returnDate || null,
        adults: searchParams.adults || 1,
        children: searchParams.children || 0,
        infants: searchParams.infants || 0,
        travelClass: searchParams.travelClass || 'ECONOMY',
        nonStop: searchParams.nonStop || false,
        currencyCode: searchParams.currencyCode || 'XOF',
        max: searchParams.max || 50
      });
      return response.data;
    } catch (error) {
      console.error('Error searching flights:', error);
      throw error;
    }
  },

  /**
   * Confirmer le prix d'une offre de vol
   * @param {Object} flightOffer - Offre de vol
   * @returns {Promise}
   */
  confirmPrice: async (flightOffer) => {
    try {
      const response = await api.post('/amadeus/flights/confirm-price', {
        flightOffer
      });
      return response.data;
    } catch (error) {
      console.error('Error confirming price:', error);
      throw error;
    }
  },

  /**
   * Créer une réservation
   * @param {Object} bookingData - Données de réservation
   * @returns {Promise}
   */
  createBooking: async (bookingData) => {
    try {
      const response = await api.post('/amadeus/bookings', {
        flightOffer: bookingData.flightOffer,
        travelers: bookingData.travelers,
        contact: bookingData.contact,
        user_id: bookingData.user_id || null
      });
      return response.data;
    } catch (error) {
      console.error('Error creating booking:', error);
      throw error;
    }
  },

  /**
   * Récupérer les détails d'une réservation
   * @param {number} bookingId - ID de la réservation
   * @returns {Promise}
   */
  getBooking: async (bookingId) => {
    try {
      const response = await api.get(`/amadeus/bookings/${bookingId}`);
      return response.data;
    } catch (error) {
      console.error('Error getting booking:', error);
      throw error;
    }
  },

  /**
   * Annuler une réservation
   * @param {number} bookingId - ID de la réservation
   * @param {string} reason - Raison de l'annulation
   * @returns {Promise}
   */
  cancelBooking: async (bookingId, reason = '') => {
    try {
      const response = await api.delete(`/amadeus/bookings/${bookingId}`, {
        data: { reason }
      });
      return response.data;
    } catch (error) {
      console.error('Error cancelling booking:', error);
      throw error;
    }
  },

  /**
   * Rechercher des aéroports
   * @param {string} keyword - Mot-clé de recherche
   * @returns {Promise}
   */
  searchAirports: async (keyword) => {
    try {
      const response = await api.get('/amadeus/airports/search', {
        params: { keyword }
      });
      return response.data;
    } catch (error) {
      console.error('Error searching airports:', error);
      throw error;
    }
  },

  /**
   * Récupérer mes réservations (authentifié)
   * @returns {Promise}
   */
  getMyBookings: async () => {
    try {
      const response = await api.get('/amadeus/my-bookings');
      return response.data;
    } catch (error) {
      console.error('Error getting my bookings:', error);
      throw error;
    }
  },

  /**
   * Formater les données de vol pour l'affichage
   * @param {Object} offer - Offre de vol Amadeus
   * @param {Object} dictionaries - Dictionnaires Amadeus
   * @returns {Object}
   */
  formatFlightOffer: (offer, dictionaries = {}) => {
    const itineraries = offer.itineraries || [];
    const price = offer.price || {};
    
    // Extraire les informations du premier segment (aller)
    const outbound = itineraries[0] || {};
    const outboundSegments = outbound.segments || [];
    const firstSegment = outboundSegments[0] || {};
    const lastSegment = outboundSegments[outboundSegments.length - 1] || {};

    // Extraire les informations du retour si disponible
    const inbound = itineraries[1] || null;
    const inboundSegments = inbound ? (inbound.segments || []) : [];

    // Récupérer les noms des compagnies aériennes
    const carrierCodes = offer.validatingAirlineCodes || [];
    const airlines = carrierCodes.map(code => 
      dictionaries.carriers?.[code] || code
    );

    return {
      id: offer.id,
      price: {
        total: parseFloat(price.total || 0),
        currency: price.currency || 'XOF',
        base: parseFloat(price.base || 0)
      },
      outbound: {
        departure: {
          airport: firstSegment.departure?.iataCode || '',
          time: firstSegment.departure?.at || '',
          terminal: firstSegment.departure?.terminal || ''
        },
        arrival: {
          airport: lastSegment.arrival?.iataCode || '',
          time: lastSegment.arrival?.at || '',
          terminal: lastSegment.arrival?.terminal || ''
        },
        duration: outbound.duration || '',
        stops: outboundSegments.length - 1,
        segments: outboundSegments
      },
      inbound: inbound ? {
        departure: {
          airport: inboundSegments[0]?.departure?.iataCode || '',
          time: inboundSegments[0]?.departure?.at || '',
          terminal: inboundSegments[0]?.departure?.terminal || ''
        },
        arrival: {
          airport: inboundSegments[inboundSegments.length - 1]?.arrival?.iataCode || '',
          time: inboundSegments[inboundSegments.length - 1]?.arrival?.at || '',
          terminal: inboundSegments[inboundSegments.length - 1]?.arrival?.terminal || ''
        },
        duration: inbound.duration || '',
        stops: inboundSegments.length - 1,
        segments: inboundSegments
      } : null,
      airlines: airlines.join(', '),
      availableSeats: offer.numberOfBookableSeats || 9,
      rawOffer: offer // Garder l'offre complète pour la réservation
    };
  },

  /**
   * Formater la durée ISO 8601 en format lisible
   * @param {string} duration - Durée au format ISO 8601 (ex: PT2H30M)
   * @returns {string}
   */
  formatDuration: (duration) => {
    if (!duration) return '';
    
    const match = duration.match(/PT(\d+H)?(\d+M)?/);
    if (!match) return duration;

    const hours = match[1] ? parseInt(match[1]) : 0;
    const minutes = match[2] ? parseInt(match[2]) : 0;

    if (hours && minutes) {
      return `${hours}h ${minutes}min`;
    } else if (hours) {
      return `${hours}h`;
    } else if (minutes) {
      return `${minutes}min`;
    }
    return duration;
  },

  /**
   * Formater la date/heure
   * @param {string} dateTime - Date/heure ISO
   * @returns {Object}
   */
  formatDateTime: (dateTime) => {
    if (!dateTime) return { date: '', time: '' };
    
    const dt = new Date(dateTime);
    return {
      date: dt.toLocaleDateString('fr-FR', { 
        day: '2-digit', 
        month: 'short', 
        year: 'numeric' 
      }),
      time: dt.toLocaleTimeString('fr-FR', { 
        hour: '2-digit', 
        minute: '2-digit' 
      }),
      full: dt
    };
  }
};

export default amadeusService;
