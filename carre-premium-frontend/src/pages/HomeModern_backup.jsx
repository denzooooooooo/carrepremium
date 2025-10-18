import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useCurrency } from '../contexts/CurrencyContext';
import { eventService, packageService } from '../services/api';

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
      {/* Hero */}
      <section className="relative h-screen flex items-center justify-center overflow-hidden">
        <div className="absolute inset-0 bg-cover bg-center" style={{backgroundImage: "url('https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=1920&h=1080&fit=crop')"}}>
          <div className="absolute inset-0 bg-gradient-to-b from-purple-900/70 via-purple-800/60 to-black/80"></div>
        </div>

        <div className="relative z-10 container mx-auto px-4 text-center">
          <div className="max-w-5xl mx-auto">
            <div className="inline-block px-8 py-3 bg-gradient-to-r from-amber-500 to-pink-500 text-white rounded-full text-sm font-black mb-8 shadow-2xl animate-pulse">
              �� ÉVÉNEMENTS EXCLUSIFS • 🚁 HÉLICOPTÈRES • 🏎️ QUADS & LUXE
            </div>

            <h1 className="text-6xl md:text-8xl font-black text-white mb-8 leading-tight">
              Vivez l'Extraordinaire<br />
              <span className="bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent">Avec Carré Premium</span>
            </h1>
            
            <p className="text-2xl md:text-3xl text-white/90 mb-12 font-light">
              Événements VIP • Hélicoptères • Quads • Jets Privés • Expériences Uniques
            </p>

            <div className="flex flex-wrap justify-center gap-6 mb-12">
              <Link to="/events" className="group px-10 py-5 bg-gradient-to-r from-amber-500 to-pink-500 text-white font-black text-lg rounded-full hover:scale-110 transition-all duration-300 shadow-2xl flex items-center space-x-3">
                <span>🏆 ÉVÉNEMENTS VIP</span>
                <svg className="w-6 h-6 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </Link>
              <Link to="/packages" className="group px-10 py-5 bg-white/10 backdrop-blur-md text-white font-black text-lg rounded-full border-4 border-white/30 hover:bg-white/20 hover:scale-110 transition-all duration-300 flex items-center space-x-3">
                <span>🚁 PACKAGES LUXE</span>
                <svg className="w-6 h-6 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </Link>
            </div>

            <div className="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
              {[
                { icon: '🎾', title: 'Roland Garros', subtitle: 'Tennis VIP' },
                { icon: '🏎️', title: 'Formule 1', subtitle: 'Paddock Access' },
                { icon: '🚁', title: 'Hélicoptère', subtitle: 'Tours Privés' },
                { icon: '🏍️', title: 'Quads & Luxe', subtitle: 'Location Premium' }
              ].map((service, idx) => (
                <div key={idx} className="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all hover:scale-105">
                  <div className="text-5xl mb-3">{service.icon}</div>
                  <div className="text-white font-bold text-lg">{service.title}</div>
                  <div className="text-white/70 text-sm">{service.subtitle}</div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Événements */}
      <section className="py-24 bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 relative overflow-hidden">
        <div className="absolute inset-0 opacity-20">
          <div className="absolute top-20 left-20 w-96 h-96 bg-amber-500 rounded-full filter blur-3xl animate-pulse"></div>
        </div>

        <div className="container mx-auto px-4 relative z-10">
          <div className="text-center mb-16">
            <div className="inline-block px-8 py-3 bg-gradient-to-r from-amber-500 to-pink-500 text-white rounded-full text-sm font-black mb-6 shadow-2xl animate-pulse">
              🏆 ÉVÉNEMENTS SPORTIFS & CULTURELS EXCLUSIFS
            </div>
            <h2 className="text-6xl md:text-7xl font-black text-white mb-6 leading-tight">
              Vivez les Plus Grands<br />
              <span className="bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent">Événements du Monde</span>
            </h2>
          </div>

          <div className="grid md:grid-cols-4 gap-6 mb-12">
            {events.slice(0, 8).map((event, idx) => (
              <Link key={idx} to={`/events/${event.id}`} className="group relative rounded-3xl overflow-hidden shadow-2xl hover:shadow-amber-500/50 transition-all duration-700 hover:-translate-y-6">
                <div className="aspect-[3/4] overflow-hidden relative">
                  <img src={event.image || `https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=400&h=600&fit=crop`} alt={event.title_fr || event.title} className="w-full h-full object-cover group-hover:scale-125 transition-all duration-1000" />
                  <div className="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>
                  <div className="absolute top-4 right-4 px-4 py-2 bg-gradient-to-r from-amber-500 to-pink-500 rounded-full text-xs font-black uppercase text-white shadow-2xl">{event.event_type || 'VIP'}</div>
                  {idx < 2 && <div className="absolute top-4 left-4 px-3 py-1 bg-red-500 rounded-full text-xs font-black text-white shadow-xl animate-pulse">🔥 HOT</div>}
                </div>
                <div className="absolute bottom-0 left-0 right-0 p-6 text-white">
                  <h3 className="text-xl font-black mb-3 line-clamp-2">{event.title_fr || event.title || 'Événement'}</h3>
                  <div className="flex items-center justify-between p-3 bg-gradient-to-r from-amber-500/30 to-pink-500/30 backdrop-blur-md rounded-2xl border border-amber-400/50">
                    <span className="text-lg font-black bg-gradient-to-r from-amber-300 to-pink-300 bg-clip-text text-transparent">{event.min_price || 'Sur demande'}</span>
                  </div>
                </div>
              </Link>
            ))}
          </div>

          <div className="text-center">
            <Link to="/events" className="inline-flex items-center px-12 py-6 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-500 text-white font-black text-xl rounded-full hover:scale-110 transition-all duration-300 shadow-2xl space-x-3">
              <span>VOIR TOUS LES ÉVÉNEMENTS</span>
            </Link>
          </div>
        </div>
      </section>

      {/* Packages */}
      <section className="py-24 bg-white dark:bg-gray-800">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <h2 className="text-6xl font-black text-gray-900 dark:text-white mb-6">Packages Premium</h2>
          </div>
          <div className="grid md:grid-cols-3 gap-8">
            {packages.slice(0, 3).map((pkg, idx) => (
              <Link key={idx} to={`/packages/${pkg.id}`} className="group rounded-3xl overflow-hidden shadow-2xl hover:-translate-y-6 transition-all duration-700 bg-white dark:bg-gray-900">
                <div className="aspect-[4/3] overflow-hidden">
                  <img src={pkg.image || 'https://via.placeholder.com/600x400'} alt={pkg.title_fr} className="w-full h-full object-cover group-hover:scale-125 transition-transform duration-1000" />
                </div>
                <div className="p-6">
                  <h3 className="text-2xl font-black mb-3">{pkg.title_fr || pkg.title}</h3>
                  <button className="w-full py-4 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-2xl">Découvrir</button>
                </div>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-24 bg-gradient-to-br from-purple-900 to-amber-900">
        <div className="container mx-auto px-4 text-center">
          <h2 className="text-5xl font-black text-white mb-8">Prêt pour une Expérience Inoubliable ?</h2>
          <Link to="/events" className="px-10 py-5 bg-white text-purple-900 font-black text-lg rounded-full hover:scale-110 transition-all shadow-2xl inline-block">ÉVÉNEMENTS VIP</Link>
        </div>
      </section>
    </div>
  );
};

export default HomeModern;