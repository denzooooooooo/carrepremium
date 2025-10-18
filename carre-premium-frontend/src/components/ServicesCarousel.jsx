import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { TrophyIcon, HelicopterIcon, MotorcycleIcon, JetIcon, MusicIcon, CheckCircleIcon } from './icons/ServiceIcons';

const ServicesCarousel = () => {
  const [currentSlide, setCurrentSlide] = useState(0);

  const slides = [
    {
      title: 'Événements Sportifs VIP',
      subtitle: 'Accès Privilégiés aux Plus Grands Événements',
      description: 'Roland Garros, Champions League, Formule 1 - Places VIP et accès paddock',
      image: 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=1920&h=1080&fit=crop',
      IconComponent: TrophyIcon,
      link: '/events',
      features: ['Places VIP', 'Accès Paddock', 'Rencontres exclusives']
    },
    {
      title: 'Tours en Hélicoptère',
      subtitle: 'Survolez les Plus Beaux Paysages',
      description: 'Expérience unique en hélicoptère privé avec champagne et photos professionnelles',
      image: 'https://images.unsplash.com/photo-1589519160732-57fc498494f8?w=1920&h=1080&fit=crop',
      IconComponent: HelicopterIcon,
      link: '/packages',
      features: ['Pilote professionnel', 'Champagne inclus', 'Photos HD offertes']
    },
    {
      title: 'Quads & Motos Premium',
      subtitle: 'Aventure et Adrénaline',
      description: 'Location de quads et motos de luxe avec équipement complet et guide expérimenté',
      image: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1920&h=1080&fit=crop',
      IconComponent: MotorcycleIcon,
      link: '/packages',
      features: ['Équipement complet', 'Assurance incluse', 'Guide disponible']
    },
    {
      title: 'Jets Privés',
      subtitle: 'Voyagez en Toute Exclusivité',
      description: 'Service de jet privé sur mesure vers toutes vos destinations de rêve',
      image: 'https://images.unsplash.com/photo-1540962351504-03099e0a754b?w=1920&h=1080&fit=crop',
      IconComponent: JetIcon,
      link: '/packages',
      features: ['Service VIP complet', 'Flexibilité totale', 'Confort absolu']
    },
    {
      title: 'Concerts & Festivals',
      subtitle: 'Les Meilleurs Artistes du Monde',
      description: 'Billets VIP pour concerts et festivals internationaux avec backstage access',
      image: 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=1920&h=1080&fit=crop',
      IconComponent: MusicIcon,
      link: '/events',
      features: ['Backstage access', 'Meet & Greet', 'Places premium']
    }
  ];

  // Auto-play
  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % slides.length);
    }, 6000);
    return () => clearInterval(timer);
  }, [slides.length]);

  const nextSlide = () => setCurrentSlide((prev) => (prev + 1) % slides.length);
  const prevSlide = () => setCurrentSlide((prev) => (prev - 1 + slides.length) % slides.length);
  const goToSlide = (index) => setCurrentSlide(index);

  return (
    <div className="relative h-screen overflow-hidden">
      {/* Slides */}
      {slides.map((slide, idx) => (
        <div
          key={idx}
          className={`absolute inset-0 transition-opacity duration-1000 ${
            idx === currentSlide ? 'opacity-100 z-10' : 'opacity-0 z-0'
          }`}
        >
          {/* Background Image */}
          <div 
            className="absolute inset-0 bg-cover bg-center transform scale-105"
            style={{backgroundImage: `url('${slide.image}')`}}
          >
            <div className="absolute inset-0 bg-gradient-to-b from-purple-900/80 via-purple-800/70 to-black/90"></div>
          </div>

          {/* Content */}
          <div className="relative z-10 h-full flex items-center">
            <div className="container mx-auto px-4">
              <div className="max-w-5xl mx-auto text-center">
                <div 
                  className={`transition-all duration-700 delay-300 ${
                    idx === currentSlide ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
                  }`}
                >
                  {/* Badge avec icône SVG */}
                  <div className="inline-flex items-center space-x-3 px-8 py-3 bg-gradient-to-r from-amber-500 to-pink-500 text-white rounded-full text-sm font-black mb-8 shadow-2xl">
                    <slide.IconComponent className="w-6 h-6" />
                    <span>{slide.title.toUpperCase()}</span>
                  </div>

                  {/* Title */}
                  <h1 className="text-6xl md:text-8xl font-black text-white mb-6 leading-tight">
                    {slide.subtitle.split(' ').slice(0, 2).join(' ')}<br />
                    <span className="bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent">
                      {slide.subtitle.split(' ').slice(2).join(' ')}
                    </span>
                  </h1>
                  
                  {/* Description */}
                  <p className="text-2xl md:text-3xl text-white/90 mb-12 font-light max-w-4xl mx-auto">
                    {slide.description}
                  </p>

                  {/* Features avec icônes SVG */}
                  <div className="flex flex-wrap justify-center gap-4 mb-12">
                    {slide.features.map((feature, i) => (
                      <div key={i} className="inline-flex items-center space-x-2 px-6 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 text-white font-semibold">
                        <CheckCircleIcon className="w-5 h-5 text-green-400" />
                        <span>{feature}</span>
                      </div>
                    ))}
                  </div>

                  {/* CTA Button */}
                  <Link 
                    to={slide.link}
                    className="inline-flex items-center px-12 py-6 bg-gradient-to-r from-amber-500 to-pink-500 text-white font-black text-xl rounded-full hover:scale-110 transition-all duration-300 shadow-2xl space-x-3"
                  >
                    <span>DÉCOUVRIR</span>
                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      ))}

      {/* Navigation Controls */}
      <div className="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 flex items-center space-x-4">
        {/* Previous Button */}
        <button 
          onClick={prevSlide}
          className="w-14 h-14 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center hover:bg-white/30 transition-all border-2 border-white/30 group"
          aria-label="Slide précédent"
        >
          <svg className="w-6 h-6 text-white group-hover:scale-125 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        {/* Indicators */}
        <div className="flex space-x-2">
          {slides.map((_, idx) => (
            <button
              key={idx}
              onClick={() => goToSlide(idx)}
              className={`h-3 rounded-full transition-all duration-300 ${
                idx === currentSlide ? 'w-12 bg-white' : 'w-3 bg-white/50 hover:bg-white/70'
              }`}
              aria-label={`Aller au slide ${idx + 1}`}
            />
          ))}
        </div>

        {/* Next Button */}
        <button 
          onClick={nextSlide}
          className="w-14 h-14 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center hover:bg-white/30 transition-all border-2 border-white/30 group"
          aria-label="Slide suivant"
        >
          <svg className="w-6 h-6 text-white group-hover:scale-125 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>

      {/* Scroll Indicator */}
      <div className="absolute bottom-24 left-1/2 transform -translate-x-1/2 z-20 animate-bounce">
        <div className="flex flex-col items-center">
          <span className="text-white text-sm font-semibold mb-2">Défiler</span>
          <svg className="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 14l-7 7m0 0l-7-7m7 7V3" />
          </svg>
        </div>
      </div>
    </div>
  );
};

export default ServicesCarousel;
