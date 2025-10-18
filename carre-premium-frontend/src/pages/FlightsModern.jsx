import React, { useState } from 'react';
import HeaderModern from '../components/layout/HeaderModern';
import FooterModern from '../components/layout/FooterModern';
import FlightSearch from '../components/flights/FlightSearch';
import FlightResults from '../components/flights/FlightResults';
import { useLanguage } from '../contexts/LanguageContext';
import { 
  JetIcon, 
  GlobeIcon, 
  LightningIcon, 
  ShieldIcon, 
  CheckCircleIcon,
  SparklesIcon,
  ArrowRightIcon
} from '../components/icons/ServiceIcons';

const FlightsModern = () => {
  const { t } = useLanguage();
  const [searchResults, setSearchResults] = useState(null);
  const [isLoading, setIsLoading] = useState(false);

  const handleSearchResults = (results) => {
    setSearchResults(results);
    setTimeout(() => {
      document.getElementById('results-section')?.scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
      });
    }, 100);
  };

  const handleLoading = (loading) => {
    setIsLoading(loading);
  };

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      <HeaderModern />
      
      {/* Hero Section - Bannière Premium */}
      <section className="relative h-[70vh] flex items-center justify-center overflow-hidden">
        {/* Image de fond avec overlay */}
        <div 
          className="absolute inset-0 bg-cover bg-center transform scale-105"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1920&h=1080&fit=crop')`,
          }}
        >
          <div className="absolute inset-0 bg-gradient-to-br from-purple-900/90 via-purple-800/80 to-amber-900/90"></div>
          
          {/* Effet de lumière animé */}
          <div className="absolute inset-0 opacity-30">
            <div className="absolute top-1/4 left-1/4 w-96 h-96 bg-amber-500 rounded-full filter blur-3xl animate-pulse"></div>
            <div className="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500 rounded-full filter blur-3xl animate-pulse" style={{animationDelay: '1s'}}></div>
          </div>
        </div>

        <div className="relative z-10 container mx-auto px-4 text-center">
          <div className="max-w-5xl mx-auto">
            {/* Badge Premium */}
            

            {/* Titre Principal */}
            <h1 className="text-6xl md:text-8xl font-black text-white mb-8 leading-tight">
              Trouvez Votre<br />
              <span className="bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent">
                Vol Idéal
              </span>
            </h1>
            
            {/* Sous-titre */}
            <p className="text-2xl md:text-3xl text-white/90 mb-12 font-light max-w-4xl mx-auto">
              Des milliers de vols disponibles dans le monde entier avec les meilleurs prix garantis
            </p>

            {/* Badges d'avantages */}
            
          </div>
        </div>

        {/* Indicateur de scroll */}
        <div className="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
          <div className="flex flex-col items-center text-white">
            <span className="text-sm font-semibold mb-2">Rechercher un vol</span>
            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
          </div>
        </div>
      </section>

      {/* Search Section - Formulaire de recherche */}
      <section className="relative z-20 -mt-32">
        <div className="container mx-auto px-4">
          <FlightSearch 
            onSearchResults={handleSearchResults}
            onLoading={handleLoading}
          />
        </div>
      </section>

      {/* Results Section */}
      {(searchResults || isLoading) && (
        <section id="results-section" className="py-16">
          <div className="container mx-auto px-4">
            <FlightResults 
              results={searchResults}
              loading={isLoading}
            />
          </div>
        </section>
      )}

      {/* Section Avantages - Affiché uniquement si pas de recherche */}
      {!searchResults && !isLoading && (
        <>
          <section className="py-24 bg-white dark:bg-gray-800">
            <div className="container mx-auto px-4">
              <div className="text-center mb-16">
                <div className="inline-flex items-center space-x-3 px-6 py-3 bg-gradient-to-r from-purple-100 to-amber-100 dark:from-purple-900/30 dark:to-amber-900/30 text-purple-900 dark:text-white rounded-full text-sm font-bold mb-6">
                  <SparklesIcon className="w-5 h-5" />
                  <span>POURQUOI CHOISIR CARRÉ PREMIUM</span>
                </div>
                <h2 className="text-5xl md:text-6xl font-black text-gray-900 dark:text-white mb-6">
                  La Meilleure Expérience<br />
                  <span className="bg-gradient-to-r from-purple-600 to-amber-600 bg-clip-text text-transparent">
                    de Réservation de Vols
                  </span>
                </h2>
                <p className="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                  Profitez d'un service premium avec des avantages exclusifs
                </p>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                {/* Avantage 1 - Couverture Mondiale */}
                <div className="group relative bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-3xl p-10 text-center hover:shadow-2xl hover:-translate-y-4 transition-all duration-500 border-2 border-purple-200 dark:border-purple-700">
                  <div className="absolute top-0 right-0 w-32 h-32 bg-purple-200 dark:bg-purple-800 rounded-full filter blur-2xl opacity-50 group-hover:opacity-70 transition-opacity"></div>
                  
                  <div className="relative">
                    <div className="w-24 h-24 bg-gradient-to-br from-purple-600 to-purple-700 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition-transform">
                      <GlobeIcon className="w-14 h-14 text-white" />
                    </div>
                    <h3 className="text-3xl font-black text-gray-900 dark:text-white mb-4">
                      Couverture Mondiale
                    </h3>
                    <p className="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                      Accédez à des milliers de vols dans le monde entier grâce à notre partenariat avec Amadeus GDS
                    </p>
                  </div>
                </div>

                {/* Avantage 2 - Réservation Instantanée */}
                <div className="group relative bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 rounded-3xl p-10 text-center hover:shadow-2xl hover:-translate-y-4 transition-all duration-500 border-2 border-amber-200 dark:border-amber-700">
                  <div className="absolute top-0 right-0 w-32 h-32 bg-amber-200 dark:bg-amber-800 rounded-full filter blur-2xl opacity-50 group-hover:opacity-70 transition-opacity"></div>
                  
                  <div className="relative">
                    <div className="w-24 h-24 bg-gradient-to-br from-amber-600 to-amber-700 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition-transform">
                      <LightningIcon className="w-14 h-14 text-white" />
                    </div>
                    <h3 className="text-3xl font-black text-gray-900 dark:text-white mb-4">
                      Réservation Instantanée
                    </h3>
                    <p className="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                      Confirmation immédiate avec PNR et billets électroniques envoyés directement par email
                    </p>
                  </div>
                </div>

                {/* Avantage 3 - Paiement Sécurisé */}
                <div className="group relative bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-3xl p-10 text-center hover:shadow-2xl hover:-translate-y-4 transition-all duration-500 border-2 border-green-200 dark:border-green-700">
                  <div className="absolute top-0 right-0 w-32 h-32 bg-green-200 dark:bg-green-800 rounded-full filter blur-2xl opacity-50 group-hover:opacity-70 transition-opacity"></div>
                  
                  <div className="relative">
                    <div className="w-24 h-24 bg-gradient-to-br from-green-600 to-green-700 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition-transform">
                      <ShieldIcon className="w-14 h-14 text-white" />
                    </div>
                    <h3 className="text-3xl font-black text-gray-900 dark:text-white mb-4">
                      Paiement Sécurisé
                    </h3>
                    <p className="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                      Transactions 100% sécurisées avec plusieurs options de paiement disponibles
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </section>

          {/* Section Destinations Populaires */}
          <section className="py-24 bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 relative overflow-hidden">
            <div className="absolute inset-0 opacity-20">
              <div className="absolute top-20 left-20 w-96 h-96 bg-amber-500 rounded-full filter blur-3xl animate-pulse"></div>
              <div className="absolute bottom-20 right-20 w-96 h-96 bg-pink-500 rounded-full filter blur-3xl animate-pulse" style={{animationDelay: '2s'}}></div>
            </div>

            <div className="container mx-auto px-4 relative z-10">
              <div className="text-center mb-16">
                <div className="inline-flex items-center space-x-3 px-8 py-3 bg-gradient-to-r from-amber-500 to-pink-500 text-white rounded-full text-sm font-black mb-6 shadow-2xl">
                  <GlobeIcon className="w-6 h-6" />
                  <span>DESTINATIONS POPULAIRES</span>
                </div>
                <h2 className="text-5xl md:text-6xl font-black text-white mb-6 leading-tight">
                  Où Souhaitez-Vous<br />
                  <span className="bg-gradient-to-r from-amber-400 to-pink-400 bg-clip-text text-transparent">
                    Voyager ?
                  </span>
                </h2>
                <p className="text-xl text-white/90 max-w-3xl mx-auto">
                  Découvrez nos destinations les plus prisées avec les meilleurs tarifs
                </p>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                {[
                  { 
                    city: 'Paris', 
                    country: 'France', 
                    code: 'CDG', 
                    image: 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=800&h=600&fit=crop', 
                    price: 450000,
                    description: 'La Ville Lumière vous attend'
                  },
                  { 
                    city: 'Dubai', 
                    country: 'Émirats Arabes Unis', 
                    code: 'DXB', 
                    image: 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800&h=600&fit=crop', 
                    price: 550000,
                    description: 'Luxe et modernité au Moyen-Orient'
                  },
                  { 
                    city: 'New York', 
                    country: 'États-Unis', 
                    code: 'JFK', 
                    image: 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?w=800&h=600&fit=crop', 
                    price: 750000,
                    description: 'La ville qui ne dort jamais'
                  },
                  { 
                    city: 'Londres', 
                    country: 'Royaume-Uni', 
                    code: 'LHR', 
                    image: 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=800&h=600&fit=crop', 
                    price: 480000,
                    description: 'Histoire et culture britannique'
                  },
                  { 
                    city: 'Tokyo', 
                    country: 'Japon', 
                    code: 'NRT', 
                    image: 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=800&h=600&fit=crop', 
                    price: 850000,
                    description: 'Tradition et technologie'
                  },
                  { 
                    city: 'Istanbul', 
                    country: 'Turquie', 
                    code: 'IST', 
                    image: 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?w=800&h=600&fit=crop', 
                    price: 420000,
                    description: 'Pont entre deux continents'
                  }
                ].map((dest, index) => (
                  <div 
                    key={index} 
                    className="group relative overflow-hidden rounded-3xl shadow-2xl hover:shadow-amber-500/50 transition-all duration-700 cursor-pointer hover:-translate-y-6"
                  >
                    <div className="relative h-96">
                      <img 
                        src={dest.image} 
                        alt={dest.city}
                        className="w-full h-full object-cover group-hover:scale-125 transition-transform duration-1000"
                      />
                      <div className="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>
                      
                      {/* Badge Code Aéroport */}
                      <div className="absolute top-6 right-6 px-4 py-2 bg-white/20 backdrop-blur-md rounded-full border border-white/30 text-white font-black text-sm">
                        {dest.code}
                      </div>

                      {/* Effet de brillance au hover */}
                      <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                    </div>
                    
                    <div className="absolute bottom-0 left-0 right-0 p-8 text-white">
                      <h3 className="text-4xl font-black mb-2 group-hover:text-amber-400 transition-colors">
                        {dest.city}
                      </h3>
                      <p className="text-white/80 mb-4 text-lg">{dest.country}</p>
                      <p className="text-white/70 mb-6 text-sm">{dest.description}</p>
                      
                      <div className="flex justify-between items-center p-4 bg-gradient-to-r from-amber-500/30 to-pink-500/30 backdrop-blur-md rounded-2xl border border-amber-400/50">
                        <div>
                          <div className="text-sm text-white/70 mb-1">À partir de</div>
                          <div className="text-2xl font-black text-white">
                            {dest.price.toLocaleString()} XOF
                          </div>
                        </div>
                        <button className="inline-flex items-center space-x-2 bg-white text-purple-900 px-6 py-3 rounded-full font-bold hover:scale-110 transition-transform shadow-xl">
                          <span>Rechercher</span>
                          <ArrowRightIcon className="w-5 h-5" />
                        </button>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </section>

          {/* Section Compagnies Aériennes Partenaires */}
          <section className="py-20 bg-white dark:bg-gray-800">
            <div className="container mx-auto px-4">
              <div className="text-center mb-12">
                <h2 className="text-4xl font-black text-gray-900 dark:text-white mb-4">
                  Nos Compagnies Partenaires
                </h2>
                <p className="text-xl text-gray-600 dark:text-gray-400">
                  Accédez aux meilleures compagnies aériennes du monde
                </p>
              </div>

              <div className="grid grid-cols-2 md:grid-cols-6 gap-8 items-center opacity-60 dark:opacity-40">
                {['Air France', 'Emirates', 'Qatar Airways', 'Turkish Airlines', 'Lufthansa', 'British Airways'].map((airline, idx) => (
                  <div key={idx} className="text-center p-6 bg-gray-100 dark:bg-gray-700 rounded-2xl hover:opacity-100 transition-opacity">
                    <div className="text-2xl font-black text-gray-700 dark:text-gray-300">
                      {airline}
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </section>

          {/* Section Garanties */}
          <section className="py-24 bg-gradient-to-br from-purple-900 to-amber-900">
            <div className="container mx-auto px-4">
              <div className="max-w-5xl mx-auto">
                <div className="text-center mb-16">
                  <h2 className="text-5xl font-black text-white mb-6">
                    Nos Garanties
                  </h2>
                  <p className="text-2xl text-white/90">
                    Votre satisfaction est notre priorité
                  </p>
                </div>

                <div className="grid md:grid-cols-2 gap-8">
                  {[
                    {
                      IconComponent: ShieldIcon,
                      title: 'Meilleur Prix Garanti',
                      description: 'Si vous trouvez moins cher ailleurs, nous alignons notre prix'
                    },
                    {
                      IconComponent: CheckCircleIcon,
                      title: 'Modification Gratuite',
                      description: 'Modifiez votre réservation sans frais supplémentaires (selon conditions)'
                    },
                    {
                      IconComponent: LightningIcon,
                      title: 'Confirmation Immédiate',
                      description: 'Recevez votre confirmation et vos billets en quelques minutes'
                    },
                    {
                      IconComponent: SparklesIcon,
                      title: 'Service Client Premium',
                      description: 'Une équipe dédiée disponible 24/7 pour vous assister'
                    }
                  ].map((guarantee, idx) => (
                    <div key={idx} className="flex items-start space-x-6 bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20 hover:bg-white/20 transition-all">
                      <div className="flex-shrink-0">
                        <div className="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                          <guarantee.IconComponent className="w-10 h-10 text-white" />
                        </div>
                      </div>
                      <div>
                        <h3 className="text-2xl font-black text-white mb-3">
                          {guarantee.title}
                        </h3>
                        <p className="text-white/80 text-lg">
                          {guarantee.description}
                        </p>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </section>

          {/* CTA Final */}
          <section className="py-20 bg-white dark:bg-gray-800">
            <div className="container mx-auto px-4">
              <div className="max-w-4xl mx-auto text-center bg-gradient-to-r from-purple-600 to-amber-600 rounded-3xl p-12 shadow-2xl">
                <JetIcon className="w-20 h-20 text-white mx-auto mb-6" />
                <h2 className="text-4xl md:text-5xl font-black text-white mb-6">
                  Prêt à Décoller ?
                </h2>
                <p className="text-xl text-white/90 mb-8">
                  Commencez votre recherche maintenant et trouvez le vol parfait
                </p>
                <button 
                  onClick={() => window.scrollTo({ top: 0, behavior: 'smooth' })}
                  className="inline-flex items-center space-x-3 px-10 py-5 bg-white text-purple-900 font-black text-lg rounded-full hover:scale-110 transition-all duration-300 shadow-2xl"
                >
                  <span>RECHERCHER UN VOL</span>
                  <ArrowRightIcon className="w-6 h-6" />
                </button>
              </div>
            </div>
          </section>
        </>
      )}

      <FooterModern />
    </div>
  );
};

export default FlightsModern;
