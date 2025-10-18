import React, { useState } from 'react';
import { useLanguage } from '../../contexts/LanguageContext';
import { useCurrency } from '../../contexts/CurrencyContext';
import amadeusService from '../../services/amadeusService';
import { 
  JetIcon, 
  CalendarIcon, 
  UsersIcon, 
  CheckCircleIcon,
  LocationIcon,
  ArrowRightIcon
} from '../icons/ServiceIcons';

const FlightSearch = ({ onSearchResults, onLoading }) => {
  const { t } = useLanguage();
  const { currency } = useCurrency();
  
  const [searchParams, setSearchParams] = useState({
    origin: '',
    destination: '',
    departureDate: '',
    returnDate: '',
    adults: 1,
    children: 0,
    infants: 0,
    travelClass: 'ECONOMY',
    nonStop: false
  });

  const [originSuggestions, setOriginSuggestions] = useState([]);
  const [destinationSuggestions, setDestinationSuggestions] = useState([]);
  const [showOriginSuggestions, setShowOriginSuggestions] = useState(false);
  const [showDestinationSuggestions, setShowDestinationSuggestions] = useState(false);
  const [isSearching, setIsSearching] = useState(false);
  const [error, setError] = useState(null);
  const [selectedOrigin, setSelectedOrigin] = useState(null);
  const [selectedDestination, setSelectedDestination] = useState(null);
  const [tripType, setTripType] = useState('roundtrip'); // roundtrip ou oneway

  // Aéroports populaires pour suggestions rapides
  const popularAirports = [
    { iataCode: 'ABJ', name: 'Félix Houphouët-Boigny', address: { cityName: 'Abidjan', countryName: 'Côte d\'Ivoire' } },
    { iataCode: 'CDG', name: 'Charles de Gaulle', address: { cityName: 'Paris', countryName: 'France' } },
    { iataCode: 'LHR', name: 'Heathrow', address: { cityName: 'Londres', countryName: 'Royaume-Uni' } },
    { iataCode: 'DXB', name: 'Dubai International', address: { cityName: 'Dubaï', countryName: 'EAU' } },
    { iataCode: 'JFK', name: 'John F. Kennedy', address: { cityName: 'New York', countryName: 'USA' } },
    { iataCode: 'IST', name: 'Istanbul', address: { cityName: 'Istanbul', countryName: 'Turquie' } },
    { iataCode: 'ADD', name: 'Addis Ababa Bole', address: { cityName: 'Addis Ababa', countryName: 'Éthiopie' } },
    { iataCode: 'DOH', name: 'Hamad International', address: { cityName: 'Doha', countryName: 'Qatar' } },
    { iataCode: 'FRA', name: 'Frankfurt', address: { cityName: 'Francfort', countryName: 'Allemagne' } },
    { iataCode: 'AMS', name: 'Schiphol', address: { cityName: 'Amsterdam', countryName: 'Pays-Bas' } },
    { iataCode: 'MAD', name: 'Adolfo Suárez Madrid-Barajas', address: { cityName: 'Madrid', countryName: 'Espagne' } },
    { iataCode: 'BCN', name: 'Barcelona-El Prat', address: { cityName: 'Barcelone', countryName: 'Espagne' } },
    { iataCode: 'FCO', name: 'Leonardo da Vinci-Fiumicino', address: { cityName: 'Rome', countryName: 'Italie' } },
    { iataCode: 'LOS', name: 'Murtala Muhammed', address: { cityName: 'Lagos', countryName: 'Nigeria' } },
    { iataCode: 'ACC', name: 'Kotoka International', address: { cityName: 'Accra', countryName: 'Ghana' } },
    { iataCode: 'DKR', name: 'Blaise Diagne', address: { cityName: 'Dakar', countryName: 'Sénégal' } },
    { iataCode: 'NBO', name: 'Jomo Kenyatta', address: { cityName: 'Nairobi', countryName: 'Kenya' } },
    { iataCode: 'CPT', name: 'Cape Town International', address: { cityName: 'Le Cap', countryName: 'Afrique du Sud' } },
    { iataCode: 'JNB', name: 'O.R. Tambo', address: { cityName: 'Johannesburg', countryName: 'Afrique du Sud' } },
    { iataCode: 'CAI', name: 'Cairo International', address: { cityName: 'Le Caire', countryName: 'Égypte' } }
  ];

  // Recherche d'aéroports
  const searchAirports = async (keyword, type) => {
    if (keyword.length < 2) {
      if (type === 'origin') setOriginSuggestions([]);
      else setDestinationSuggestions([]);
      return;
    }

    const searchTerm = keyword.toLowerCase();
    
    const localResults = popularAirports.filter(airport => 
      airport.iataCode.toLowerCase().includes(searchTerm) ||
      airport.name.toLowerCase().includes(searchTerm) ||
      airport.address.cityName.toLowerCase().includes(searchTerm) ||
      airport.address.countryName.toLowerCase().includes(searchTerm)
    );

    try {
      const response = await amadeusService.searchAirports(keyword);
      const apiResults = response.data?.data || [];
      
      const combinedResults = [...localResults];
      apiResults.forEach(apiAirport => {
        if (!combinedResults.find(a => a.iataCode === apiAirport.iataCode)) {
          combinedResults.push(apiAirport);
        }
      });
      
      if (type === 'origin') {
        setOriginSuggestions(combinedResults.slice(0, 10));
        setShowOriginSuggestions(true);
      } else {
        setDestinationSuggestions(combinedResults.slice(0, 10));
        setShowDestinationSuggestions(true);
      }
    } catch (error) {
      if (type === 'origin') {
        setOriginSuggestions(localResults.slice(0, 10));
        setShowOriginSuggestions(true);
      } else {
        setDestinationSuggestions(localResults.slice(0, 10));
        setShowDestinationSuggestions(true);
      }
    }
  };

  const selectAirport = (airport, type) => {
    const iataCode = airport.iataCode;
    const displayText = `${iataCode} - ${airport.address?.cityName || airport.name}`;
    
    if (type === 'origin') {
      setSelectedOrigin(airport);
      setSearchParams({ ...searchParams, origin: iataCode });
      setShowOriginSuggestions(false);
      document.getElementById('origin-input').value = displayText;
    } else {
      setSelectedDestination(airport);
      setSearchParams({ ...searchParams, destination: iataCode });
      setShowDestinationSuggestions(false);
      document.getElementById('destination-input').value = displayText;
    }
  };

  const handleSearch = async (e) => {
    e.preventDefault();
    setError(null);

    const originCode = searchParams.origin.trim().toUpperCase().substring(0, 3);
    const destinationCode = searchParams.destination.trim().toUpperCase().substring(0, 3);

    if (originCode.length !== 3 || destinationCode.length !== 3) {
      setError('Veuillez sélectionner un aéroport de départ et d\'arrivée valide dans la liste');
      return;
    }

    setIsSearching(true);
    if (onLoading) onLoading(true);

    try {
      const results = await amadeusService.searchFlights({
        origin: originCode,
        destination: destinationCode,
        departureDate: searchParams.departureDate,
        returnDate: tripType === 'roundtrip' ? searchParams.returnDate : undefined,
        adults: searchParams.adults,
        children: searchParams.children || 0,
        infants: searchParams.infants || 0,
        travelClass: searchParams.travelClass,
        nonStop: searchParams.nonStop,
        currencyCode: currency.code
      });

      if (onSearchResults) {
        onSearchResults(results);
      }
    } catch (error) {
      console.error('Search error:', error);
      const errorMessage = error.response?.data?.message || 
                          error.response?.data?.error ||
                          'Erreur lors de la recherche de vols. Veuillez réessayer.';
      setError(errorMessage);
    } finally {
      setIsSearching(false);
      if (onLoading) onLoading(false);
    }
  };

  return (
    <div className="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 md:p-10 border-2 border-purple-100 dark:border-purple-900">
      {/* En-tête du formulaire */}
      <div className="flex items-center justify-between mb-8">
        <div className="flex items-center space-x-4">
          <div className="w-16 h-16 bg-gradient-to-br from-purple-600 to-amber-600 rounded-2xl flex items-center justify-center shadow-xl">
            <JetIcon className="w-10 h-10 text-white" />
          </div>
          <div>
            <h2 className="text-3xl md:text-4xl font-black text-gray-900 dark:text-white">
              Rechercher un Vol
            </h2>
            <p className="text-gray-600 dark:text-gray-400 font-semibold">
              Trouvez les meilleurs tarifs en temps réel
            </p>
          </div>
        </div>
      </div>

      {/* Type de voyage */}
      <div className="flex space-x-4 mb-8">
        <button
          type="button"
          onClick={() => setTripType('roundtrip')}
          className={`flex-1 py-4 px-6 rounded-2xl font-bold transition-all duration-300 ${
            tripType === 'roundtrip'
              ? 'bg-gradient-to-r from-purple-600 to-amber-600 text-white shadow-xl scale-105'
              : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
          }`}
        >
          <div className="flex items-center justify-center space-x-2">
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
            <span>Aller-Retour</span>
          </div>
        </button>
        <button
          type="button"
          onClick={() => {
            setTripType('oneway');
            setSearchParams({ ...searchParams, returnDate: '' });
          }}
          className={`flex-1 py-4 px-6 rounded-2xl font-bold transition-all duration-300 ${
            tripType === 'oneway'
              ? 'bg-gradient-to-r from-purple-600 to-amber-600 text-white shadow-xl scale-105'
              : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
          }`}
        >
          <div className="flex items-center justify-center space-x-2">
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
            <span>Aller Simple</span>
          </div>
        </button>
      </div>

      {error && (
        <div className="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-5 rounded-xl mb-6 flex items-start space-x-3">
          <svg className="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
          </svg>
          <p className="text-red-700 dark:text-red-400 font-semibold">{error}</p>
        </div>
      )}

      <form onSubmit={handleSearch}>
        {/* Aéroports */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          {/* Origine */}
          <div className="relative">
            <label className="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
              <LocationIcon className="w-5 h-5 text-purple-600" />
              <span>Aéroport de Départ *</span>
            </label>
            <div className="relative">
              <input
                id="origin-input"
                type="text"
                defaultValue={searchParams.origin}
                onChange={(e) => {
                  const value = e.target.value;
                  setSearchParams({ ...searchParams, origin: value });
                  searchAirports(value, 'origin');
                }}
                onFocus={() => setShowOriginSuggestions(true)}
                onBlur={() => setTimeout(() => setShowOriginSuggestions(false), 200)}
                placeholder="Ex: ABJ - Abidjan"
                className="w-full pl-12 pr-4 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 dark:bg-gray-700 dark:text-white text-lg font-semibold transition-all"
                required
                autoComplete="off"
              />
              <div className="absolute left-4 top-1/2 transform -translate-y-1/2">
                <svg className="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
              </div>
            </div>
            
            {showOriginSuggestions && originSuggestions.length > 0 && (
              <div className="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border-2 border-purple-200 dark:border-purple-700 rounded-2xl shadow-2xl max-h-80 overflow-y-auto">
                {originSuggestions.map((airport, idx) => (
                  <div
                    key={idx}
                    onClick={() => selectAirport(airport, 'origin')}
                    className="px-5 py-4 hover:bg-purple-50 dark:hover:bg-purple-900/30 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-b-0 transition-colors"
                  >
                    <div className="flex items-center space-x-3">
                      <div className="w-12 h-12 bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span className="text-white font-black text-sm">{airport.iataCode}</span>
                      </div>
                      <div className="flex-1">
                        <div className="font-bold text-gray-900 dark:text-white text-lg">
                          {airport.name}
                        </div>
                        <div className="text-sm text-gray-600 dark:text-gray-400 font-semibold">
                          {airport.address?.cityName}, {airport.address?.countryName}
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>

          {/* Destination */}
          <div className="relative">
            <label className="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
              <LocationIcon className="w-5 h-5 text-amber-600" />
              <span>Aéroport d'Arrivée *</span>
            </label>
            <div className="relative">
              <input
                id="destination-input"
                type="text"
                defaultValue={searchParams.destination}
                onChange={(e) => {
                  const value = e.target.value;
                  setSearchParams({ ...searchParams, destination: value });
                  searchAirports(value, 'destination');
                }}
                onFocus={() => setShowDestinationSuggestions(true)}
                onBlur={() => setTimeout(() => setShowDestinationSuggestions(false), 200)}
                placeholder="Ex: CDG - Paris"
                className="w-full pl-12 pr-4 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-amber-500/50 focus:border-amber-500 dark:bg-gray-700 dark:text-white text-lg font-semibold transition-all"
                required
                autoComplete="off"
              />
              <div className="absolute left-4 top-1/2 transform -translate-y-1/2">
                <svg className="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </div>
            </div>
            
            {showDestinationSuggestions && destinationSuggestions.length > 0 && (
              <div className="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border-2 border-amber-200 dark:border-amber-700 rounded-2xl shadow-2xl max-h-80 overflow-y-auto">
                {destinationSuggestions.map((airport, idx) => (
                  <div
                    key={idx}
                    onClick={() => selectAirport(airport, 'destination')}
                    className="px-5 py-4 hover:bg-amber-50 dark:hover:bg-amber-900/30 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-b-0 transition-colors"
                  >
                    <div className="flex items-center space-x-3">
                      <div className="w-12 h-12 bg-gradient-to-br from-amber-600 to-amber-700 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span className="text-white font-black text-sm">{airport.iataCode}</span>
                      </div>
                      <div className="flex-1">
                        <div className="font-bold text-gray-900 dark:text-white text-lg">
                          {airport.name}
                        </div>
                        <div className="text-sm text-gray-600 dark:text-gray-400 font-semibold">
                          {airport.address?.cityName}, {airport.address?.countryName}
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>

        {/* Dates */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div>
            <label className="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
              <CalendarIcon className="w-5 h-5 text-purple-600" />
              <span>Date de Départ *</span>
            </label>
            <input
              type="date"
              value={searchParams.departureDate}
              onChange={(e) => setSearchParams({ ...searchParams, departureDate: e.target.value })}
              min={new Date().toISOString().split('T')[0]}
              className="w-full px-4 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 dark:bg-gray-700 dark:text-white text-lg font-semibold transition-all"
              required
            />
          </div>

          {tripType === 'roundtrip' && (
            <div>
              <label className="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                <CalendarIcon className="w-5 h-5 text-amber-600" />
                <span>Date de Retour *</span>
              </label>
              <input
                type="date"
                value={searchParams.returnDate}
                onChange={(e) => setSearchParams({ ...searchParams, returnDate: e.target.value })}
                min={searchParams.departureDate || new Date().toISOString().split('T')[0]}
                className="w-full px-4 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-amber-500/50 focus:border-amber-500 dark:bg-gray-700 dark:text-white text-lg font-semibold transition-all"
                required={tripType === 'roundtrip'}
              />
            </div>
          )}
        </div>

        {/* Passagers et Classe */}
        <div className="bg-gradient-to-r from-purple-50 to-amber-50 dark:from-purple-900/20 dark:to-amber-900/20 rounded-2xl p-6 mb-6 border-2 border-purple-100 dark:border-purple-800">
          <div className="flex items-center space-x-2 mb-4">
            <UsersIcon className="w-6 h-6 text-purple-600" />
            <h3 className="text-lg font-black text-gray-900 dark:text-white">Passagers & Classe</h3>
          </div>
          
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
              <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                Adultes (12+)
              </label>
              <select
                value={searchParams.adults}
                onChange={(e) => setSearchParams({ ...searchParams, adults: parseInt(e.target.value) })}
                className="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-semibold"
              >
                {[1, 2, 3, 4, 5, 6, 7, 8, 9].map(num => (
                  <option key={num} value={num}>{num}</option>
                ))}
              </select>
            </div>

            <div>
              <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                Enfants (2-11)
              </label>
              <select
                value={searchParams.children}
                onChange={(e) => setSearchParams({ ...searchParams, children: parseInt(e.target.value) })}
                className="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-semibold"
              >
                {[0, 1, 2, 3, 4, 5, 6, 7, 8].map(num => (
                  <option key={num} value={num}>{num}</option>
                ))}
              </select>
            </div>

            <div>
              <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                Bébés (0-2)
              </label>
              <select
                value={searchParams.infants}
                onChange={(e) => setSearchParams({ ...searchParams, infants: parseInt(e.target.value) })}
                className="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-semibold"
              >
                {[0, 1, 2, 3, 4].map(num => (
                  <option key={num} value={num}>{num}</option>
                ))}
              </select>
            </div>

            <div>
              <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                Classe
              </label>
              <select
                value={searchParams.travelClass}
                onChange={(e) => setSearchParams({ ...searchParams, travelClass: e.target.value })}
                className="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-semibold"
              >
                <option value="ECONOMY">Économique</option>
                <option value="PREMIUM_ECONOMY">Éco Premium</option>
                <option value="BUSINESS">Affaires</option>
                <option value="FIRST">Première</option>
              </select>
            </div>
          </div>
        </div>

        {/* Options */}
        <div className="mb-8">
          <label className="flex items-center space-x-3 cursor-pointer group">
            <div className="relative">
              <input
                type="checkbox"
                checked={searchParams.nonStop}
                onChange={(e) => setSearchParams({ ...searchParams, nonStop: e.target.checked })}
                className="w-6 h-6 text-purple-600 border-2 border-gray-300 rounded-lg focus:ring-4 focus:ring-purple-500/50 transition-all"
              />
            </div>
            <div className="flex items-center space-x-2">
              <CheckCircleIcon className="w-5 h-5 text-purple-600" />
              <span className="text-gray-700 dark:text-gray-300 font-bold text-lg group-hover:text-purple-600 transition-colors">
                Vols directs uniquement (sans escale)
              </span>
            </div>
          </label>
        </div>

        {/* Bouton de recherche */}
        <button
          type="submit"
          disabled={isSearching}
          className="w-full bg-gradient-to-r from-purple-600 via-purple-700 to-amber-600 hover:from-purple-700 hover:via-purple-800 hover:to-amber-700 text-white font-black text-xl py-6 px-8 rounded-2xl transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none shadow-2xl hover:shadow-purple-500/50 flex items-center justify-center space-x-3"
        >
          {isSearching ? (
            <>
              <svg className="animate-spin h-6 w-6" fill="none" viewBox="0 0 24 24">
                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>Recherche en cours...</span>
            </>
          ) : (
            <>
              <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <span>RECHERCHER DES VOLS</span>
              <ArrowRightIcon className="w-6 h-6" />
            </>
          )}
        </button>
      </form>
    </div>
  );
};

export default FlightSearch;
