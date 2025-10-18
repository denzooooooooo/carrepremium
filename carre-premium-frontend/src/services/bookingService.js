import axios from 'axios';

const API_URL = 'http://localhost:8000/api/v1';

/**
 * Service de gestion des réservations utilisateur
 * Permet de récupérer, gérer et télécharger les documents de réservation
 */
export const bookingService = {
  /**
   * Obtenir la liste des réservations de l'utilisateur
   * @param {number} page - Numéro de page
   * @returns {Promise} Liste paginée des réservations
   */
  getMyBookings: async (page = 1) => {
    try {
      const response = await axios.get(`${API_URL}/user/bookings?page=${page}`);
      return response.data;
    } catch (error) {
      console.error('Erreur lors de la récupération des réservations:', error);
      throw error;
    }
  },

  /**
   * Obtenir les détails d'une réservation
   * @param {number} id - ID de la réservation
   * @returns {Promise} Détails de la réservation
   */
  getBookingDetails: async (id) => {
    try {
      const response = await axios.get(`${API_URL}/user/bookings/${id}`);
      return response.data;
    } catch (error) {
      console.error('Erreur lors de la récupération des détails:', error);
      throw error;
    }
  },

  /**
   * Télécharger le reçu de paiement (PDF)
   * @param {number} id - ID de la réservation
   * @param {string} bookingNumber - Numéro de réservation
   * @returns {Promise} Blob du PDF
   */
  downloadReceipt: async (id, bookingNumber) => {
    try {
      const response = await axios.get(`${API_URL}/user/bookings/${id}/receipt`, {
        responseType: 'blob'
      });
      
      // Créer un lien de téléchargement
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', `recu_${bookingNumber}.pdf`);
      document.body.appendChild(link);
      link.click();
      link.remove();
      window.URL.revokeObjectURL(url);
      
      return { success: true };
    } catch (error) {
      console.error('Erreur lors du téléchargement du reçu:', error);
      return { success: false, message: error.response?.data?.message || 'Erreur de téléchargement' };
    }
  },

  /**
   * Télécharger le billet (e-ticket) (PDF)
   * @param {number} id - ID de la réservation
   * @param {string} bookingNumber - Numéro de réservation
   * @returns {Promise} Blob du PDF
   */
  downloadTicket: async (id, bookingNumber) => {
    try {
      const response = await axios.get(`${API_URL}/user/bookings/${id}/ticket`, {
        responseType: 'blob'
      });
      
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', `billet_${bookingNumber}.pdf`);
      document.body.appendChild(link);
      link.click();
      link.remove();
      window.URL.revokeObjectURL(url);
      
      return { success: true };
    } catch (error) {
      console.error('Erreur lors du téléchargement du billet:', error);
      return { success: false, message: error.response?.data?.message || 'Erreur de téléchargement' };
    }
  },

  /**
   * Télécharger la confirmation de réservation (PDF)
   * @param {number} id - ID de la réservation
   * @param {string} bookingNumber - Numéro de réservation
   * @returns {Promise} Blob du PDF
   */
  downloadConfirmation: async (id, bookingNumber) => {
    try {
      const response = await axios.get(`${API_URL}/user/bookings/${id}/confirmation`, {
        responseType: 'blob'
      });
      
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', `confirmation_${bookingNumber}.pdf`);
      document.body.appendChild(link);
      link.click();
      link.remove();
      window.URL.revokeObjectURL(url);
      
      return { success: true };
    } catch (error) {
      console.error('Erreur lors du téléchargement de la confirmation:', error);
      return { success: false, message: error.response?.data?.message || 'Erreur de téléchargement' };
    }
  },

  /**
   * Annuler une réservation
   * @param {number} id - ID de la réservation
   * @param {string} reason - Raison de l'annulation
   * @returns {Promise} Résultat de l'annulation
   */
  cancelBooking: async (id, reason = '') => {
    try {
      const response = await axios.post(`${API_URL}/user/bookings/${id}/cancel`, {
        reason
      });
      return response.data;
    } catch (error) {
      console.error('Erreur lors de l\'annulation:', error);
      throw error;
    }
  },

  /**
   * Obtenir les statistiques de l'utilisateur
   * @returns {Promise} Statistiques
   */
  getStatistics: async () => {
    try {
      const response = await axios.get(`${API_URL}/user/statistics`);
      return response.data;
    } catch (error) {
      console.error('Erreur lors de la récupération des statistiques:', error);
      throw error;
    }
  },

  /**
   * Formater le statut de réservation pour l'affichage
   * @param {string} status - Statut de la réservation
   * @returns {object} Objet avec label et couleur
   */
  formatStatus: (status) => {
    const statusMap = {
      'pending': { label: 'En attente', color: 'yellow', bgColor: 'bg-yellow-100', textColor: 'text-yellow-700' },
      'confirmed': { label: 'Confirmée', color: 'green', bgColor: 'bg-green-100', textColor: 'text-green-700' },
      'cancelled': { label: 'Annulée', color: 'red', bgColor: 'bg-red-100', textColor: 'text-red-700' },
      'completed': { label: 'Complétée', color: 'blue', bgColor: 'bg-blue-100', textColor: 'text-blue-700' },
      'refunded': { label: 'Remboursée', color: 'purple', bgColor: 'bg-purple-100', textColor: 'text-purple-700' }
    };
    return statusMap[status] || { label: status, color: 'gray', bgColor: 'bg-gray-100', textColor: 'text-gray-700' };
  },

  /**
   * Formater le type de réservation
   * @param {string} type - Type de réservation
   * @returns {object} Objet avec label et icône
   */
  formatType: (type) => {
    const typeMap = {
      'flight': { label: 'Vol', icon: 'fa-plane', color: 'text-blue-600' },
      'event': { label: 'Événement', icon: 'fa-ticket-alt', color: 'text-purple-600' },
      'package': { label: 'Package', icon: 'fa-suitcase', color: 'text-amber-600' }
    };
    return typeMap[type] || { label: type, icon: 'fa-question', color: 'text-gray-600' };
  }
};

export default bookingService;
