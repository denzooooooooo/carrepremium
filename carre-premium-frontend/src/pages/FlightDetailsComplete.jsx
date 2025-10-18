import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate, useLocation } from 'react-router-dom';
import { useCurrency } from '../contexts/CurrencyContext';
import { useCart } from '../contexts/CartContext';
import amadeusService from '../services/amadeusService';
import SeatSelector from '../components/SeatSelector';
import PassengerForm from '../components/PassengerForm';

const FlightDetailsComplete = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const location = useLocation();
  const { formatPrice, convertPrice } = useCurrency();
  const { addToCart } = useCart();

  const [flight, setFlight] = useState(null);
  const [loading, setLoading] = useState(true);
  const [amadeusOffer, setAmadeusOffer] = useState(null);
  const [dictionaries, setDictionaries] = useState(null);
  const [currentStep, setCurrentStep] = useState(1);
  const [selectedClass, setSelectedClass] = useState('economy');
  const [passengers, setPassengers] = useState(1);
  const [selectedSeats, setSelectedSeats] = useState([]);
  const [passengerData, setPassengerData] = useState([]);
  const [selectedOptions, setSelectedOptions] = useState({
    baggage: false,
    meal: false,
    insurance: false,
    seat_selection: false
  });

  useEffect(() => {
    // Récupérer les données Amadeus depuis location.state
    if (location.state?.offer && location.state?.dictionaries) {
      const { offer, dictionaries } = location.state;
      setAmadeusOffer(offer);
      setDictionaries(dictionaries);
      
      // Formater les données pour l'affichage
      const formatted = amadeusService.formatFlightOffer(offer, dictionaries);
      
      setFlight({
        id: offer.id,
        airline: { name: formatted.airlines, code: formatted.airlines.substring(0, 2) },
        flight_number: formatted.airlines,
        departure_airport: { 
          city: formatted.outbound.departure.airport, 
          code: formatted.outbound.departure.airport,
          name: formatted.outbound.departure.airport
        },
        arrival_airport: { 
          city: formatted.outbound.arrival.airport, 
          code: formatted.outbound.arrival.airport,
          name: formatted.outbound.arrival.airport
        },
        departure_time: amadeusService.formatDateTime(formatted.outbound.departure.time).time,
        arrival_time: amadeusService.formatDateTime(formatted.outbound.arrival.time).time,
        departure_date: formatted.outbound.departure.time.split('T')[0],
        duration: formatted.outbound.duration,
        economy_price: parseFloat(formatted.price.total),
        business_price: parseFloat(formatted.price.total) * 2,
        first_class_price: parseFloat(formatted.price.total) * 3,
        available_economy: formatted.availableSeats,
        available_business: Math.floor(formatted.availableSeats / 3),
        available_first_class: Math.floor(formatted.availableSeats / 5),
        stops: formatted.outbound.stops,
        image: 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1200&h=800&fit=crop',
        amenities: { wifi: true, entertainment: true, power: true, meals: true },
        baggage: {
          economy: { cabin: '1x8kg', checked: '1x23kg' },
          business: { cabin: '2x12kg', checked: '2x32kg' },
          first: { cabin: '2x15kg', checked: '3x32kg' }
        },
        amadeus_data: offer // Garder les données originales
      });
      setLoading(false);
    } else {
      // Si pas de données, rediriger vers la recherche
      navigate('/flights');
    }
  }, [location, navigate]);

  const steps = [
    { id: 1, name: 'Options', icon: '⚙️', description: 'Classe & Options' },
    { id: 2, name: 'Sièges', icon: '💺', description: 'Sélection des sièges' },
    { id: 3, name: 'Passagers', icon: '👤', description: 'Informations passagers' },
    { id: 4, name: 'Récapitulatif', icon: '📋', description: 'Vérification finale' }
  ];

  const getPrice = () => {
    if (!flight) return 0;
    let basePrice = selectedClass === 'business' ? flight.business_price : selectedClass === 'first' ? flight.first_class_price : flight.economy_price;
    let optionsPrice = 0;
    if (selectedOptions.baggage) optionsPrice += basePrice * 0.05; // 5% du prix
    if (selectedOptions.meal) optionsPrice += basePrice * 0.03; // 3% du prix
    if (selectedOptions.insurance) optionsPrice += basePrice * 0.08; // 8% du prix
    if (selectedOptions.seat_selection) optionsPrice += basePrice * 0.02; // 2% du prix
    return (basePrice + optionsPrice) * passengers;
  };

  const formatDuration = (durationString) => {
    if (typeof durationString === 'number') {
      const hours = Math.floor(durationString / 60);
      const mins = durationString % 60;
      return `${hours}h ${mins}min`;
    }
    // Si c'est déjà formaté par amadeusService
    return durationString;
  };

  const canProceedToNextStep = () => {
    if (currentStep === 1) return true;
    if (currentStep === 2 && selectedOptions.seat_selection) {
      return selectedSeats.length === passengers;
    }
    if (currentStep === 3) {
      return passengerData.length === passengers && passengerData.every(p => 
        p.firstName && p.lastName && p.passportNumber && p.dateOfBirth
      );
    }
    return true;
  };

  const handleNextStep = () => {
    if (!canProceedToNextStep()) {
      alert('Veuillez compléter toutes les informations requises');
      return;
    }
    if (currentStep === 2 && !selectedOptions.seat_selection) {
      setCurrentStep(3);
    } else {
      setCurrentStep(Math.min(4, currentStep + 1));
    }
  };

  const handleBooking = () => {
    if (!canProceedToNextStep()) {
      alert('Veuillez compléter toutes les informations');
      return;
    }
    
    addToCart({
      id: flight.id,
      type: 'flight',
      name: `${flight.departure_airport.city} → ${flight.arrival_airport.city}`,
      airline: flight.airline.name,
      flight_number: flight.flight_number,
      date: flight.departure_date,
      departure: flight.departure_time,
      arrival: flight.arrival_time,
      class: selectedClass,
      passengers: passengers,
      selectedSeats: selectedSeats,
      passengerData: passengerData,
      options: selectedOptions,
      price: getPrice(),
      image: flight.image,
      amadeus_data: amadeusOffer // Données Amadeus pour traitement
    });
    navigate('/cart');
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
        <div className="text-center">
          <div className="w-16 h-16 border-4 border-purple-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
          <p className="text-gray-600 dark:text-gray-400">Chargement...</p>
        </div>
      </div>
    );
  }

  if (!flight) {
    return (
      <div className="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
        <div className="text-center">
          <h2 className="text-2xl font-bold mb-4">Vol non trouvé</h2>
          <Link to="/flights" className="text-purple-600 hover:underline">Retour</Link>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 pb-12">
      {/* Hero */}
      <section className="relative h-[40vh] overflow-hidden">
        <div className="absolute inset-0 bg-cover bg-center" style={{ backgroundImage: `url('${flight.image}')` }}>
          <div className="absolute inset-0 bg-gradient-to-b from-black/70 to-black/70"></div>
        </div>
        <div className="relative z-10 container-custom h-full flex flex-col justify-center px-4">
          <Link to="/flights" className="inline-flex items-center text-white mb-4 hover:text-amber-400">
            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
            </svg>
            Retour
          </Link>
          <h1 className="text-4xl font-black text-white mb-2">
            {flight.departure_airport.city} → {flight.arrival_airport.city}
          </h1>
          <p className="text-xl text-white/80">{flight.airline.name} • {flight.flight_number}</p>
        </div>
      </section>

      {/* Avertissement Important */}
      <section className="py-4 bg-blue-50 dark:bg-blue-900/20 border-b-2 border-blue-200 dark:border-blue-800">
        <div className="container-custom">
          <div className="flex items-start space-x-3">
            <svg className="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
              <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clipRule="evenodd" />
            </svg>
            <div className="text-sm text-blue-900 dark:text-blue-100">
              <p className="font-bold mb-1">📌 Processus de Réservation</p>
              <p>Après paiement, votre demande sera traitée par notre équipe qui finalisera la réservation et vous enverra votre PNR et billets électroniques sous 2-4 heures. Les prix affichés sont garantis.</p>
            </div>
          </div>
        </div>
      </section>

      {/* Steps Progress */}
      <section className="py-6 bg-white dark:bg-gray-800 shadow-lg">
        <div className="container-custom">
          <div className="flex items-center justify-between">
            {steps.map((step, index) => (
              <React.Fragment key={step.id}>
                <div className="flex items-center flex-1">
                  <button
                    onClick={() => currentStep > step.id && setCurrentStep(step.id)}
                    className={`flex items-center space-x-3 ${currentStep >= step.id ? 'opacity-100' : 'opacity-40'}`}
                    disabled={currentStep < step.id}
                  >
                    <div className={`w-12 h-12 rounded-full flex items-center justify-center text-2xl ${
                      currentStep === step.id 
                        ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white scale-110 shadow-xl' 
                        : currentStep > step.id
                        ? 'bg-green-500 text-white'
                        : 'bg-gray-200 dark:bg-gray-700'
                    }`}>
                      {currentStep > step.id ? '✓' : step.icon}
                    </div>
                    <div className="hidden md:block text-left">
                      <p className="font-bold text-sm">{step.name}</p>
                      <p className="text-xs text-gray-500">{step.description}</p>
                    </div>
                  </button>
                </div>
                {index < steps.length - 1 && (
                  <div className={`h-1 flex-1 mx-2 ${currentStep > step.id ? 'bg-green-500' : 'bg-gray-200 dark:bg-gray-700'}`}></div>
                )}
              </React.Fragment>
            ))}
          </div>
        </div>
      </section>

      {/* Content */}
      <section className="py-8">
        <div className="container-custom">
          <div className="grid lg:grid-cols-3 gap-8">
            {/* Left - Steps Content */}
            <div className="lg:col-span-2 space-y-6">
              
              {/* Step 1: Options */}
              {currentStep === 1 && (
                <>
                  {/* Flight Info */}
                  <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl">
                    <h2 className="text-2xl font-black mb-4">Informations du Vol</h2>
                    <div className="flex justify-between items-center">
                      <div className="text-center">
                        <p className="text-3xl font-black">{flight.departure_time}</p>
                        <p className="text-sm font-bold text-gray-600 mt-1">{flight.departure_airport.code}</p>
                      </div>
                      <div className="flex-1 px-6">
                        <div className="border-t-2 border-gray-300 relative">
                          <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 px-2">
                            <svg className="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                              <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                          </div>
                        </div>
                        <p className="text-center text-sm mt-2">{formatDuration(flight.duration)}</p>
                      </div>
                      <div className="text-center">
                        <p className="text-3xl font-black">{flight.arrival_time}</p>
                        <p className="text-sm font-bold text-gray-600 mt-1">{flight.arrival_airport.code}</p>
                      </div>
                    </div>
                  </div>

                  {/* Class Selection */}
                  <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl">
                    <h2 className="text-2xl font-black mb-4">Choisissez Votre Classe</h2>
                    <div className="grid md:grid-cols-3 gap-4">
                      {[
                        { value: 'economy', label: 'Économique', price: flight.economy_price, features: ['Siège standard', 'Repas inclus', 'Divertissement'] },
                        { value: 'business', label: 'Affaires', price: flight.business_price, features: ['Siège lit', 'Repas gastronomique', 'Salon VIP'] },
                        { value: 'first', label: 'Première', price: flight.first_class_price, features: ['Suite privée', 'Chef à bord', 'Service premium'] }
                      ].map((c) => (
                        <button
                          key={c.value}
                          onClick={() => setSelectedClass(c.value)}
                          className={`p-6 rounded-2xl border-2 transition-all ${selectedClass === c.value ? 'border-amber-600 bg-amber-50 dark:bg-amber-900/20 scale-105' : 'border-gray-200 dark:border-gray-700'}`}
                        >
                          <h3 className="font-bold mb-2">{c.label}</h3>
                          <p className="text-3xl font-black text-amber-600 mb-3">{formatPrice(c.price)}</p>
                          <div className="space-y-1">
                            {c.features.map((f, i) => (
                              <div key={i} className="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                <svg className="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                  <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                </svg>
                                {f}
                              </div>
                            ))}
                          </div>
                        </button>
                      ))}
                    </div>
                  </div>

                  {/* Options */}
                  <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl">
                    <h2 className="text-2xl font-black mb-4">Options Supplémentaires</h2>
                    <div className="space-y-3">
                      {[
                        { key: 'baggage', label: 'Bagage supplémentaire', price: 25000, icon: '🧳' },
                        { key: 'meal', label: 'Repas spécial', price: 15000, icon: '🍽️' },
                        { key: 'insurance', label: 'Assurance voyage', price: 35000, icon: '🛡️' },
                        { key: 'seat_selection', label: 'Sélection de siège', price: 10000, icon: '💺' }
                      ].map((opt) => (
                        <label key={opt.key} className="flex items-center justify-between p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 cursor-pointer hover:border-amber-400">
                          <div className="flex items-center space-x-3">
                            <input
                              type="checkbox"
                              checked={selectedOptions[opt.key]}
                              onChange={(e) => setSelectedOptions({...selectedOptions, [opt.key]: e.target.checked})}
                              className="w-5 h-5 text-amber-600 rounded"
                            />
                            <span className="text-xl">{opt.icon}</span>
                            <span className="font-semibold">{opt.label}</span>
                          </div>
                          <span className="font-bold text-amber-600">+{formatPrice(opt.price)}</span>
                        </label>
                      ))}
                    </div>
                  </div>
                </>
              )}

              {/* Step 2: Seat Selection */}
              {currentStep === 2 && selectedOptions.seat_selection && (
                <SeatSelector 
                  selectedClass={selectedClass}
                  passengers={passengers}
                  onSeatsSelected={setSelectedSeats}
                />
              )}

              {currentStep === 2 && !selectedOptions.seat_selection && (
                <div className="bg-white dark:bg-gray-800 rounded-3xl p-12 shadow-xl text-center">
                  <svg className="w-20 h-20 mx-auto mb-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clipRule="evenodd" />
                  </svg>
                  <h3 className="text-2xl font-bold mb-2">Sélection de siège non activée</h3>
                  <p className="text-gray-600 dark:text-gray-400 mb-6">Retournez à l'étape précédente pour activer cette option</p>
                  <button
                    onClick={() => setCurrentStep(1)}
                    className="px-6 py-3 bg-amber-600 text-white font-bold rounded-xl"
                  >
                    Retour aux Options
                  </button>
                </div>
              )}

              {/* Step 3: Passenger Info */}
              {currentStep === 3 && (
                <PassengerForm 
                  passengers={passengers}
                  onPassengersUpdate={setPassengerData}
                />
              )}

              {/* Step 4: Summary */}
              {currentStep === 4 && (
                <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl">
                  <h2 className="text-2xl font-black mb-6">Récapitulatif de Votre Réservation</h2>
                  
                  <div className="space-y-6">
                    {/* Flight Summary */}
                    <div className="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                      <h3 className="font-bold mb-3">Vol</h3>
                      <p className="text-sm text-gray-600 dark:text-gray-400">
                        {flight.departure_airport.city} ({flight.departure_airport.code}) → {flight.arrival_airport.city} ({flight.arrival_airport.code})
                      </p>
                      <p className="text-sm text-gray-600 dark:text-gray-400">
                        {new Date(flight.departure_date).toLocaleDateString('fr-FR')} • {flight.departure_time} - {flight.arrival_time}
                      </p>
                    </div>

                    {/* Passengers Summary */}
                    <div className="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                      <h3 className="font-bold mb-3">Passagers ({passengers})</h3>
                      {passengerData.map((p, i) => (
                        <div key={i} className="text-sm text-gray-600 dark:text-gray-400 mb-2">
                          {i + 1}. {p.title} {p.firstName} {p.lastName}
                          {selectedSeats[i] && ` - Siège ${selectedSeats[i]}`}
                        </div>
                      ))}
                    </div>

                    {/* Options Summary */}
                    <div className="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                      <h3 className="font-bold mb-3">Options</h3>
                      <p className="text-sm text-gray-600 dark:text-gray-400">Classe: {selectedClass}</p>
                      {Object.entries(selectedOptions).filter(([k, v]) => v).map(([key]) => (
                        <p key={key} className="text-sm text-gray-600 dark:text-gray-400">✓ {key}</p>
                      ))}
                    </div>
                  </div>
                </div>
              )}
            </div>

            {/* Right - Booking Summary */}
            <div className="lg:col-span-1">
              <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl sticky top-24">
                <h2 className="text-2xl font-black mb-6">Récapitulatif</h2>
                
                <div className="mb-6">
                  <label className="block text-sm font-bold mb-3">Passagers</label>
                  <div className="flex items-center space-x-4">
                    <button
                      onClick={() => setPassengers(Math.max(1, passengers - 1))}
                      className="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center"
                    >
                      <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 12H4" />
                      </svg>
                    </button>
                    <span className="text-2xl font-bold">{passengers}</span>
                    <button
                      onClick={() => setPassengers(Math.min(9, passengers + 1))}
                      className="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center"
                    >
                      <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                      </svg>
                    </button>
                  </div>
                </div>

                <div className="border-t border-gray-200 dark:border-gray-700 pt-6 mb-6">
                  <div className="space-y-3 mb-4">
                    <div className="flex justify-between text-sm">
                      <span className="text-gray-600 dark:text-gray-400">Classe {selectedClass}</span>
                      <span className="font-semibold">{formatPrice(selectedClass === 'business' ? flight.business_price : selectedClass === 'first' ? flight.first_class_price : flight.economy_price)}</span>
                    </div>
                    <div className="flex justify-between text-sm">
                      <span className="text-gray-600 dark:text-gray-400">Passagers</span>
                      <span className="font-semibold">x{passengers}</span>
                    </div>
                    {Object.entries(selectedOptions).filter(([k, v]) => v).map(([key]) => (
                      <div key={key} className="flex justify-between text-sm">
                        <span className="text-gray-600 dark:text-gray-400">Option {key}</span>
                        <span className="font-semibold">+{formatPrice(key === 'baggage' ? 25000 : key === 'meal' ? 15000 : key === 'insurance' ? 35000 : 10000)}</span>
                      </div>
                    ))}
                  </div>
                  <div className="flex justify-between items-baseline pt-4 border-t border-gray-200 dark:border-gray-700">
                    <span className="text-lg font-bold">Total</span>
                    <span className="text-3xl font-black bg-gradient-to-r from-amber-600 to-amber-800 bg-clip-text text-transparent">
                      {formatPrice(getPrice())}
                    </span>
                  </div>
                </div>

                {currentStep < 4 ? (
                  <button
                    onClick={handleNextStep}
                    disabled={!canProceedToNextStep()}
                    className="w-full py-4 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold rounded-xl hover:shadow-2xl transition-all flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <span>Étape Suivante</span>
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                  </button>
                ) : (
                  <button
                    onClick={handleBooking}
                    className="w-full py-4 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl hover:shadow-2xl transition-all flex items-center justify-center space-x-2"
                  >
                    <span>Confirmer la Réservation</span>
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                    </svg>
                  </button>
                )}

                {currentStep > 1 && (
                  <button
                    onClick={() => setCurrentStep(currentStep - 1)}
                    className="w-full mt-3 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-900 transition-all"
                  >
                    ← Étape Précédente
                  </button>
                )}
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default FlightDetailsComplete;
