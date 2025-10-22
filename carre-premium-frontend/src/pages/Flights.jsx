import React, { useState } from 'react';
import { flightService } from '../services/api';
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

  const [filteredFlights, setFilteredFlights] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [airports, setAirports] = useState([]);
  const [showFromSuggestions, setShowFromSuggestions] = useState(false);
  const [showToSuggestions, setShowToSuggestions] = useState(false);

  // Recherche des aéroports
  /* const searchAirports = async (keyword, type) => {
    if (keyword.length < 2) {
      if (type === 'from') setShowFromSuggestions(false);
      if (type === 'to') setShowToSuggestions(false);
      return;
    }

    try {
      const response = await flightService.getAirports(keyword);
      const airportList = response || [];
      setAirports(airportList);
      if (type === 'from') setShowFromSuggestions(true);
      if (type === 'to') setShowToSuggestions(true);
    } catch (err) {
      console.error('Erreur recherche aéroports:', err);
    }
  }; */

  const searchAirports = async (keyword, type) => {
    if (keyword.length < 3) {  // ✅ Minimum 3 caractères
      if (type === 'from') setShowFromSuggestions(false);
      if (type === 'to') setShowToSuggestions(false);
      setAirports([]);
      return;
    }

    try {
      const response = await flightService.getAirports(keyword);
      const airportList = response || []; // ✅ Pas besoin de .data ici
      console.log(`🛫 Aéroports trouvés pour "${keyword}":`, airportList);
      setAirports(airportList);
      if (type === 'from') setShowFromSuggestions(airportList.length > 0);
      if (type === 'to') setShowToSuggestions(airportList.length > 0);
    } catch (err) {
      console.error('Erreur recherche aéroports:', err);
      setAirports([]);
    }
  };


  const formatFlight = (flightOffer, index) => {
    const firstSegment = flightOffer.itineraries[0].segments[0];
    const lastSegment = flightOffer.itineraries[0].segments.slice(-1)[0];
    return {
      id: flightOffer.id || `flight-${index}`,
      airline: flightOffer.validatingAirlineCodes?.[0] || 'Airline',
      logo: '✈️',
      from: firstSegment.departure.iataCode,
      to: lastSegment.arrival.iataCode,
      departure: new Date(firstSegment.departure.at).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }),
      arrival: new Date(lastSegment.arrival.at).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }),
      duration: flightOffer.itineraries[0].duration.replace('PT', '').toLowerCase(),
      date: firstSegment.departure.at.split('T')[0],
      stops: flightOffer.itineraries[0].segments.length === 1 ? 'Direct' : `${flightOffer.itineraries[0].segments.length - 1} escale(s)`,
      economyPrice: Math.round(parseFloat(flightOffer.price.total) * 100),
      businessPrice: Math.round(parseFloat(flightOffer.price.total) * 100 * 1.5),
      firstClassPrice: Math.round(parseFloat(flightOffer.price.total) * 100 * 2),
      availableSeats: flightOffer.numberOfBookableSeats || 9,
      flightOffer: flightOffer
    };
  };

  const getPrice = (flight) => {
    switch (filters.class) {
      case 'business': return flight.businessPrice;
      case 'first': return flight.firstClassPrice;
      default: return flight.economyPrice;
    }
  };

  const handleAddToCart = async (flight) => {
    try {
      // Ici on suppose que flightService.confirmPrice existe côté backend
      const confirmedOffer = await flightService.confirmPrice(flight.flightOffer);
      addToCart({
        id: flight.id,
        type: 'flight',
        name: `${flight.from} → ${flight.to}`,
        airline: flight.airline,
        date: flight.date,
        departure: flight.departure,
        class: filters.class,
        passengers: filters.passengers,
        price: getPrice(flight),
        flightOffer: confirmedOffer?.data || confirmedOffer
      });
    } catch (err) {
      console.error('Erreur confirmation prix:', err);
      setError('Erreur lors de la confirmation du prix. Veuillez réessayer.');
    }
  };

  const handleSearch = async (e) => {
    e.preventDefault();
    setError(null);
    setIsSearching(true);
    if (onLoading) onLoading(true);

    try {
      // ✅ Codes IATA sécurisés
      const originCode = selectedOrigin?.iataCode?.toUpperCase() ?? '';
      const destinationCode = selectedDestination?.iataCode?.toUpperCase() ?? '';

      if (originCode.length !== 3 || destinationCode.length !== 3) {
        setError('Veuillez sélectionner un aéroport de départ et d\'arrivée valide dans la liste.');
        return;
      }

      // ✅ Dates
      const departureDate = searchParams.departureDate;
      const returnDate = searchParams.returnDate;

      if (!departureDate) {
        setError('Veuillez sélectionner une date de départ valide.');
        return;
      }
      if (tripType === 'roundtrip' && (!returnDate || returnDate < departureDate)) {
        setError('Veuillez sélectionner une date de retour valide.');
        return;
      }

      // ✅ Passagers
      const adults = Number(searchParams.adults);
      const children = Number(searchParams.children || 0);
      const infants = Number(searchParams.infants || 0);

      if (adults < 1) {
        setError('Au moins un adulte doit être sélectionné.');
        return;
      }

      // ✅ Classe
      const validClasses = ['ECONOMY', 'PREMIUM_ECONOMY', 'BUSINESS', 'FIRST'];
      const travelClass = (searchParams.travelClass || 'ECONOMY').toUpperCase();
      if (!validClasses.includes(travelClass)) {
        setError('Veuillez sélectionner une classe de voyage valide.');
        return;
      }

      // ✅ Construction du payload
      const requestPayload = {
        origin: originCode,
        destination: destinationCode,
        departureDate,
        adults,
        children,
        infants,
        travelClass,
        nonStop: !!searchParams.nonStop,
        currencyCode: currency.code,
        ...(tripType === 'roundtrip' && { returnDate })
      };

      // 🔍 Logs pour debug dans VS Code
      console.log('--- Recherche de vols ---');
      console.log('Payload envoyé à l’API:', requestPayload);

      const results = await flightService.searchFlights(requestPayload);

      console.log('Résultats reçus:', results);
      if (onSearchResults) onSearchResults(results);

    } catch (error) {
      console.error('Erreur recherche vols:', error);
      console.log('Voici les erreurs ', error)

      // Récupération du message depuis la réponse de l'API
      const errorMessage = error.response?.data?.message ||
        error.response?.data?.error ||
        'Erreur lors de la recherche de vols. Veuillez réessayer.';
      setError(errorMessage);
    } finally {
      setIsSearching(false);
      if (onLoading) onLoading(false);
    }
  };

  const handleAirportSearch = async (keyword) => {
    try {
      const airports = await flightService.getAirports(keyword);
      console.log('Aéroports trouvés:', airports);
      // Mettre à jour l'état de l'application avec les résultats
      return airports;
    } catch (error) {
      console.error('Erreur lors de la recherche d\'aéroports via flightService:', error);
      // Gérer l'erreur (afficher un message à l'utilisateur)
      throw error;
    }
  };



  // Fonction pour sélectionner un aéroport
  const handleAirportSelect = (airport, type) => {
    const airportText = `${airport.name} (${airport.iataCode})`;

    if (type === 'from') {
      setFilters({ ...filters, from: airportText });
      setShowFromSuggestions(false);
    } else if (type === 'to') {
      setFilters({ ...filters, to: airportText });
      setShowToSuggestions(false);
    }
  };

  // Ici le JSX reste identique à ton code actuel
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
            {/* Départ */}
            <div className="relative">
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Départ *
              </label>
              <input
                type="text"
                placeholder="Ex: Paris (CDG)"
                value={filters.from}
                onChange={(e) => {
                  setFilters({ ...filters, from: e.target.value });
                  searchAirports(e.target.value, 'from');
                }}
                onFocus={() => filters.from && searchAirports(filters.from, 'from')}
                onBlur={() => setTimeout(() => setShowFromSuggestions(false), 200)}
                className="input"
              />
              {showFromSuggestions && airports.length > 0 && (
                <div className="absolute z-20 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-auto">
                  {airports.map((airport) => (
                    <div
                      key={airport.iataCode}
                      className="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer"
                      onClick={() => handleAirportSelect(airport, 'from')}
                    >
                      <div className="font-medium">{airport.name}</div>
                      <div className="text-sm text-gray-600 dark:text-gray-400">
                        {airport.iataCode} - {airport.address.cityName}
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>

            {/* Arrivée */}
            <div className="relative">
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Arrivée *
              </label>
              <input
                type="text"
                placeholder="Ex: New York (JFK)"
                value={filters.to}
                onChange={(e) => {
                  setFilters({ ...filters, to: e.target.value });
                  searchAirports(e.target.value, 'to');
                }}
                onFocus={() => filters.to && searchAirports(filters.to, 'to')}
                onBlur={() => setTimeout(() => setShowToSuggestions(false), 200)}
                className="input"
              />
              {showToSuggestions && airports.length > 0 && (
                <div className="absolute z-20 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-auto">
                  {airports.map((airport) => (
                    <div
                      key={airport.iataCode}
                      className="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer"
                      onClick={() => handleAirportSelect(airport, 'to')}
                    >
                      <div className="font-medium">{airport.name}</div>
                      <div className="text-sm text-gray-600 dark:text-gray-400">
                        {airport.iataCode} - {airport.address.cityName}
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>

            {/* Date */}
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Date *
              </label>
              <input
                type="date"
                min={new Date().toISOString().split('T')[0]}
                value={filters.date}
                onChange={(e) => setFilters({ ...filters, date: e.target.value })}
                className="input"
              />
            </div>

            {/* Classe */}
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Classe
              </label>
              <select
                value={filters.class}
                onChange={(e) => setFilters({ ...filters, class: e.target.value })}
                className="input"
              >
                <option value="economy">Économique</option>
                <option value="business">Affaires</option>
                <option value="first">Première</option>
              </select>
            </div>

            {/* Passagers */}
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Passagers
              </label>
              <input
                type="number"
                min="1"
                max="9"
                value={filters.passengers}
                onChange={(e) => setFilters({ ...filters, passengers: parseInt(e.target.value) || 1 })}
                className="input"
              />
            </div>
          </div>

          {/* Message d'erreur */}
          {error && (
            <div className="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
              {error}
            </div>
          )}

          {/* Bouton Rechercher */}
          <div className="mt-4 text-right">
            <button
              onClick={handleSearch}
              disabled={loading}
              className="btn btn-primary disabled:opacity-50"
            >
              {loading ? 'Recherche en cours...' : 'Rechercher'}
            </button>
          </div>
        </div>
      </section>

      {/* Results */}
      <section className="section">
        <div className="container-custom">
          <div className="flex justify-between items-center mb-8">
            <h2 className="text-2xl font-montserrat font-bold text-gray-800 dark:text-white">
              {loading ? 'Recherche en cours...' : `${filteredFlights.length} vols trouvés`}
            </h2>
          </div>

          <div className="space-y-6">
            {loading ? (
              <div className="text-center py-8">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto"></div>
                <p className="mt-4 text-gray-600 dark:text-gray-400">Recherche de vols en cours...</p>
              </div>
            ) : filteredFlights.length > 0 ? (
              filteredFlights.map((flight) => (
                <div key={flight.id} className="card hover-lift">
                  <div className="flex flex-col md:flex-row gap-6 p-6">
                    {/* Airline Info */}
                    <div className="flex items-center gap-4 md:w-1/4">
                      <div className="text-4xl">{flight.logo}</div>
                      <div>
                        <h3 className="font-bold text-gray-800 dark:text-white">
                          {flight.airline}
                        </h3>
                        <p className="text-sm text-gray-600 dark:text-gray-400">
                          {flight.stops}
                        </p>
                      </div>
                    </div>

                    {/* Flight Details */}
                    <div className="flex-1 md:w-2/4">
                      <div className="flex items-center justify-between">
                        <div className="text-center">
                          <p className="text-2xl font-bold text-gray-800 dark:text-white">
                            {flight.departure}
                          </p>
                          <p className="text-sm text-gray-600 dark:text-gray-400">
                            {flight.from}
                          </p>
                        </div>
                        <div className="flex-1 px-4">
                          <div className="border-t-2 border-gray-300 dark:border-gray-600 relative">
                            <span className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 px-2 text-sm text-gray-600 dark:text-gray-400">
                              {flight.duration}
                            </span>
                          </div>
                        </div>
                        <div className="text-center">
                          <p className="text-2xl font-bold text-gray-800 dark:text-white">
                            {flight.arrival}
                          </p>
                          <p className="text-sm text-gray-600 dark:text-gray-400">
                            {flight.to}
                          </p>
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
                        <p className="text-sm text-gray-600 dark:text-gray-400">
                          À partir de
                        </p>
                        <p className="text-3xl font-bold text-primary">
                          {formatPrice(getPrice(flight))}
                        </p>
                        <p className="text-xs text-gray-500 dark:text-gray-400">
                          par personne
                        </p>
                      </div>
                      <div className="flex gap-2 mt-4">
                        <Link
                          to={`/flights/${flight.id}`}
                          state={{ flight }}
                          className="btn btn-outline"
                        >
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
              ))
            ) : (
              !loading && (
                <p className="text-gray-600 dark:text-gray-400 text-center py-8">
                  Aucun vol ne correspond à vos critères. Veuillez modifier votre recherche.
                </p>
              )
            )}
          </div>
        </div>
      </section>
    </div>
  );
};

export default Flights;
