import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import { useCurrency } from '../contexts/CurrencyContext';
import { useCart } from '../contexts/CartContext';
import { packageService } from '../services/api';

const PackageDetailsModern = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const { formatPrice } = useCurrency();
  const { addToCart } = useCart();

  const [pkg, setPkg] = useState(null);
  const [loading, setLoading] = useState(true);
  const [selectedDate, setSelectedDate] = useState('');
  const [participants, setParticipants] = useState(1);

  const defaultPackage = {
    id: 1,
    title_fr: "Survol en Hélicoptère - Abidjan",
    description_fr: "Découvrez Abidjan vue du ciel dans un hélicoptère privé. Une expérience inoubliable au-dessus de la capitale économique ivoirienne.",
    package_type: "helicopter",
    destination: "Abidjan, Côte d'Ivoire",
    duration: 1,
    duration_text_fr: "30 minutes",
    price: 1500000,
    image: "https://images.unsplash.com/photo-1589519160732-57fc498494f8?w=1200&h=800&fit=crop",
    included_services_fr: [
      "Vol privé de 30 minutes",
      "Champagne et rafraîchissements",
      "Photos professionnelles incluses",
      "Pilote expérimenté certifié",
      "Assurance complète",
      "Transfert depuis/vers l'hôtel"
    ],
    excluded_services_fr: [
      "Repas",
      "Hébergement",
      "Dépenses personnelles"
    ],
    max_participants: 4,
    is_featured: true,
    rating: 4.9,
    available_dates: ["2025-01-20", "2025-01-25", "2025-02-01", "2025-02-10"],
    gallery: [
      "https://images.unsplash.com/photo-1589519160732-57fc498494f8?w=600&h=400&fit=crop",
      "https://images.unsplash.com/photo-1464037866556-6812c9d1c72e?w=600&h=400&fit=crop",
      "https://images.unsplash.com/photo-1583939003579-730e3918a45a?w=600&h=400&fit=crop"
    ]
  };

  useEffect(() => {
    const fetchPackage = async () => {
      try {
        setLoading(true);
        const response = await packageService.getPackageById(id);
        const pkgData = response.data || defaultPackage;
        setPkg(pkgData);
        if (pkgData.available_dates && pkgData.available_dates.length > 0) {
          setSelectedDate(pkgData.available_dates[0]);
        }
      } catch (error) {
        setPkg(defaultPackage);
        setSelectedDate(defaultPackage.available_dates[0]);
      } finally {
        setLoading(false);
      }
    };
    fetchPackage();
  }, [id]);

  const handleBooking = () => {
    if (!selectedDate) return;
    addToCart({
      id: pkg.id,
      type: 'package',
      name: pkg.title_fr,
      destination: pkg.destination,
      duration: pkg.duration_text_fr,
      date: selectedDate,
      participants: participants,
      price: pkg.price * participants,
      image: pkg.image
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

  if (!pkg) {
    return (
      <div className="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
        <div className="text-center">
          <h2 className="text-2xl font-bold mb-4">Package non trouvé</h2>
          <Link to="/packages" className="text-purple-600 hover:underline">Retour aux packages</Link>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 pb-12">
      {/* Hero */}
      <section className="relative h-[50vh] overflow-hidden">
        <div className="absolute inset-0 bg-cover bg-center" style={{ backgroundImage: `url('${pkg.image}')` }}>
          <div className="absolute inset-0 bg-gradient-to-b from-black/70 to-black/70"></div>
        </div>
        <div className="relative z-10 container-custom h-full flex flex-col justify-center px-4">
          <Link to="/packages" className="inline-flex items-center text-white mb-4 hover:text-purple-400">
            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
            </svg>
            Retour
          </Link>
          <div className="flex items-center space-x-3 mb-4">
            <span className="px-4 py-2 bg-gradient-to-r from-purple-600 to-amber-600 rounded-full text-xs font-bold text-white">
              VIP
            </span>
            {pkg.rating && (
              <div className="flex items-center space-x-1 px-3 py-2 bg-white/20 backdrop-blur-md rounded-full">
                <svg className="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span className="text-white font-bold">{pkg.rating}</span>
              </div>
            )}
          </div>
          <h1 className="text-4xl font-black text-white mb-2">{pkg.title_fr}</h1>
          <p className="text-xl text-white/80">{pkg.destination} • {pkg.duration_text_fr}</p>
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
                <h2 className="text-2xl font-black mb-4">Description</h2>
                <p className="text-gray-600 dark:text-gray-400 leading-relaxed">{pkg.description_fr}</p>
              </div>

              {/* Included */}
              <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl">
                <h2 className="text-2xl font-black mb-4">Inclus dans le Package</h2>
                <div className="grid md:grid-cols-2 gap-3">
                  {pkg.included_services_fr?.map((service, idx) => (
                    <div key={idx} className="flex items-start space-x-3">
                      <svg className="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                      </svg>
                      <span className="text-gray-700 dark:text-gray-300">{service}</span>
                    </div>
                  ))}
                </div>
              </div>

              {/* Excluded */}
              {pkg.excluded_services_fr && pkg.excluded_services_fr.length > 0 && (
                <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl">
                  <h2 className="text-2xl font-black mb-4">Non Inclus</h2>
                  <div className="space-y-2">
                    {pkg.excluded_services_fr.map((service, idx) => (
                      <div key={idx} className="flex items-start space-x-3">
                        <svg className="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                          <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                        </svg>
                        <span className="text-gray-700 dark:text-gray-300">{service}</span>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {/* Gallery */}
              {pkg.gallery && pkg.gallery.length > 0 && (
                <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl">
                  <h2 className="text-2xl font-black mb-4">Galerie Photos</h2>
                  <div className="grid md:grid-cols-3 gap-4">
                    {pkg.gallery.map((img, idx) => (
                      <div key={idx} className="aspect-video rounded-xl overflow-hidden">
                        <img src={img} alt={`Gallery ${idx + 1}`} className="w-full h-full object-cover hover:scale-110 transition-transform duration-500" />
                      </div>
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Right - Booking */}
            <div className="lg:col-span-1">
              <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl sticky top-24">
                <h2 className="text-2xl font-black mb-6">Réservation</h2>
                
                <div className="mb-6">
                  <label className="block text-sm font-bold mb-3">Date de départ</label>
                  <select
                    value={selectedDate}
                    onChange={(e) => setSelectedDate(e.target.value)}
                    className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:border-purple-600 focus:outline-none"
                  >
                    {pkg.available_dates?.map((date) => (
                      <option key={date} value={date}>
                        {new Date(date).toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}
                      </option>
                    ))}
                  </select>
                </div>

                <div className="mb-6">
                  <label className="block text-sm font-bold mb-3">Participants (max {pkg.max_participants})</label>
                  <div className="flex items-center space-x-4">
                    <button
                      onClick={() => setParticipants(Math.max(1, participants - 1))}
                      className="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center"
                    >
                      <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 12H4" />
                      </svg>
                    </button>
                    <span className="text-2xl font-bold">{participants}</span>
                    <button
                      onClick={() => setParticipants(Math.min(pkg.max_participants, participants + 1))}
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
                      <span className="text-gray-600 dark:text-gray-400">Prix par personne</span>
                      <span className="font-semibold">{formatPrice(pkg.price)}</span>
                    </div>
                    <div className="flex justify-between text-sm">
                      <span className="text-gray-600 dark:text-gray-400">Participants</span>
                      <span className="font-semibold">x{participants}</span>
                    </div>
                  </div>
                  <div className="flex justify-between items-baseline pt-4 border-t border-gray-200 dark:border-gray-700">
                    <span className="text-lg font-bold">Total</span>
                    <span className="text-3xl font-black bg-gradient-to-r from-purple-600 to-amber-600 bg-clip-text text-transparent">
                      {formatPrice(pkg.price * participants)}
                    </span>
                  </div>
                </div>

                <button
                  onClick={handleBooking}
                  disabled={!selectedDate}
                  className="w-full py-4 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-2xl transition-all flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
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

export default PackageDetailsModern;
