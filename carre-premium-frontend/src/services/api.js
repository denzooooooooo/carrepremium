import axios from 'axios';

// Configuration de l'API
//const API_URL = process.env.REACT_APP_API_URL || 'http://192.168.1.14:8000/api';

const API_URL = 'http://192.168.1.14:8000/api';
// Créer une instance axios avec configuration par défaut
const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

// Intercepteur pour ajouter le token d'authentification
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Intercepteur pour gérer les erreurs
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token expiré ou invalide
      localStorage.removeItem('auth_token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// ============================================
// FLIGHTS API
// ============================================
export const flightService = {
  /**
   * Get all flights with filters
   */
  getFlights: async (params = {}) => {
    try {
      const response = await api.get('/flights', { params });
      return response.data;
    } catch (error) {
      console.error('Error fetching flights:', error);
      throw error;
    }
  },

  /**
   * Get all flights with pagination
   */
  getAllFlights: async (page = 1, perPage = 20) => {
    try {
      const response = await api.get('/flights', {
        params: { page, per_page: perPage }
      });
      return response.data;
    } catch (error) {
      console.error('Error fetching all flights:', error);
      throw error;
    }
  },

  /**
   * Get flight by ID
   */
  getFlightById: async (id) => {
    try {
      const response = await api.get(`/flights/${id}`);
      return response.data;
    } catch (error) {
      console.error('Error fetching flight:', error);
      throw error;
    }
  },

  /**
   * Search flights
   */
  /* searchFlights: async (searchData) => {
    try {
      const response = await api.post('/flights/search', searchData);
      return response.data;
    } catch (error) {
      console.error('Error searching flights:', error);
      throw error;
    }
  }, */
  searchFlights: async (searchData) => {
    try {
      console.log('📤 Envoi des paramètres:', searchData);

      const response = await api.get('/flights/search', {
        params: searchData  // ✅ Les paramètres doivent être dans "params"
      });

      console.log('📥 Réponse reçue:', response.data);
      return response.data;
    } catch (error) {
      console.error('❌ Error searching flights:', error);
      if (error.response) {
        console.error('📛 Error details:', error.response.data);
        console.error('📛 Error status:', error.response.status);
      }
      throw error;
    }
  },


  /**
   * Get popular flights
   */
  getPopularFlights: async (limit = 6) => {
    try {
      const response = await api.get('/flights/popular', { params: { limit } });
      return response.data;
    } catch (error) {
      console.error('Error fetching popular flights:', error);
      throw error;
    }
  },

  /**
   * Check flight availability
   */
  checkAvailability: async (id, data) => {
    try {
      const response = await api.post(`/flights/${id}/check-availability`, data);
      return response.data;
    } catch (error) {
      console.error('Error checking availability:', error);
      throw error;
    }
  },

  /**
   * Get airlines
   */
  getAirlines: async () => {
    try {
      const response = await api.get('/airlines');
      return response.data;
    } catch (error) {
      console.error('Error fetching airlines:', error);
      throw error;
    }
  },

  /**
   * Get airports
   */
  /* getAirports: async (search = '') => {
    try {
      const response = await api.get('/airports', { params: { search } });
      return response.data;
    } catch (error) {
      console.error('Error fetching airports:', error);
      throw error;
    }
  } */

  getAirports: async (keyword = '') => {
    try {
      const response = await api.get('/flights/airports/search', { params: { keyword } });
      return response.data;
    } catch (error) {
      console.error('Error fetching airports:', error);
      throw error;
    }
  },
};

// ============================================
// EVENTS API
// ============================================
export const eventService = {
  /**
   * Get all events with filters
   */
  getEvents: async (params = {}) => {
    try {
      const response = await api.get('/events', { params });
      return response.data;
    } catch (error) {
      console.error('Error fetching events:', error);
      throw error;
    }
  },

  /**
   * Get event by ID
   */
  getEventById: async (id) => {
    try {
      const response = await api.get(`/events/${id}`);
      return response.data;
    } catch (error) {
      console.error('Error fetching event:', error);
      throw error;
    }
  },

  /**
   * Get upcoming events
   */
  getUpcomingEvents: async (limit = 6) => {
    try {
      const response = await api.get('/events/upcoming', { params: { limit } });
      return response.data;
    } catch (error) {
      console.error('Error fetching upcoming events:', error);
      throw error;
    }
  },

  /**
   * Get events by category
   */
  getEventsByCategory: async (categoryId) => {
    try {
      const response = await api.get(`/events/category/${categoryId}`);
      return response.data;
    } catch (error) {
      console.error('Error fetching events by category:', error);
      throw error;
    }
  },

  /**
   * Get event categories
   */
  getCategories: async () => {
    try {
      const response = await api.get('/events/categories');
      return response.data;
    } catch (error) {
      console.error('Error fetching categories:', error);
      throw error;
    }
  },

  /**
   * Search events
   */
  searchEvents: async (searchData) => {
    try {
      const response = await api.post('/events/search', searchData);
      return response.data;
    } catch (error) {
      console.error('Error searching events:', error);
      throw error;
    }
  },

  /**
   * Check event availability
   */
  checkAvailability: async (id, data) => {
    try {
      const response = await api.post(`/events/${id}/check-availability`, data);
      return response.data;
    } catch (error) {
      console.error('Error checking availability:', error);
      throw error;
    }
  }
};

// ============================================
// PACKAGES API
// ============================================
export const packageService = {
  /**
   * Get all packages with filters
   */
  getPackages: async (params = {}) => {
    try {
      const response = await api.get('/packages', { params });
      return response.data;
    } catch (error) {
      console.error('Error fetching packages:', error);
      throw error;
    }
  },

  /**
   * Get all packages with pagination
   */
  getAllPackages: async (page = 1, perPage = 20) => {
    try {
      const response = await api.get('/packages', {
        params: { page, per_page: perPage }
      });
      return response.data;
    } catch (error) {
      console.error('Error fetching all packages:', error);
      throw error;
    }
  },

  /**
   * Get package by ID
   */
  getPackageById: async (id) => {
    try {
      const response = await api.get(`/packages/${id}`);
      return response.data;
    } catch (error) {
      console.error('Error fetching package:', error);
      throw error;
    }
  },

  /**
   * Get VIP packages
   */
  getVIPPackages: async (limit = 6) => {
    try {
      const response = await api.get('/packages/vip', { params: { limit } });
      return response.data;
    } catch (error) {
      console.error('Error fetching VIP packages:', error);
      throw error;
    }
  },

  /**
   * Get packages by type
   */
  getPackagesByType: async (type) => {
    try {
      const response = await api.get(`/packages/type/${type}`);
      return response.data;
    } catch (error) {
      console.error('Error fetching packages by type:', error);
      throw error;
    }
  },

  /**
   * Search packages
   */
  searchPackages: async (searchData) => {
    try {
      const response = await api.post('/packages/search', searchData);
      return response.data;
    } catch (error) {
      console.error('Error searching packages:', error);
      throw error;
    }
  },

  /**
   * Get available dates for package
   */
  getAvailableDates: async (id) => {
    try {
      const response = await api.get(`/packages/${id}/available-dates`);
      return response.data;
    } catch (error) {
      console.error('Error fetching available dates:', error);
      throw error;
    }
  },

  /**
   * Check package availability
   */
  checkAvailability: async (id, data) => {
    try {
      const response = await api.post(`/packages/${id}/check-availability`, data);
      return response.data;
    } catch (error) {
      console.error('Error checking availability:', error);
      throw error;
    }
  }
};

// ============================================
// CAROUSELS API
// ============================================
export const carouselService = {
  /**
   * Get active carousels
   */
  getActiveCarousels: async () => {
    try {
      const response = await api.get('/carousels');
      return response.data;
    } catch (error) {
      console.error('Error fetching carousels:', error);
      throw error;
    }
  }
};

// ============================================
// SETTINGS API
// ============================================
export const settingService = {
  /**
   * Get public settings
   */
  getPublicSettings: async () => {
    try {
      const response = await api.get('/settings');
      return response.data;
    } catch (error) {
      console.error('Error fetching settings:', error);
      throw error;
    }
  },

  /**
   * Get currencies
   */
  getCurrencies: async () => {
    try {
      const response = await api.get('/currencies');
      return response.data;
    } catch (error) {
      console.error('Error fetching currencies:', error);
      throw error;
    }
  }
};

// ============================================
// CART API
// ============================================
export const cartService = {
  /**
   * Add item to cart
   */
  addToCart: async (data) => {
    try {
      const response = await api.post('/cart/add', data);
      return response.data;
    } catch (error) {
      console.error('Error adding to cart:', error);
      throw error;
    }
  },

  /**
   * Get cart items
   */
  getCart: async (sessionId) => {
    try {
      const response = await api.get(`/cart/${sessionId}`);
      return response.data;
    } catch (error) {
      console.error('Error fetching cart:', error);
      throw error;
    }
  },

  /**
   * Update cart item
   */
  updateCartItem: async (id, data) => {
    try {
      const response = await api.put(`/cart/${id}`, data);
      return response.data;
    } catch (error) {
      console.error('Error updating cart item:', error);
      throw error;
    }
  },

  /**
   * Remove item from cart
   */
  removeFromCart: async (id) => {
    try {
      const response = await api.delete(`/cart/${id}`);
      return response.data;
    } catch (error) {
      console.error('Error removing from cart:', error);
      throw error;
    }
  },

  /**
   * Clear cart
   */
  clearCart: async (sessionId) => {
    try {
      const response = await api.delete(`/cart/session/${sessionId}`);
      return response.data;
    } catch (error) {
      console.error('Error clearing cart:', error);
      throw error;
    }
  }
};

// ============================================
// BOOKINGS API
// ============================================
export const bookingService = {
  /**
   * Create booking
   */
  /* createBooking: async (data) => {
    try {
      const response = await api.post('/bookings', data);
      return response.data;
    } catch (error) {
      console.error('Error creating booking:', error);
      throw error;
    }
  }, */

  createBooking: async (data) => {
    try {
      const response = await api.post('/flights/book', data);
      return response.data;
    } catch (error) {
      console.error('Error creating flight booking:', error);
      throw error;
    }
  },

  /**
   * Get booking by number
   */
  getBookingByNumber: async (bookingNumber) => {
    try {
      const response = await api.get(`/bookings/${bookingNumber}`);
      return response.data;
    } catch (error) {
      console.error('Error fetching booking:', error);
      throw error;
    }
  },

  /**
   * Get user's bookings (authenticated)
   */
  /* getMyBookings: async () => {
    try {
      const response = await api.get('/my-bookings');
      return response.data;
    } catch (error) {
      console.error('Error fetching my bookings:', error);
      throw error;
    }
  }, */

  getUserBookings: async () => {
    try {
      const response = await api.get('/flights/user-bookings');
      return response.data;
    } catch (error) {
      console.error('Error fetching user bookings:', error);
      throw error;
    }
  },

  /**
   * Get booking details (authenticated)
   */
  /* getBookingDetails: async (id) => {
    try {
      const response = await api.get(`/my-bookings/${id}`);
      return response.data;
    } catch (error) {
      console.error('Error fetching booking details:', error);
      throw error;
    }
  }, */

  getFlightBookingDetails: async (amadeusOrderId) => {
    try {
      const response = await api.get(`/flights/booking-details/${amadeusOrderId}`);
      return response.data;
    } catch (error) {
      console.error('Error fetching flight booking details:', error);
      throw error;
    }
  },

  /**
   * Cancel booking (authenticated)
   */
  /* cancelBooking: async (id, reason) => {
    try {
      const response = await api.post(`/my-bookings/${id}/cancel`, {
        cancellation_reason: reason
      });
      return response.data;
    } catch (error) {
      console.error('Error cancelling booking:', error);
      throw error;
    }
  } */

  cancelBooking: async (bookingId) => {
    try {
      const response = await api.delete(`/flights/booking/${bookingId}`);
      return response.data;
    } catch (error) {
      console.error('Error cancelling flight booking:', error);
      throw error;
    }
  },
};

// ============================================
// PAYMENTS API
// ============================================
export const paymentService = {
  /**
   * Get available payment methods
   */
  getPaymentMethods: async () => {
    try {
      const response = await api.get('/payments/methods');
      return response.data;
    } catch (error) {
      console.error('Error fetching payment methods:', error);
      throw error;
    }
  },

  /**
   * Initialize payment
   */
  initializePayment: async (data) => {
    try {
      const response = await api.post('/payments/initialize', data);
      return response.data;
    } catch (error) {
      console.error('Error initializing payment:', error);
      throw error;
    }
  },

  /**
   * Confirm payment (Stripe)
   */
  confirmPayment: async (data) => {
    try {
      const response = await api.post('/payments/confirm', data);
      return response.data;
    } catch (error) {
      console.error('Error confirming payment:', error);
      throw error;
    }
  },

  /**
   * Check payment status (Mobile Money)
   */
  checkPaymentStatus: async (data) => {
    try {
      const response = await api.post('/payments/check-status', data);
      return response.data;
    } catch (error) {
      console.error('Error checking payment status:', error);
      throw error;
    }
  },

  /**
   * Get payment history for a booking
   */
  getPaymentHistory: async (bookingId) => {
    try {
      const response = await api.get(`/payments/history/${bookingId}`);
      return response.data;
    } catch (error) {
      console.error('Error fetching payment history:', error);  
      throw error;
    }
  }
};

// Export l'instance axios pour utilisation directe si nécessaire
export default api;
