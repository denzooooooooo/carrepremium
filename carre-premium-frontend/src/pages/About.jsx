import React from 'react';

const About = () => {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      {/* Hero */}
      <section className="relative h-[50vh] bg-gradient-to-r from-purple-600 to-amber-600 overflow-hidden">
        <div className="absolute inset-0 bg-black/20"></div>
        <div className="relative z-10 container-custom h-full flex flex-col justify-center px-4">
          <h1 className="text-5xl font-black text-white mb-4">À Propos de Carré Premium</h1>
          <p className="text-xl text-white/90">Votre partenaire de confiance pour tous vos voyages</p>
        </div>
      </section>

      {/* Notre Histoire */}
      <section className="py-16">
        <div className="container-custom">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            <div>
              <h2 className="text-4xl font-black mb-6">Notre Histoire</h2>
              <p className="text-lg text-gray-600 dark:text-gray-400 mb-4">
                Fondée en 2020, <span className="font-bold text-purple-600">Carré Premium</span> est née d'une passion pour le voyage et d'un désir de rendre l'expérience de réservation simple, rapide et accessible à tous.
              </p>
              <p className="text-lg text-gray-600 dark:text-gray-400 mb-4">
                Basée à Abidjan, en Côte d'Ivoire, nous sommes rapidement devenus l'un des leaders de la billetterie en ligne en Afrique de l'Ouest, offrant des services de réservation de vols, d'événements sportifs et culturels, ainsi que des packages touristiques exclusifs.
              </p>
              <p className="text-lg text-gray-600 dark:text-gray-400">
                Aujourd'hui, nous servons des milliers de clients satisfaits chaque année et continuons d'innover pour offrir la meilleure expérience possible.
              </p>
            </div>
            <div className="relative h-96 rounded-3xl overflow-hidden shadow-2xl">
              <img 
                src="https://images.unsplash.com/photo-1556388158-158ea5ccacbd?w=800&h=600&fit=crop" 
                alt="Notre équipe"
                className="w-full h-full object-cover"
              />
            </div>
          </div>
        </div>
      </section>

      {/* Nos Valeurs */}
      <section className="py-16 bg-white dark:bg-gray-800">
        <div className="container-custom">
          <h2 className="text-4xl font-black text-center mb-12">Nos Valeurs</h2>
          <div className="grid md:grid-cols-3 gap-8">
            {[
              {
                icon: '🎯',
                title: 'Excellence',
                description: 'Nous nous engageons à fournir un service de qualité supérieure à chaque étape de votre voyage.'
              },
              {
                icon: '🤝',
                title: 'Confiance',
                description: 'La transparence et l\'honnêteté sont au cœur de notre relation avec nos clients.'
              },
              {
                icon: '💡',
                title: 'Innovation',
                description: 'Nous adoptons les dernières technologies pour améliorer constamment votre expérience.'
              },
              {
                icon: '🌍',
                title: 'Accessibilité',
                description: 'Rendre le voyage accessible à tous, partout et à tout moment.'
              },
              {
                icon: '⚡',
                title: 'Rapidité',
                description: 'Des réservations instantanées et un service client réactif 24/7.'
              },
              {
                icon: '🔒',
                title: 'Sécurité',
                description: 'Vos données et paiements sont protégés par les meilleurs systèmes de sécurité.'
              }
            ].map((value, index) => (
              <div key={index} className="bg-gray-50 dark:bg-gray-900 rounded-3xl p-8 hover:shadow-xl transition-all">
                <div className="text-5xl mb-4">{value.icon}</div>
                <h3 className="text-2xl font-bold mb-3">{value.title}</h3>
                <p className="text-gray-600 dark:text-gray-400">{value.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Nos Chiffres */}
      <section className="py-16">
        <div className="container-custom">
          <h2 className="text-4xl font-black text-center mb-12">Carré Premium en Chiffres</h2>
          <div className="grid md:grid-cols-4 gap-8">
            {[
              { number: '50K+', label: 'Clients Satisfaits' },
              { number: '200+', label: 'Destinations' },
              { number: '1000+', label: 'Événements' },
              { number: '24/7', label: 'Support Client' }
            ].map((stat, index) => (
              <div key={index} className="text-center p-8 bg-gradient-to-br from-purple-600 to-amber-600 rounded-3xl text-white">
                <div className="text-5xl font-black mb-2">{stat.number}</div>
                <div className="text-lg font-semibold">{stat.label}</div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Notre Équipe */}
      <section className="py-16 bg-white dark:bg-gray-800">
        <div className="container-custom">
          <h2 className="text-4xl font-black text-center mb-12">Notre Équipe</h2>
          <div className="grid md:grid-cols-4 gap-8">
            {[
              { name: 'Jean Kouassi', role: 'CEO & Fondateur', image: 'https://i.pravatar.cc/300?img=12' },
              { name: 'Marie Diallo', role: 'Directrice Commerciale', image: 'https://i.pravatar.cc/300?img=45' },
              { name: 'Paul Traoré', role: 'Directeur Technique', image: 'https://i.pravatar.cc/300?img=33' },
              { name: 'Sophie Kone', role: 'Responsable Client', image: 'https://i.pravatar.cc/300?img=47' }
            ].map((member, index) => (
              <div key={index} className="text-center group">
                <div className="relative mb-4 overflow-hidden rounded-3xl">
                  <img 
                    src={member.image} 
                    alt={member.name}
                    className="w-full aspect-square object-cover group-hover:scale-110 transition-transform duration-500"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
                <h3 className="text-xl font-bold mb-1">{member.name}</h3>
                <p className="text-purple-600 dark:text-purple-400 font-semibold">{member.role}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Nos Partenaires */}
      <section className="py-16">
        <div className="container-custom">
          <h2 className="text-4xl font-black text-center mb-12">Nos Partenaires</h2>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
            {[
              'Air France', 'Emirates', 'Turkish Airlines', 'Ethiopian Airlines',
              'Visa', 'Mastercard', 'Orange Money', 'MTN Mobile Money'
            ].map((partner, index) => (
              <div key={index} className="bg-white dark:bg-gray-800 rounded-2xl p-6 flex items-center justify-center shadow-lg hover:shadow-xl transition-all">
                <span className="text-lg font-bold text-gray-600 dark:text-gray-400">{partner}</span>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-16 bg-gradient-to-r from-purple-600 to-amber-600">
        <div className="container-custom text-center">
          <h2 className="text-4xl font-black text-white mb-6">Prêt à Voyager avec Nous ?</h2>
          <p className="text-xl text-white/90 mb-8">Rejoignez des milliers de voyageurs satisfaits</p>
          <div className="flex flex-wrap gap-4 justify-center">
            <a href="/flights" className="px-8 py-4 bg-white text-purple-600 font-bold rounded-xl hover:shadow-2xl transition-all">
              Réserver un Vol
            </a>
            <a href="/contact" className="px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-xl hover:bg-white hover:text-purple-600 transition-all">
              Nous Contacter
            </a>
          </div>
        </div>
      </section>
    </div>
  );
};

export default About;
