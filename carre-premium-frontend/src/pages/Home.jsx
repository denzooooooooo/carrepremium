import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Pagination, Navigation, EffectFade } from 'swiper/modules';
import { useLanguage } from '../contexts/LanguageContext';
import { useCurrency } from '../contexts/CurrencyContext';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';
import 'swiper/css/effect-fade';

const Home = () => {
  const { t } = useLanguage();
  const { formatPrice } = useCurrency();
  const [activeSlide, setActiveSlide] = useState(0);

  // Hero Carousel Data with real travel images
  const heroSlides = [
    {
      id: 1,
      title: "Découvrez le Monde",
      subtitle: "Vols Premium vers 500+ Destinations",
      description: "Réservez vos billets d'avion aux meilleurs tarifs",
      cta: "Réserver un Vol",
      ctaLink: "/flights",
      gradient: "from-purple-900 via-purple-800 to-indigo-900"
    },
    {
      id: 2,
      title: "Événements Exclusifs",
      subtitle: "Roland Garros • Champions League • Concerts",
      description: "Accédez aux plus grands événements du monde",
      cta: "Voir les Événements",
      ctaLink: "/events",
      gradient: "from-yellow-900 via-yellow-800 to-orange-900"
    },
    {
      id: 3,
      title: "Voyages de Luxe",
      subtitle: "Jets Privés • Hélicoptères • Packages VIP",
      description: "Vivez des expériences uniques et sur-mesure",
      cta: "Explorer les Packages",
      ctaLink: "/packages",
      gradient: "from-pink-900 via-pink-800 to-rose-900"
    }
  ];

  const destinations = [
    { city: 'Paris', country: 'France', price: '450000', emoji: '🗼', desc: 'La Ville Lumière' },
    { city: 'Dubai', country: 'UAE', price: '850000', emoji: '🏙️', desc: 'Luxe et Modernité' },
    { city: 'New York', country: 'USA', price: '1200000', emoji: '🗽', desc: 'The Big Apple' },
    { city: 'Tokyo', country: 'Japon', price: '1100000', emoji: '🗾', desc: 'Tradition & Tech' }
  ];

  return (
    <div className="min-h-screen">
      {/* Hero Carousel */}
      <section className="relative h-screen">
        <Swiper
          modules={[Autoplay, Pagination, Navigation, EffectFade]}
          effect="fade"
          speed={1500}
          autoplay={{ delay: 5000, disableOnInteraction: false }}
          pagination={{ clickable: true }}
          navigation={true}
          loop={true}
          onSlideChange={(swiper) => setActiveSlide(swiper.realIndex)}
          className="h-full"
        >
          {heroSlides.map((slide, index) => (
            <SwiperSlide key={slide.id}>
              <div className="relative h-full">
                <div className={`absolute inset-0 bg-gradient-to-br ${slide.gradient}`}></div>
                
                <div className="relative h-full flex items-center justify-center z-10">
                  <div className="container-custom text-center px-4">
                    <div className={`transform transition-all duration-1000 ${activeSlide === index ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'}`}>
                      <div className="inline-block mb-6 px-6 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20">
                        <span className="text-yellow-400 font-semibold">✨ {slide.subtitle}</span>
                      </div>
                      
                      <h1 className="text-5xl md:text-7xl lg:text-8xl font-black text-white mb-6 leading-tight">
                        {slide.title}
                      </h1>
                      
                      <p className="text-xl md:text-2xl text-gray-200 mb-12 max-w-3xl mx-auto">
                        {slide.description}
                      </p>

                      <Link 
                        to={slide.ctaLink}
                        className="inline-block px-10 py-5 bg-gradient-to-r from-yellow-400 to-yellow-500 text-purple-900 font-bold rounded-2xl text-lg shadow-2xl hover:shadow-yellow-500/50 transition-all duration-300 hover:scale-105"
                      >
                        {slide.cta}
                        <svg className="inline-block ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                      </Link>
                    </div>
                  </div>
                </div>

                {/* Stats */}
                <div className="absolute bottom-20 left-0 right-0 z-20">
                  <div className="container-custom">
                    <div className="grid grid-cols-3 gap-4 md:gap-8 max-w-4xl mx-auto">
                      <div className="text-center bg-white/10 backdrop-blur-md rounded-2xl p-4 md:p-6 border border-white/20">
                        <div className="text-3xl md:text-5xl font-black text-yellow-400 mb-2">10K+</div>
                        <div className="text-white text-xs md:text-sm font-medium">Clients Satisfaits</div>
                      </div>
                      <div className="text-center bg-white/10 backdrop-blur-md rounded-2xl p-4 md:p-6 border border-white/20">
                        <div className="text-3xl md:text-5xl font-black text-yellow-400 mb-2">500+</div>
                        <div className="text-white text-xs md:text-sm font-medium">Destinations</div>
                      </div>
                      <div className="text-center bg-white/10 backdrop-blur-md rounded-2xl p-4 md:p-6 border border-white/20">
                        <div className="text-3xl md:text-5xl font-black text-yellow-400 mb-2">24/7</div>
                        <div className="text-white text-xs md:text-sm font-medium">Support Client</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </SwiperSlide>
          ))}
        </Swiper>
      </section>

      {/* Quick Search */}
      <section className="relative -mt-20 z-30 pb-12">
        <div className="container-custom">
          <div className="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 max-w-6xl mx-auto">
            <div className="grid md:grid-cols-4 gap-4">
              <div>
                <label className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                  📍 Départ
                </label>
                <input 
                  type="text" 
                  placeholder="Abidjan (ABJ)"
                  className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-purple-500 focus:outline-none"
                />
              </div>
              <div>
                <label className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                  📍 Arrivée
                </label>
                <input 
                  type="text" 
                  placeholder="Paris (CDG)"
                  className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-purple-500 focus:outline-none"
                />
              </div>
              <div>
                <label className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                  📅 Date
                </label>
                <input 
                  type="date" 
                  className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:border-purple-500 focus:outline-none"
                />
              </div>
              <div className="flex items-end">
                <Link 
                  to="/flights"
                  className="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-300 hover:scale-105 shadow-lg text-center"
                >
                  Rechercher
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Services */}
      <section className="py-24 bg-gray-50 dark:bg-gray-900">
        <div className="container-custom">
          <div className="text-center mb-16">
            <span className="inline-block px-4 py-2 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full text-sm font-semibold mb-4">
              NOS SERVICES
            </span>
            <h2 className="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
              Voyagez en Toute Sérénité
            </h2>
            <p className="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
              Des solutions complètes pour tous vos besoins de voyage
            </p>
          </div>

          <div className="grid md:grid-cols-3 gap-8">
            {[
              {
                title: 'Vols Premium',
                desc: 'Réservez vers 500+ destinations. Économie, Business ou Première Classe.',
                icon: '✈️',
                link: '/flights',
                color: 'purple',
                features: ['Meilleurs tarifs', 'Réservation instantanée', 'Modification flexible']
              },
              {
                title: 'Événements VIP',
                desc: 'Roland Garros, Champions League, Formule 1, concerts et plus.',
                icon: '🎫',
                link: '/events',
                color: 'yellow',
                features: ['Places VIP', 'Billets authentiques', 'Livraison sécurisée']
              },
              {
                title: 'Packages Luxe',
                desc: 'Jets privés, hélicoptères, safaris. Expériences sur-mesure.',
                icon: '🌍',
                link: '/packages',
                color: 'pink',
                features: ['Sur-mesure', 'Conciergerie 24/7', 'Tout inclus']
              }
            ].map((service, idx) => (
              <Link key={idx} to={service.link} className="group">
                <div className="relative h-full bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                  <div className="text-6xl mb-6">{service.icon}</div>
                  <h3 className="text-2xl font-bold text-gray-900 dark:text-white mb-3">{service.title}</h3>
                  <p className="text-gray-600 dark:text-gray-400 mb-6">{service.desc}</p>
                  
                  <ul className="space-y-2 mb-6">
                    {service.features.map((feature, i) => (
                      <li key={i} className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <span className="text-green-500 mr-2">✓</span>
                        {feature}
                      </li>
                    ))}
                  </ul>
                  
                  <div className={`flex items-center text-${service.color}-600 dark:text-${service.color}-400 font-semibold group-hover:gap-3 gap-2 transition-all`}>
                    <span>En savoir plus</span>
                    <span>→</span>
                  </div>
                </div>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* Destinations */}
      <section className="py-24 bg-white dark:bg-gray-800">
        <div className="container-custom">
          <div className="text-center mb-16">
            <span className="inline-block px-4 py-2 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 rounded-full text-sm font-semibold mb-4">
              DESTINATIONS POPULAIRES
            </span>
            <h2 className="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
              Où Voulez-Vous Aller ?
            </h2>
          </div>

          <div className="grid md:grid-cols-4 gap-6">
            {destinations.map((dest, idx) => (
              <Link key={idx} to="/flights" className="group">
                <div className="relative h-80 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 hover:scale-105">
                  <div className="absolute inset-0 bg-gradient-to-br from-purple-600 to-pink-600 opacity-90 group-hover:opacity-80 transition-opacity"></div>
                  
                  <div className="relative h-full flex flex-col justify-end p-6 text-white">
                    <div className="text-5xl mb-4">{dest.emoji}</div>
                    <h3 className="text-3xl font-bold mb-1">{dest.city}</h3>
                    <p className="text-white/80 text-sm mb-2">{dest.country}</p>
                    <p className="text-white/60 text-xs mb-4">{dest.desc}</p>
                    <div className="flex items-center justify-between">
                      <div>
                        <span className="text-xs opacity-80">À partir de</span>
                        <div className="text-xl font-bold">{formatPrice(dest.price)} XOF</div>
                      </div>
                      <div className="w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center">
                        →
                      </div>
                    </div>
                  </div>
                </div>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* Why Choose Us */}
      <section className="py-24 bg-gradient-to-br from-purple-900 via-purple-800 to-indigo-900 text-white">
        <div className="container-custom">
          <div className="text-center mb-16">
            <h2 className="text-4xl md:text-5xl font-black mb-4">
              Pourquoi Carré Premium ?
            </h2>
            <p className="text-xl text-gray-200">
              L'excellence à chaque étape de votre voyage
            </p>
          </div>

          <div className="grid md:grid-cols-4 gap-8">
            {[
              { icon: '🛡️', title: 'Paiement Sécurisé', desc: '100% sécurisé et crypté' },
              { icon: '⚡', title: 'Réservation Rapide', desc: 'En moins de 2 minutes' },
              { icon: '💎', title: 'Prix Garantis', desc: 'Meilleurs tarifs du marché' },
              { icon: '🎯', title: 'Support 24/7', desc: 'Assistance à tout moment' }
            ].map((feature, idx) => (
              <div key={idx} className="text-center">
                <div className="text-5xl mb-4">{feature.icon}</div>
                <h3 className="text-xl font-bold mb-2">{feature.title}</h3>
                <p className="text-gray-300">{feature.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Newsletter */}
      <section className="py-24 bg-gray-50 dark:bg-gray-900">
        <div className="container-custom max-w-4xl mx-auto text-center">
          <h2 className="text-4xl font-black text-gray-900 dark:text-white mb-4">
            Restez Informé
          </h2>
          <p className="text-xl text-gray-600 dark:text-gray-400 mb-8">
            Recevez nos meilleures offres directement dans votre boîte mail
          </p>
          
          <div className="flex flex-col sm:flex-row gap-4 max-w-2xl mx-auto">
            <input 
              type="email" 
              placeholder="Votre adresse email"
              className="flex-1 px-6 py-4 rounded-2xl border-2 border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-purple-500 focus:outline-none text-lg"
            />
            <button className="px-8 py-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold rounded-2xl hover:from-purple-700 hover:to-purple-800 transition-all duration-300 hover:scale-105 shadow-xl">
              S'abonner
            </button>
          </div>
        </div>
      </section>
    </div>
  );
};

export default Home;
