import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import { useCurrency } from '../contexts/CurrencyContext';
import { useCart } from '../contexts/CartContext';
import { flightService } from '../services/api';

const FlightDetailsModern = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const { formatPrice } = useCurrency();
  const { addToCart } = useCart();

  const [flight, setFlight] = useState(null);
  const [loading, setLoading] = useState(true);
  const [selectedClass, setSelectedClass] = useState('economy');
  const [passengers, setPassengers] = useState(1);
  const [selectedOptions, setSelectedOptions] = useState({
    baggage: false,
    meal: false,
    insurance: false,
    seat_selection: false
  });

  // Vol par défaut
  const defaultFlight = {
    id: 1,
    airline: { name: 'Air France', code: 'AF', logo: '✈️' },
    flight_number: 'AF 703',
    departure_airport: { city: 'Abidjan', code: 'ABJ', name: 'Félix Houphouët-Boigny', country: 'Côte d\'Ivoire' },
    arrival_airport: { city: 'Paris', code: 'CDG', name: 'Charles de Gaulle', country: 'France' },
    departure_time: '10:30',
    arrival_time: '18:45',
    departure_date: '2025-01-15',
    duration: 375,
    aircraft_type: 'Boeing 777-300ER',
    economy_price: 450000,
    business_price: 850000,
    first_class_price: 1200000,
    available_economy: 45,
    available_business: 12,
    available_first_class: 4,
    stops: 0,
    image: 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=1200&h=800&fit=crop',
    amenities: { wifi: true, entertainment: true, power: true, meals: true },
    baggage: {
      economy: { cabin: '1x8kg', checked: '1x23kg' },
      business: { cabin: '2x12kg', checked: '2x32kg' },
      first: { cabin: '2x15kg', checked: '3x32kg' }
    }
  };

  useEffect(() => {
    const fetchFlight = async () => {
      try {
        setLoading(true);
        const response = await flightService.getFlightById(id);
        setFlight(response.data || defaultFlight);
      } catch (error) {
        setFlight(defaultFlight);
      } finally {
        setLoading(false);
      }
    };
    fetchFlight();
  }, [id]);

  const getPrice = () => {
    if (!flight) return 0;
    let basePrice = selectedClass === 'business' ? flight.business_price : selectedClass === 'first' ? flight.first_class_price : flight.economy_price;
    let optionsPrice = 0;
    if (selectedOptions.baggage) optionsPrice += 25000;
    if (selectedOptions.meal) optionsPrice += 15000;
    if (selectedOptions.insurance) optionsPrice += 35000;
    if (selectedOptions.seat_selection) optionsPrice += 10000;
    return (basePrice + optionsPrice) * passengers;
  };

  const formatDuration = (minutes) => {
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    return `${hours}h ${mins}min`;
  };

  const handleBooking = () => {
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
      options: selectedOptions,
      price: getPrice(),
      image: flight.image
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
          <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-4">Vol non trouvé</h2>
          <Link to="/flights" className="text-purple-600 hover:underline">Retour aux vols</Link>
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
          <Link to="/flights" className="inline-flex items-center text-white mb-4 hover:text-purple-400">
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

      {/* Content */}
      <section className="py-8">
        <div className="container-custom">
          <div className="grid lg:grid-cols-3 gap-8">
            {/* Left - Details */}
            <div className="lg:col-span-2 space-y-6">
              {/* Flight Info */}
              <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl">
                <h2 className="text-2xl font-black mb-4">Informations du Vol</h2>
                <div className="flex justify-between items-center mb-6">
                  <div className="text-center">
                    <p className="text-3xl font-black">{flight.departure_time}</p>
                    <p className="text-sm font-bold text-gray-600 mt-1">{flight.departure_airport.code}</p>
                  </div>
                  <div className="flex-1 px-6">
                    <div className="border-t-2 border-gray-300 relative">
                      <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 px-2">
                        <svg className="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
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
                <h2 className="text-2xl font-black mb-4">Classe</h2>
                <div className="grid md:grid-cols-3 gap-4">
                  {[
                    { value: 'economy', label: 'Économique', price: flight.economy_price },
                    { value: 'business', label: 'Affaires', price: flight.business_price },
                    { value: 'first', label: 'Première', price: flight.first_class_price }
                  ].map((c) => (
                    <button
                      key={c.value}
                      onClick={() => setSelectedClass(c.value)}
                      className={`p-4 rounded-xl border-2 ${selectedClass === c.value ? 'border-purple-600 bg-purple-50 dark:bg-purple-900/20' : 'border-gray-200 dark:border-gray-700'}`}
                    >
                      <h3 className="font-bold mb-1">{c.label}</h3>
                      <p className="text-2xl font-black text-purple-600">{formatPrice(c.price)}</p>
                    </button>
                  ))}
                </div>
              </div>

              {/* Options */}
              <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl">
                <h2 className="text-2xl font-black mb-4">Options</h2>
                <div className="space-y-3">
                  {[
                    { key: 'baggage', label: 'Bagage +', price: 25000 },
                    { key: 'meal', label: 'Repas spécial', price: 15000 },
                    { key: 'insurance', label: 'Assurance', price: 35000 },
                    { key: 'seat_selection', label: 'Choix siège', price: 10000 }
                  ].map((opt) => (
                    <label key={opt.key} className="flex items-center justify-between p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 cursor-pointer hover:border-purple-400">
                      <div className="flex items-center space-x-3">
                        <input
                          type="checkbox"
                          checked={selectedOptions[opt.key]}
                          onChange={(e) => setSelectedOptions({...selectedOptions, [opt.key]: e.target.checked})}
                          className="w-5 h-5 text-purple-600 rounded"
                        />
                        <span className="font-semibold">{opt.label}</span>
                      </div>
                      <span className="font-bold text-purple-600">+{formatPrice(opt.price)}</span>
                    </label>
                  ))}
                </div>
              </div>
            </div>

            {/* Right - Booking */}
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
                    <span className="text-3xl font-black bg-gradient-to-r from-purple-600 to-amber-600 bg-clip-text text-transparent">
                      {formatPrice(getPrice())}
                    </span>
                  </div>
                </div>

                <button
                  onClick={handleBooking}
                  className="w-full py-4 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-2xl transition-all flex items-center justify-center space-x-2"
                >
                  <span>Réserver Maintenant</span>
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default FlightDetailsModern;
