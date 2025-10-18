import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import { useCurrency } from '../contexts/CurrencyContext';
import { useCart } from '../contexts/CartContext';
import { eventService } from '../services/api';

const EventDetailsModern = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const { formatPrice } = useCurrency();
  const { addToCart } = useCart();

  const [event, setEvent] = useState(null);
  const [loading, setLoading] = useState(true);
  const [selectedZone, setSelectedZone] = useState(null);
  const [quantity, setQuantity] = useState(1);

  const defaultEvent = {
    id: 1,
    title_fr: "Roland Garros 2025",
    description_fr: "Assistez au tournoi de tennis le plus prestigieux au monde. Vivez l'émotion des matchs sur les courts mythiques de Roland Garros.",
    event_type: "sport",
    sport_type: "tennis",
    venue_name: "Stade Roland Garros",
    city: "Paris",
    country: "France",
    event_date: "2025-05-25",
    event_time: "11:00",
    image: "https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=1200&h=800&fit=crop",
    min_price: 250000,
    max_price: 1500000,
    available_seats: 150,
    is_featured: true,
    category: { name_fr: "Tennis" },
    zones: [
      { id: 1, zone_name_fr: "Tribune Centrale", zone_code: "TC", price: 1500000, available_seats: 20 },
      { id: 2, zone_name_fr: "Tribune Latérale", zone_code: "TL", price: 800000, available_seats: 50 },
      { id: 3, zone_name_fr: "Gradin Supérieur", zone_code: "GS", price: 250000, available_seats: 80 }
    ]
  };

  useEffect(() => {
    const fetchEvent = async () => {
      try {
        setLoading(true);
        const response = await eventService.getEventById(id);
        const eventData = response.data || defaultEvent;
        setEvent(eventData);
        if (eventData.zones && eventData.zones.length > 0) {
          setSelectedZone(eventData.zones[0]);
        }
      } catch (error) {
        setEvent(defaultEvent);
        setSelectedZone(defaultEvent.zones[0]);
      } finally {
        setLoading(false);
      }
    };
    fetchEvent();
  }, [id]);

  const handleBooking = () => {
    if (!selectedZone) return;
    addToCart({
      id: event.id,
      type: 'event',
      name: event.title_fr,
      venue: event.venue_name,
      date: event.event_date,
      time: event.event_time,
      location: `${event.city}, ${event.country}`,
      zone: selectedZone.zone_name_fr,
      quantity: quantity,
      price: selectedZone.price * quantity,
      image: event.image
    });
    navigate('/cart');
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
        <div className="text-center">
          <div className="w-16 h-16 border-4 border-amber-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
          <p className="text-gray-600 dark:text-gray-400">Chargement...</p>
        </div>
      </div>
    );
  }

  if (!event) {
    return (
      <div className="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
        <div className="text-center">
          <h2 className="text-2xl font-bold mb-4">Événement non trouvé</h2>
          <Link to="/events" className="text-amber-600 hover:underline">Retour aux événements</Link>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 pb-12">
      {/* Hero */}
      <section className="relative h-[50vh] overflow-hidden">
        <div className="absolute inset-0 bg-cover bg-center" style={{ backgroundImage: `url('${event.image}')` }}>
          <div className="absolute inset-0 bg-gradient-to-b from-black/70 to-black/70"></div>
        </div>
        <div className="relative z-10 container-custom h-full flex flex-col justify-center px-4">
          <Link to="/events" className="inline-flex items-center text-white mb-4 hover:text-amber-400">
            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
            </svg>
            Retour
          </Link>
          <div className="flex items-center space-x-3 mb-4">
            <span className="px-4 py-2 bg-gradient-to-r from-amber-500 to-pink-500 rounded-full text-xs font-black uppercase text-white">
              {event.category?.name_fr || event.event_type}
            </span>
            {event.is_featured && (
              <span className="px-3 py-1 bg-red-500 text-white text-xs font-black rounded-full animate-pulse">
                🔥 HOT
              </span>
            )}
          </div>
          <h1 className="text-4xl font-black text-white mb-2">{event.title_fr}</h1>
          <div className="flex flex-wrap gap-4 text-white/90">
            <div className="flex items-center space-x-2">
              <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fillRule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clipRule="evenodd" />
              </svg>
              <span>{new Date(event.event_date).toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}</span>
            </div>
            <div className="flex items-center space-x-2">
              <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clipRule="evenodd" />
              </svg>
              <span>{event.event_time}</span>
            </div>
            <div className="flex items-center space-x-2">
              <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fillRule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clipRule="evenodd" />
              </svg>
              <span>{event.city}, {event.country}</span>
            </div>
          </div>
        </div>
      </section>

      {/* Content */}
      <section className="py-8">
        <div className="container-custom">
          <div className="grid lg:grid-cols-3 gap-8">
            {/* Left - Details */}
            <div className="lg:col-span-2 space-y-6">
              {/* Description */}
              <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl">
                <h2 className="text-2xl font-black mb-4">À Propos</h2>
                <p className="text-gray-600 dark:text-gray-400 leading-relaxed">{event.description_fr}</p>
              </div>

              {/* Venue */}
              <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl">
                <h2 className="text-2xl font-black mb-4">Lieu</h2>
                <div className="flex items-start space-x-4">
                  <svg className="w-8 h-8 text-amber-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fillRule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clipRule="evenodd" />
                  </svg>
                  <div>
                    <h3 className="text-xl font-bold mb-1">{event.venue_name}</h3>
                    <p className="text-gray-600 dark:text-gray-400">{event.city}, {event.country}</p>
                  </div>
                </div>
              </div>

              {/* Zones */}
              <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl">
                <h2 className="text-2xl font-black mb-4">Sélectionnez Votre Zone</h2>
                <div className="grid md:grid-cols-3 gap-4">
                  {event.zones?.map((zone) => (
                    <button
                      key={zone.id}
                      onClick={() => setSelectedZone(zone)}
                      className={`p-4 rounded-xl border-2 transition-all ${
                        selectedZone?.id === zone.id
                          ? 'border-amber-600 bg-amber-50 dark:bg-amber-900/20 scale-105'
                          : 'border-gray-200 dark:border-gray-700 hover:border-amber-400'
                      }`}
                    >
                      <h3 className="font-bold mb-1">{zone.zone_name_fr}</h3>
                      <p className="text-2xl font-black text-amber-600 mb-1">{formatPrice(zone.price)}</p>
                      <p className="text-xs text-gray-600 dark:text-gray-400">{zone.available_seats} places</p>
                    </button>
                  ))}
                </div>
              </div>
            </div>

            {/* Right - Booking */}
            <div className="lg:col-span-1">
              <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl sticky top-24">
                <h2 className="text-2xl font-black mb-6">Réservation</h2>
                
                <div className="mb-6">
                  <label className="block text-sm font-bold mb-3">Nombre de billets</label>
                  <div className="flex items-center space-x-4">
                    <button
                      onClick={() => setQuantity(Math.max(1, quantity - 1))}
                      className="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center"
                    >
                      <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 12H4" />
                      </svg>
                    </button>
                    <span className="text-2xl font-bold">{quantity}</span>
                    <button
                      onClick={() => setQuantity(Math.min(10, quantity + 1))}
                      className="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center"
                    >
                      <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                      </svg>
                    </button>
                  </div>
                </div>

                {selectedZone && (
                  <div className="border-t border-gray-200 dark:border-gray-700 pt-6 mb-6">
                    <div className="space-y-3 mb-4">
                      <div className="flex justify-between text-sm">
                        <span className="text-gray-600 dark:text-gray-400">Zone</span>
                        <span className="font-semibold">{selectedZone.zone_name_fr}</span>
                      </div>
                      <div className="flex justify-between text-sm">
                        <span className="text-gray-600 dark:text-gray-400">Prix unitaire</span>
                        <span className="font-semibold">{formatPrice(selectedZone.price)}</span>
                      </div>
                      <div className="flex justify-between text-sm">
                        <span className="text-gray-600 dark:text-gray-400">Quantité</span>
                        <span className="font-semibold">x{quantity}</span>
                      </div>
                    </div>
                    <div className="flex justify-between items-baseline pt-4 border-t border-gray-200 dark:border-gray-700">
                      <span className="text-lg font-bold">Total</span>
                      <span className="text-3xl font-black bg-gradient-to-r from-amber-600 to-pink-600 bg-clip-text text-transparent">
                        {formatPrice(selectedZone.price * quantity)}
                      </span>
                    </div>
                  </div>
                )}

                <button
                  onClick={handleBooking}
                  disabled={!selectedZone}
                  className="w-full py-4 bg-gradient-to-r from-amber-600 to-pink-600 text-white font-bold rounded-xl hover:shadow-2xl transition-all flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
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

export default EventDetailsModern;
