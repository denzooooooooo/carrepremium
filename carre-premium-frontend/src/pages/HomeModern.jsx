import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useCurrency } from '../contexts/CurrencyContext';
import { eventService, packageService } from '../services/api';
import ServicesCarousel from '../components/ServicesCarousel';
import { 
  TrophyIcon, 
  HelicopterIcon, 
  MotorcycleIcon, 
  JetIcon, 
  MusicIcon, 
  CarIcon,
  TicketIcon,
  CalendarIcon,
  LocationIcon,
  CheckCircleIcon,
  FireIcon,
  ArrowRightIcon
} from '../components/icons/ServiceIcons';

const HomeModern = () => {
  const { formatPrice } = useCurrency();
  const [events, setEvents] = useState([]);
  const [packages, setPackages] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        const [eventsRes, packagesRes] = await Promise.allSettled([
          eventService.getEvents({ per_page: 8 }),
          packageService.getAllPackages(1, 6)
        ]);

        if (eventsRes.status === 'fulfilled' && eventsRes.value?.data) {
          const eventsData = eventsRes.value.data.data || eventsRes.value.data;
          setEvents(Array.isArray(eventsData) ? eventsData : []);
        }

        if (packagesRes.status === 'fulfilled' && packagesRes.value?.data) {
          const packagesData = packagesRes.value.data.data || packagesRes.value.data;
          setPackages(Array.isArray(packagesData) ? packagesData : []);
        }
      } catch (error) {
        console.error('Erreur:', error);
      } finally {
        setLoading(false);
      }
    };
    fetchData();
  }, []);

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      {/* Hero Carrousel avec icônes SVG professionnelles */}
      <ServicesCarousel />
      
      {/* Événements à la Une */}
      <section className="py-24 bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 relative overflow-hidden">
        <div className="absolute inset-0 opacity-20">
          <div className="absolute top-20 left-20 w-96 h-96 bg-amber-500 rounded-full filter blur-3xl animate-pulse"></div>
          <div className="absolute bottom-20 right-20 w-96 h-96 bg-pink-500 rounded-full filter blur-3xl animate-pulse" style={{animationDelay: '2s'}}></div>
        </div>

        <div className="container mx-auto px-4 relative z-10">
          <div className="text-center mb-16">
            <div className="inline-flex items-center space-x-3 px-8 py-3 bg-gradient-to-r from-amber-500 to-pink-500 text-white rounded-full text-sm font-black mb-6 shadow-2xl animate-pulse">
              <TrophyIcon className="w-6 h-6" />
              <span>ÉVÉNEMENTS SPORTIFS & CULTURELS EXCLUSIFS</span>
            </div>
            <h2 className="text-6xl md:text-7xl font-black text-white mb-6 leading-tight">
              Événements à Ne Pas Manquer
            </h2>
            <p className="text-2xl text-gray-300 max-w-3xl mx-auto">
              Accédez aux plus grands événements sportifs et culturels du monde
            </p>
          </div>

          <div className="grid md:grid-cols-4 gap-6 mb-12">
            {events.slice(0, 8).map((event, idx) => (
              <Link 
                key={idx} 
                to={`/events/${event.id}`} 
                className="group relative rounded-3xl overflow-hidden shadow-2xl hover:shadow-amber-500/50 transition-all duration-700 hover:-translate-y-6"
              >
                <div className="aspect-[3/4] overflow-hidden relative">
                  <img 
                    src={event.image || `https://images.unsplash.com/photo-146189683${6934 + idx}-ffe607ba8211?w=400&h=600&fit=crop`} 
                    alt={event.title_fr || event.title} 
                    className="w-full h-full object-cover group-hover:scale-125 transition-all duration-1000" 
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>
                  
                  <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                  
                  <div className="absolute top-4 right-4 px-4 py-2 bg-gradient-to-r from-amber-500 to-pink-500 rounded-full text-xs font-black uppercase text-white shadow-2xl">
                    {event.event_type || 'VIP'}
                  </div>
                  
                  {idx < 2 && (
                    <div className="absolute top-4 left-4 inline-flex items-center space-x-1 px-3 py-1 bg-red-500 rounded-full text-xs font-black text-white shadow-xl animate-pulse">
                      <FireIcon className="w-4 h-4" />
                      <span>HOT</span>
                    </div>
                  )}
                </div>

                <div className="absolute bottom-0 left-0 right-0 p-6 text-white">
                  <h3 className="text-xl font-black mb-3 group-hover:text-amber-400 transition-colors line-clamp-2">
                    {event.title_fr || event.title_en || event.title || 'Événement Exclusif'}
                  </h3>
                  
                  <div className="space-y-2 mb-4">
                    <div className="inline-flex items-center space-x-2 text-sm bg-white/10 backdrop-blur-md rounded-full px-3 py-2">
                      <CalendarIcon className="w-4 h-4 text-amber-400" />
                      <span className="font-semibold">{event.event_date || 'Date à venir'}</span>
                    </div>
                  </div>
                  
                  <div className="flex items-center justify-between p-3 bg-gradient-to-r from-amber-500/30 to-pink-500/30 backdrop-blur-md rounded-2xl border border-amber-400/50">
                    <span className="text-lg font-black bg-gradient-to-r from-amber-300 to-pink-300 bg-clip-text text-transparent">
                      {event.min_price || 'Sur demande'}
                    </span>
                    <ArrowRightIcon className="w-5 h-5 text-amber-400 group-hover:translate-x-2 transition-transform" />
                  </div>
                </div>
              </Link>
            ))}
          </div>

          <div className="text-center">
            <Link 
              to="/events" 
              className="inline-flex items-center space-x-3 px-12 py-6 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-500 text-white font-black text-xl rounded-full hover:scale-110 transition-all duration-300 shadow-2xl"
            >
              <span>VOIR TOUS LES ÉVÉNEMENTS</span>
              <ArrowRightIcon className="w-6 h-6" />
            </Link>
          </div>
        </div>
      </section>

      {/* Packages Premium */}
      <section className="py-24 bg-white dark:bg-gray-800 relative overflow-hidden">
        <div className="absolute top-0 right-0 w-96 h-96 bg-purple-200 dark:bg-purple-900 rounded-full filter blur-3xl opacity-20"></div>
        <div className="absolute bottom-0 left-0 w-96 h-96 bg-amber-200 dark:bg-amber-900 rounded-full filter blur-3xl opacity-20"></div>

        <div className="container mx-auto px-4 relative z-10">
          <div className="text-center mb-16">
            <div className="inline-flex items-center space-x-3 px-8 py-3 bg-gradient-to-r from-purple-600 to-amber-600 text-white rounded-full text-sm font-black mb-6 shadow-lg">
              <HelicopterIcon className="w-6 h-6" />
              <span>PACKAGES PREMIUM & LOCATION DE LUXE</span>
            </div>
            <h2 className="text-6xl md:text-7xl font-black text-gray-900 dark:text-white mb-6 leading-tight">
              Expériences de Luxe<br />
              <span className="bg-gradient-to-r from-purple-600 to-amber-600 bg-clip-text text-transparent">
                Sur Mesure
              </span>
            </h2>
            <p className="text-2xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
              Hélicoptères • Jets Privés • Quads • Safaris • Yachts • Voitures de Luxe
            </p>
          </div>

          <div className="grid md:grid-cols-3 gap-8 mb-16">
            {[
              {
                IconComponent: HelicopterIcon,
                title: 'Tours en Hélicoptère',
                description: 'Survolez les plus beaux paysages en hélicoptère privé avec champagne à bord',
                features: ['Pilote professionnel', 'Champagne à bord', 'Photos HD incluses', 'Durée flexible'],
                price: 'À partir de 500,000 XOF',
                image: 'https://images.unsplash.com/photo-1589519160732-57fc498494f8?w=600&h=400&fit=crop',
                badge: 'POPULAIRE'
              },
              {
                IconComponent: MotorcycleIcon,
                title: 'Location Quads & Motos',
                description: 'Explorez en toute liberté avec nos véhicules premium tout-terrain',
                features: ['Équipement complet', 'Assurance incluse', 'Guide disponible', 'Carburant inclus'],
                price: 'À partir de 75,000 XOF/jour',
                image: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&h=400&fit=crop',
                badge: 'NOUVEAU'
              },
              {
                IconComponent: JetIcon,
                title: 'Jets Privés',
                description: 'Voyagez en jet privé vers vos destinations de rêve en toute exclusivité',
                features: ['Service VIP complet', 'Flexibilité totale', 'Confort absolu', 'Catering premium'],
                price: 'Sur devis personnalisé',
                image: 'https://images.unsplash.com/photo-1540962351504-03099e0a754b?w=600&h=400&fit=crop',
                badge: 'LUXE'
              }
            ].map((service, idx) => (
              <div 
                key={idx}
                className="group relative rounded-3xl overflow-hidden shadow-2xl hover:shadow-purple-500/50 transition-all duration-700 hover:-translate-y-6 bg-white dark:bg-gray-900"
              >
                {/* Badge */}
                <div className="absolute top-6 right-6 z-10 px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-full text-xs font-black shadow-xl">
                  {service.badge}
                </div>

                <div className="aspect-[4/3] overflow-hidden relative">
                  <img 
                    src={service.image}
                    alt={service.title}
                    className="w-full h-full object-cover group-hover:scale-125 transition-transform duration-1000"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                  
                  <div className="absolute top-6 left-6 w-20 h-20 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border-4 border-white/30 group-hover:scale-110 transition-transform">
                    <service.IconComponent className="w-12 h-12 text-white" />
                  </div>
                </div>

                <div className="p-8">
                  <h3 className="text-3xl font-black text-gray-900 dark:text-white mb-4 group-hover:text-purple-600 transition-colors">
                    {service.title}
                  </h3>
                  <p className="text-gray-600 dark:text-gray-400 mb-6 text-lg">
                    {service.description}
                  </p>
                  
                  <div className="space-y-3 mb-6">
                    {service.features.map((feature, i) => (
                      <div key={i} className="flex items-center text-gray-700 dark:text-gray-300">
                        <CheckCircleIcon className="w-6 h-6 text-green-500 mr-3 flex-shrink-0" />
                        <span className="font-semibold">{feature}</span>
                      </div>
                    ))}
                  </div>

                  <div className="mb-6 p-5 bg-gradient-to-r from-purple-50 to-amber-50 dark:from-purple-900/30 dark:to-amber-900/30 rounded-2xl border-2 border-purple-200 dark:border-purple-700">
                    <div className="text-sm text-gray-600 dark:text-gray-400 mb-2 font-semibold">Tarif</div>
                    <div className="text-3xl font-black bg-gradient-to-r from-purple-600 to-amber-600 bg-clip-text text-transparent">
                      {service.price}
                    </div>
                  </div>

                  <Link
                    to="/packages"
                    className="flex items-center justify-center space-x-2 w-full py-4 bg-gradient-to-r from-purple-600 via-purple-700 to-amber-600 text-white font-black text-center rounded-2xl hover:shadow-2xl transition-all duration-300 transform group-hover:scale-105"
                  >
                    <span>RÉSERVER MAINTENANT</span>
                    <ArrowRightIcon className="w-5 h-5" />
                  </Link>
                </div>
              </div>
            ))}
          </div>

          {/* Packages de l'API */}
          {packages.length > 0 && (
            <div className="grid md:grid-cols-3 gap-8">
              {packages.slice(0, 3).map((pkg, idx) => (
                <Link 
                  key={idx}
                  to={`/packages/${pkg.id}`}
                  className="group relative rounded-3xl overflow-hidden shadow-2xl hover:shadow-amber-500/50 transition-all duration-700 hover:-translate-y-6 bg-white dark:bg-gray-900"
                >
                  <div className="aspect-[4/3] overflow-hidden relative">
                    <img 
                      src={pkg.image || `https://images.unsplash.com/photo-${1540962351504 + idx}-03099e0a754b?w=600&h=400&fit=crop`}
                      alt={pkg.title_fr || pkg.title}
                      className="w-full h-full object-cover group-hover:scale-125 transition-transform duration-1000"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    
                    <div className="absolute top-4 right-4 px-4 py-2 bg-gradient-to-r from-purple-600 to-amber-600 text-white rounded-full text-xs font-bold shadow-xl">
                      PREMIUM
                    </div>
                  </div>

                  <div className="p-6">
                    <h3 className="text-2xl font-black text-gray-900 dark:text-white mb-3 group-hover:text-purple-600 transition-colors">
                      {pkg.title_fr || pkg.title_en || pkg.title}
                    </h3>
                    <p className="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                      {pkg.description_fr || pkg.description_en || pkg.description}
                    </p>
                    
                    <div className="mb-4 p-4 bg-gradient-to-r from-purple-50 to-amber-50 dark:from-purple-900/20 dark:to-amber-900/20 rounded-2xl">
                      <div className="text-sm text-gray-600 dark:text-gray-400 mb-1">À partir de</div>
                      <div className="text-3xl font-black bg-gradient-to-r from-purple-600 to-amber-600 bg-clip-text text-transparent">
                        {pkg.price || 'Sur devis'}
                      </div>
                    </div>

                    <button className="flex items-center justify-center space-x-2 w-full py-4 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-2xl hover:shadow-2xl transition-all duration-300 transform group-hover:scale-105">
                      <span>Découvrir</span>
                      <ArrowRightIcon className="w-5 h-5" />
                    </button>
                  </div>
                </Link>
              ))}
            </div>
          )}
        </div>
      </section>

      {/* Véhicules de Luxe */}
      <section className="py-24 bg-gradient-to-br from-amber-900 via-amber-800 to-purple-900">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <div className="inline-flex items-center space-x-3 px-8 py-3 bg-white/20 backdrop-blur-md text-white rounded-full text-sm font-black mb-6 border-2 border-white/30">
              <CarIcon className="w-6 h-6" />
              <span>LOCATION DE VÉHICULES PREMIUM</span>
            </div>
            <h2 className="text-6xl md:text-7xl font-black text-white mb-6 leading-tight">
              Conduisez l'Excellence
            </h2>
            <p className="text-2xl text-white/90 max-w-3xl mx-auto">
              Quads • Motos de Luxe • Voitures de Sport • 4x4 Premium
            </p>
          </div>

          <div className="grid md:grid-cols-4 gap-6">
            {[
              { IconComponent: MotorcycleIcon, title: 'Quads Premium', desc: 'Location avec ou sans guide', image: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop' },
              { IconComponent: CarIcon, title: 'Voitures de Sport', desc: 'Ferrari, Lamborghini, Porsche', image: 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=400&h=300&fit=crop' },
              { IconComponent: CarIcon, title: '4x4 de Luxe', desc: 'Range Rover, G-Wagon', image: 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=400&h=300&fit=crop' },
              { IconComponent: MotorcycleIcon, title: 'Motos Premium', desc: 'Harley, Ducati, BMW', image: 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=400&h=300&fit=crop' }
            ].map((vehicle, idx) => (
              <Link
                key={idx}
                to="/packages"
                className="group relative rounded-3xl overflow-hidden shadow-2xl hover:shadow-amber-500/50 transition-all duration-500 hover:-translate-y-4"
              >
                <div className="aspect-square overflow-hidden relative">
                  <img 
                    src={vehicle.image}
                    alt={vehicle.title}
                    className="w-full h-full object-cover group-hover:scale-125 transition-transform duration-1000"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                  
                  <div className="absolute top-6 left-6 w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border-4 border-white/30">
                    <vehicle.IconComponent className="w-10 h-10 text-white" />
                  </div>
                </div>

                <div className="absolute bottom-0 left-0 right-0 p-6 text-white">
                  <h3 className="text-2xl font-black mb-2 group-hover:text-amber-400 transition-colors">
                    {vehicle.title}
                  </h3>
                  <p className="text-white/80 text-sm mb-4">{vehicle.desc}</p>
                  
                  <div className="flex items-center justify-between p-3 bg-white/10 backdrop-blur-md rounded-xl border border-white/20">
                    <span className="font-bold">Disponible</span>
                    <ArrowRightIcon className="w-5 h-5 group-hover:translate-x-2 transition-transform" />
                  </div>
                </div>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* Vols - Section secondaire */}
      <section className="py-20 bg-gray-100 dark:bg-gray-900">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center">
            <div className="inline-flex items-center justify-center space-x-3 mb-6">
              <JetIcon className="w-12 h-12 text-purple-600 dark:text-purple-400" />
            </div>
            <h2 className="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-6">
              Besoin d'un Vol ?
            </h2>
            <p className="text-xl text-gray-600 dark:text-gray-400 mb-8">
              Nous proposons également la réservation de vols internationaux
            </p>
            <Link 
              to="/flights"
              className="inline-flex items-center space-x-2 px-8 py-4 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-full hover:scale-105 transition-transform shadow-xl"
            >
              <span>Rechercher un Vol</span>
              <ArrowRightIcon className="w-5 h-5" />
            </Link>
          </div>
        </div>
      </section>

      {/* CTA Final */}
      <section className="py-24 bg-gradient-to-br from-purple-900 via-purple-800 to-amber-900">
        <div className="container mx-auto px-4 text-center">
          <h2 className="text-5xl md:text-6xl font-black text-white mb-8">
            Prêt pour une Expérience Inoubliable ?
          </h2>
          <p className="text-2xl text-white/90 mb-12 max-w-3xl mx-auto">
            Contactez notre conciergerie pour créer votre expérience sur mesure
          </p>
          <div className="flex flex-wrap justify-center gap-6">
            <Link 
              to="/events"
              className="inline-flex items-center space-x-2 px-10 py-5 bg-white text-purple-900 font-black text-lg rounded-full hover:scale-110 transition-all duration-300 shadow-2xl"
            >
              <TrophyIcon className="w-6 h-6" />
              <span>ÉVÉNEMENTS VIP</span>
            </Link>
            <Link 
              to="/packages"
              className="inline-flex items-center space-x-2 px-10 py-5 bg-gradient-to-r from-amber-500 to-pink-500 text-white font-black text-lg rounded-full hover:scale-110 transition-all duration-300 shadow-2xl"
            >
              <HelicopterIcon className="w-6 h-6" />
              <span>PACKAGES LUXE</span>
            </Link>
          </div>
        </div>
      </section>
    </div>
  );
};

export default HomeModern;
