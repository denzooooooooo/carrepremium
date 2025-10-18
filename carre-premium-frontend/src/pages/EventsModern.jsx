import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useCurrency } from '../contexts/CurrencyContext';
import { useCart } from '../contexts/CartContext';
import { eventService } from '../services/api';

const EventsModern = () => {
  const { formatPrice } = useCurrency();
  const { addToCart } = useCart();

  const [events, setEvents] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [filters, setFilters] = useState({
    search: '',
    category: 'all',
    date: '',
    location: '',
    sortBy: 'date_asc'
  });

  // SUPPRIMÉ: Données statiques - on utilise uniquement l'API maintenant
  const defaultEvents_OLD_REMOVED = [
    {
      id: 1,
      title_fr: "Roland Garros 2025",
      slug: "roland-garros-2025",
      event_type: "sport",
      sport_type: "tennis",
      venue_name: "Stade Roland Garros",
      city: "Paris",
      country: "France",
      event_date: "2025-05-25",
      event_time: "11:00",
      image: "https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=800&h=600&fit=crop",
      min_price: 250000,
      max_price: 1500000,
      available_seats: 150,
      is_featured: true,
      description_fr: "Assistez au tournoi de tennis le plus prestigieux au monde",
      category: { name_fr: "Tennis" }
    },
    {
      id: 2,
      title_fr: "Finale Champions League 2025",
      slug: "champions-league-finale-2025",
      event_type: "sport",
      sport_type: "football",
      venue_name: "Allianz Arena",
      city: "Munich",
      country: "Allemagne",
      event_date: "2025-06-07",
      event_time: "21:00",
      image: "https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800&h=600&fit=crop",
      min_price: 450000,
      max_price: 2500000,
      available_seats: 200,
      is_featured: true,
      description_fr: "La finale la plus attendue du football européen",
      category: { name_fr: "Football" }
    },
    {
      id: 3,
      title_fr: "Grand Prix de Monaco F1",
      slug: "f1-monaco-2025",
      event_type: "sport",
      sport_type: "formula1",
      venue_name: "Circuit de Monaco",
      city: "Monaco",
      country: "Monaco",
      event_date: "2025-05-23",
      event_time: "15:00",
      image: "https://images.unsplash.com/photo-1541443131876-44b03de101c5?w=800&h=600&fit=crop",
      min_price: 800000,
      max_price: 5000000,
      available_seats: 80,
      is_featured: true,
      description_fr: "Le circuit le plus mythique de la Formule 1",
      category: { name_fr: "Formule 1" }
    },
    {
      id: 4,
      title_fr: "Concert Burna Boy - Abidjan",
      slug: "burna-boy-abidjan-2025",
      event_type: "concert",
      venue_name: "Palais de la Culture",
      city: "Abidjan",
      country: "Côte d'Ivoire",
      event_date: "2025-07-15",
      event_time: "20:00",
      image: "https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&h=600&fit=crop",
      min_price: 50000,
      max_price: 300000,
      available_seats: 500,
      is_featured: false,
      description_fr: "Le roi de l'Afrobeat en concert exceptionnel",
      category: { name_fr: "Concert" }
    },
    {
      id: 5,
      title_fr: "Wimbledon 2025",
      slug: "wimbledon-2025",
      event_type: "sport",
      sport_type: "tennis",
      venue_name: "All England Club",
      city: "Londres",
      country: "Royaume-Uni",
      event_date: "2025-06-28",
      event_time: "14:00",
      image: "https://images.unsplash.com/photo-1622163642998-1ea32b0bbc67?w=800&h=600&fit=crop",
      min_price: 300000,
      max_price: 1800000,
      available_seats: 120,
      is_featured: true,
      description_fr: "Le tournoi de tennis sur gazon le plus prestigieux",
      category: { name_fr: "Tennis" }
    },
    {
      id: 6,
      title_fr: "Festival Coachella 2025",
      slug: "coachella-2025",
      event_type: "festival",
      venue_name: "Empire Polo Club",
      city: "Indio",
      country: "États-Unis",
      event_date: "2025-04-11",
      event_time: "12:00",
      image: "https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800&h=600&fit=crop",
      min_price: 400000,
      max_price: 2000000,
      available_seats: 300,
      is_featured: false,
      description_fr: "Le festival de musique le plus iconique au monde",
      category: { name_fr: "Festival" }
    },
    {
      id: 7,
      title_fr: "CAN 2025 - Finale",
      slug: "can-2025-finale",
      event_type: "sport",
      sport_type: "football",
      venue_name: "Stade Olympique",
      city: "Abidjan",
      country: "Côte d'Ivoire",
      event_date: "2025-02-06",
      event_time: "20:00",
      image: "https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=800&h=600&fit=crop",
      min_price: 75000,
      max_price: 500000,
      available_seats: 600,
      is_featured: true,
      description_fr: "La finale de la Coupe d'Afrique des Nations",
      category: { name_fr: "Football" }
    },
    {
      id: 8,
      title_fr: "Concert Beyoncé - Paris",
      slug: "beyonce-paris-2025",
      event_type: "concert",
      venue_name: "Stade de France",
      city: "Paris",
      country: "France",
      event_date: "2025-06-20",
      event_time: "20:30",
      image: "https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?w=800&h=600&fit=crop",
      min_price: 150000,
      max_price: 800000,
      available_seats: 400,
      is_featured: true,
      description_fr: "Queen B en concert exceptionnel à Paris",
      category: { name_fr: "Concert" }
    },
    {
      id: 9,
      title_fr: "US Open Tennis 2025",
      slug: "us-open-2025",
      event_type: "sport",
      sport_type: "tennis",
      venue_name: "USTA Billie Jean King National Tennis Center",
      city: "New York",
      country: "États-Unis",
      event_date: "2025-08-25",
      event_time: "19:00",
      image: "https://images.unsplash.com/photo-1587280501635-68a0e82cd5ff?w=800&h=600&fit=crop",
      min_price: 350000,
      max_price: 2000000,
      available_seats: 100,
      is_featured: false,
      description_fr: "Le dernier Grand Chelem de la saison",
      category: { name_fr: "Tennis" }
    }
  ];

  const categories = [
    { value: 'all', label: 'Tous les événements', icon: '🎯' },
    { value: 'sport', label: 'Sports', icon: '🏆' },
    { value: 'concert', label: 'Concerts', icon: '🎵' },
    { value: 'festival', label: 'Festivals', icon: '🎪' },
    { value: 'theater', label: 'Théâtre', icon: '🎭' }
  ];

  useEffect(() => {
    const fetchEvents = async () => {
      try {
        setLoading(true);
        setError(null);
        console.log('🔄 Chargement des événements depuis l\'API...');
        const response = await eventService.getEvents({ page: 1, per_page: 20 });
        console.log('✅ Réponse API reçue:', response);
        
        if (response.success && response.data && response.data.data) {
          console.log('✅ Événements chargés:', response.data.data.length);
          setEvents(response.data.data);
        } else {
          console.error('❌ Format de réponse invalide:', response);
          setError('Format de réponse API invalide');
          setEvents([]);
        }
      } catch (error) {
        console.error('❌ Erreur lors du chargement des événements:', error);
        setError(error.message || 'Erreur de connexion à l\'API');
        setEvents([]);
      } finally {
        setLoading(false);
      }
    };

    fetchEvents();
  }, []);

  const handleAddToCart = (event) => {
    addToCart({
      id: event.id,
      type: 'event',
      name: event.title_fr,
      venue: event.venue_name,
      date: event.event_date,
      time: event.event_time,
      location: `${event.city}, ${event.country}`,
      price: event.min_price,
      image: event.image
    });
  };

  const filteredEvents = events.filter(event => {
    if (filters.search && !event.title_fr.toLowerCase().includes(filters.search.toLowerCase())) return false;
    if (filters.category !== 'all' && event.event_type !== filters.category) return false;
    if (filters.location && !event.city.toLowerCase().includes(filters.location.toLowerCase())) return false;
    if (filters.date && event.event_date !== filters.date) return false;
    return true;
  });

  const sortedEvents = [...filteredEvents].sort((a, b) => {
    switch(filters.sortBy) {
      case 'price_asc':
        return a.min_price - b.min_price;
      case 'price_desc':
        return b.min_price - a.min_price;
      case 'date_asc':
        return new Date(a.event_date) - new Date(b.event_date);
      case 'date_desc':
        return new Date(b.event_date) - new Date(a.event_date);
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
            backgroundImage: `url('https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=1920&h=1080&fit=crop')`,
          }}
        >
          <div className="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/70"></div>
        </div>

        <div className="relative z-10 container-custom px-4 text-center">
          <h1 className="text-5xl md:text-7xl font-black text-white mb-6 leading-tight">
            Événements<br />
            <span className="bg-gradient-to-r from-amber-400 to-pink-400 bg-clip-text text-transparent">Exceptionnels</span>
          </h1>
          <p className="text-xl text-white/90 max-w-2xl mx-auto">
            Accédez aux plus grands événements sportifs et culturels du monde
          </p>
        </div>
      </section>

      {/* Categories Filter */}
      <section className="bg-white dark:bg-gray-800 shadow-2xl -mt-16 relative z-20">
        <div className="container-custom py-6">
          <div className="flex flex-wrap gap-3 justify-center">
            {categories.map((cat) => (
              <button
                key={cat.value}
                onClick={() => setFilters({...filters, category: cat.value})}
                className={`px-6 py-3 rounded-full font-bold transition-all duration-300 flex items-center space-x-2 ${
                  filters.category === cat.value
                    ? 'bg-gradient-to-r from-purple-600 to-amber-600 text-white shadow-lg scale-105'
                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                }`}
              >
                <span>{cat.icon}</span>
                <span>{cat.label}</span>
              </button>
            ))}
          </div>
        </div>
      </section>

      {/* Search & Filters */}
      <section className="py-8 bg-gray-50 dark:bg-gray-900">
        <div className="container-custom">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div className="md:col-span-2">
              <input
                type="text"
                placeholder="Rechercher un événement..."
                value={filters.search}
                onChange={(e) => setFilters({...filters, search: e.target.value})}
                className="w-full px-6 py-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-purple-600 focus:outline-none transition-colors"
              />
            </div>
            <div>
              <input
                type="text"
                placeholder="Ville..."
                value={filters.location}
                onChange={(e) => setFilters({...filters, location: e.target.value})}
                className="w-full px-6 py-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-purple-600 focus:outline-none transition-colors"
              />
            </div>
            <div>
              <input
                type="date"
                value={filters.date}
                onChange={(e) => setFilters({...filters, date: e.target.value})}
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
              <h2 className="text-3xl font-black text-white mb-2">
                {sortedEvents.length} Événements Disponibles
              </h2>
              <p className="text-gray-300">
                Trouvez l'événement parfait pour vous
              </p>
            </div>
            <select
              value={filters.sortBy}
              onChange={(e) => setFilters({...filters, sortBy: e.target.value})}
              className="px-4 py-3 rounded-xl border-2 border-gray-700 bg-gray-800 text-white focus:border-amber-500 focus:outline-none transition-colors"
            >
              <option value="date_asc">Date (proche)</option>
              <option value="date_desc">Date (éloignée)</option>
              <option value="price_asc">Prix croissant</option>
              <option value="price_desc">Prix décroissant</option>
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
              {sortedEvents.map((event) => (
                <div
                  key={event.id}
                  className="group bg-white dark:bg-gray-800 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl hover:shadow-purple-500/20 transition-all duration-500 hover:-translate-y-2"
                >
                  <div className="grid md:grid-cols-3 gap-6">
                    {/* Image */}
                    <div className="relative h-64 md:h-auto overflow-hidden">
                      <img
                        src={event.image}
                        alt={event.title_fr}
                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                      />
                      <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                      
                      {/* Badges */}
                      <div className="absolute top-4 left-4 px-4 py-2 bg-gradient-to-r from-amber-500 to-pink-500 rounded-full text-xs font-black uppercase text-white shadow-xl">
                        {event.category?.name_fr || event.event_type}
                      </div>
                      
                      {event.is_featured && (
                        <div className="absolute top-4 right-4 px-3 py-1 bg-red-500 text-white text-xs font-black rounded-full shadow-xl animate-pulse">
                          🔥 HOT
                        </div>
                      )}
                    </div>

                    {/* Event Details */}
                    <div className="p-6 md:col-span-2">
                      <div className="flex items-start justify-between mb-4">
                        <div className="flex-1">
                          <h3 className="text-3xl font-black text-gray-900 dark:text-white mb-2">
                            {event.title_fr}
                          </h3>
                          <p className="text-gray-600 dark:text-gray-400 line-clamp-2">
                            {event.description_fr}
                          </p>
                        </div>
                      </div>

                      <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div className="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                          <svg className="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clipRule="evenodd" />
                          </svg>
                          <span className="font-semibold">{new Date(event.event_date).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' })}</span>
                        </div>
                        <div className="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                          <svg className="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clipRule="evenodd" />
                          </svg>
                          <span className="font-semibold">{event.event_time}</span>
                        </div>
                        <div className="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                          <svg className="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clipRule="evenodd" />
                          </svg>
                          <span className="font-semibold">{event.city}</span>
                        </div>
                        <div className="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                          <svg className="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                          </svg>
                          <span className="font-semibold">{event.available_seats} places</span>
                        </div>
                      </div>

                      <div className="flex items-center justify-between">
                        <div>
                          <p className="text-sm text-gray-600 dark:text-gray-400 mb-1">À partir de</p>
                          <p className="text-4xl font-black bg-gradient-to-r from-amber-600 to-pink-600 bg-clip-text text-transparent">
                            {formatPrice(event.min_price)}
                          </p>
                        </div>

                        <div className="flex gap-3">
                          <Link
                            to={`/events/${event.id}`}
                            className="px-6 py-3 border-2 border-amber-600 text-amber-600 dark:text-amber-400 font-bold rounded-xl hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-all duration-300"
                          >
                            Détails
                          </Link>
                          <button
                            onClick={(e) => {
                              e.preventDefault();
                              handleAddToCart(event);
                            }}
                            className="px-6 py-3 bg-gradient-to-r from-amber-600 to-pink-600 text-white font-bold rounded-xl hover:shadow-2xl hover:shadow-amber-500/50 transition-all duration-300 flex items-center space-x-2"
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

          {/* Error State */}
          {error && (
            <div className="text-center py-16">
              <svg className="w-24 h-24 mx-auto text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 className="text-2xl font-bold text-white mb-2">
                Erreur de chargement
              </h3>
              <p className="text-red-400 mb-4">
                {error}
              </p>
              <button
                onClick={() => window.location.reload()}
                className="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700"
              >
                Réessayer
              </button>
            </div>
          )}

          {/* No Results */}
          {!loading && !error && sortedEvents.length === 0 && (
            <div className="text-center py-16">
              <svg className="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 className="text-2xl font-bold text-white mb-2">
                Aucun événement trouvé
              </h3>
              <p className="text-gray-400">
                Essayez de modifier vos critères de recherche
              </p>
            </div>
          )}
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-white dark:bg-gray-800">
        <div className="container-custom text-center">
          <h2 className="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-6">
            Vous ne trouvez pas l'événement que vous cherchez ?
          </h2>
          <p className="text-xl text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
            Contactez-nous et nous vous aiderons à trouver les meilleurs billets pour n'importe quel événement
          </p>
          <Link 
            to="/contact"
            className="inline-flex items-center px-10 py-5 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-full hover:scale-105 transition-transform shadow-xl space-x-2"
          >
            <span>CONTACTEZ-NOUS</span>
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </Link>
        </div>
      </section>
    </div>
  );
};

export default EventsModern;
