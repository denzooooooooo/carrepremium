import React, { useState } from 'react';
import { useLanguage } from '../contexts/LanguageContext';

const Contact = () => {
  const { language } = useLanguage();
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    subject: 'general',
    message: ''
  });
  const [submitted, setSubmitted] = useState(false);

  const handleSubmit = (e) => {
    e.preventDefault();
    // Ici vous pouvez ajouter l'appel API
    console.log('Form submitted:', formData);
    setSubmitted(true);
    setTimeout(() => setSubmitted(false), 5000);
  };

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      {/* Hero */}
      <section className="relative h-[40vh] bg-gradient-to-r from-purple-600 to-amber-600 overflow-hidden">
        <div className="absolute inset-0 bg-black/20"></div>
        <div className="relative z-10 container-custom h-full flex flex-col justify-center px-4">
          <h1 className="text-5xl font-black text-white mb-4">Contactez-Nous</h1>
          <p className="text-xl text-white/90">Notre équipe est à votre écoute 24h/24, 7j/7</p>
        </div>
      </section>

      {/* Contact Info Cards */}
      <section className="py-12">
        <div className="container-custom">
          <div className="grid md:grid-cols-4 gap-6 -mt-20 relative z-20 mb-12">
            {[
              {
                icon: (
                  <svg className="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                  </svg>
                ),
                title: 'Téléphone',
                info: '+225 27 XX XX XX XX',
                subinfo: 'Lun-Dim: 24h/24'
              },
              {
                icon: (
                  <svg className="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                  </svg>
                ),
                title: 'Email',
                info: 'contact@carrepremium.com',
                subinfo: 'Réponse sous 24h'
              },
              {
                icon: (
                  <svg className="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fillRule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clipRule="evenodd" />
                  </svg>
                ),
                title: 'Adresse',
                info: 'Abidjan, Plateau',
                subinfo: 'Côte d\'Ivoire'
              },
              {
                icon: (
                  <svg className="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                  </svg>
                ),
                title: 'WhatsApp',
                info: '+225 07 XX XX XX XX',
                subinfo: 'Chat en direct'
              }
            ].map((item, index) => (
              <div key={index} className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2">
                <div className="w-16 h-16 bg-gradient-to-r from-purple-600 to-amber-600 rounded-2xl flex items-center justify-center text-white mb-4">
                  {item.icon}
                </div>
                <h3 className="text-lg font-bold mb-2">{item.title}</h3>
                <p className="text-purple-600 dark:text-purple-400 font-semibold mb-1">{item.info}</p>
                <p className="text-sm text-gray-600 dark:text-gray-400">{item.subinfo}</p>
              </div>
            ))}
          </div>

          <div className="grid lg:grid-cols-2 gap-12">
            {/* Contact Form */}
            <div className="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl">
              <h2 className="text-3xl font-black mb-6">Envoyez-nous un Message</h2>
              
              {submitted && (
                <div className="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border-2 border-green-500 rounded-xl">
                  <div className="flex items-center space-x-3">
                    <svg className="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                    </svg>
                    <p className="font-bold text-green-700 dark:text-green-400">Message envoyé avec succès ! Nous vous répondrons sous 24h.</p>
                  </div>
                </div>
              )}

              <form onSubmit={handleSubmit} className="space-y-6">
                <div>
                  <label className="block text-sm font-bold mb-2">Nom complet *</label>
                  <input
                    type="text"
                    name="name"
                    value={formData.name}
                    onChange={handleChange}
                    placeholder="Votre nom"
                    className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:border-purple-600 focus:outline-none"
                    required
                  />
                </div>

                <div className="grid md:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-sm font-bold mb-2">Email *</label>
                    <input
                      type="email"
                      name="email"
                      value={formData.email}
                      onChange={handleChange}
                      placeholder="email@exemple.com"
                      className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:border-purple-600 focus:outline-none"
                      required
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-bold mb-2">Téléphone</label>
                    <input
                      type="tel"
                      name="phone"
                      value={formData.phone}
                      onChange={handleChange}
                      placeholder="+225 XX XX XX XX XX"
                      className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:border-purple-600 focus:outline-none"
                    />
                  </div>
                </div>

                <div>
                  <label className="block text-sm font-bold mb-2">Sujet *</label>
                  <select
                    name="subject"
                    value={formData.subject}
                    onChange={handleChange}
                    className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:border-purple-600 focus:outline-none"
                    required
                  >
                    <option value="general">Question générale</option>
                    <option value="booking">Réservation</option>
                    <option value="payment">Paiement</option>
                    <option value="cancellation">Annulation</option>
                    <option value="complaint">Réclamation</option>
                    <option value="partnership">Partenariat</option>
                  </select>
                </div>

                <div>
                  <label className="block text-sm font-bold mb-2">Message *</label>
                  <textarea
                    name="message"
                    value={formData.message}
                    onChange={handleChange}
                    placeholder="Décrivez votre demande..."
                    rows="6"
                    className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:border-purple-600 focus:outline-none resize-none"
                    required
                  ></textarea>
                </div>

                <button
                  type="submit"
                  className="w-full py-4 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-2xl transition-all flex items-center justify-center space-x-2"
                >
                  <span>Envoyer le Message</span>
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M14 5l7 7m0 0l-7 7m7-7H3" />
                  </svg>
                </button>
              </form>
            </div>

            {/* FAQ & Info */}
            <div className="space-y-6">
              {/* FAQ */}
              <div className="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl">
                <h2 className="text-3xl font-black mb-6">Questions Fréquentes</h2>
                
                <div className="space-y-4">
                  {[
                    {
                      q: 'Comment réserver un vol ?',
                      a: 'Recherchez votre vol, sélectionnez vos options, remplissez les informations passagers et procédez au paiement sécurisé.'
                    },
                    {
                      q: 'Puis-je annuler ma réservation ?',
                      a: 'Oui, selon les conditions tarifaires. Les frais d\'annulation varient selon le type de billet.'
                    },
                    {
                      q: 'Quels moyens de paiement acceptez-vous ?',
                      a: 'Carte bancaire, Mobile Money (Orange Money, MTN Money, Moov Money), virement bancaire et PayPal.'
                    },
                    {
                      q: 'Comment recevoir mon billet ?',
                      a: 'Votre e-ticket sera envoyé par email immédiatement après confirmation du paiement.'
                    }
                  ].map((faq, index) => (
                    <details key={index} className="group">
                      <summary className="flex items-center justify-between cursor-pointer p-4 bg-gray-50 dark:bg-gray-900 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors">
                        <span className="font-bold">{faq.q}</span>
                        <svg className="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                        </svg>
                      </summary>
                      <p className="mt-3 px-4 text-gray-600 dark:text-gray-400">{faq.a}</p>
                    </details>
                  ))}
                </div>
              </div>

              {/* Horaires */}
              <div className="bg-gradient-to-r from-purple-600 to-amber-600 rounded-3xl p-8 shadow-xl text-white">
                <h3 className="text-2xl font-black mb-4">Horaires d'Ouverture</h3>
                <div className="space-y-3">
                  <div className="flex justify-between items-center pb-3 border-b border-white/20">
                    <span className="font-semibold">Lundi - Vendredi</span>
                    <span>08:00 - 20:00</span>
                  </div>
                  <div className="flex justify-between items-center pb-3 border-b border-white/20">
                    <span className="font-semibold">Samedi</span>
                    <span>09:00 - 18:00</span>
                  </div>
                  <div className="flex justify-between items-center pb-3 border-b border-white/20">
                    <span className="font-semibold">Dimanche</span>
                    <span>10:00 - 16:00</span>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="font-semibold">Urgences</span>
                    <span className="font-bold">24h/24</span>
                  </div>
                </div>
              </div>

              {/* Social Media */}
              <div className="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl">
                <h3 className="text-2xl font-black mb-4">Suivez-Nous</h3>
                <div className="grid grid-cols-4 gap-4">
                  {[
                    { name: 'Facebook', icon: 'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z', color: 'bg-blue-600' },
                    { name: 'Instagram', icon: 'M12 2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153a4.908 4.908 0 011.153 1.772c.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 01-1.153 1.772 4.915 4.915 0 01-1.772 1.153c-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 01-1.772-1.153 4.904 4.904 0 01-1.153-1.772c-.248-.637-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 011.153-1.772A4.897 4.897 0 015.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2z', color: 'bg-pink-600' },
                    { name: 'Twitter', icon: 'M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z', color: 'bg-blue-400' },
                    { name: 'LinkedIn', icon: 'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z M4 6a2 2 0 100-4 2 2 0 000 4z', color: 'bg-blue-700' }
                  ].map((social, index) => (
                    <button
                      key={index}
                      className={`${social.color} w-full aspect-square rounded-xl flex items-center justify-center text-white hover:scale-110 transition-transform`}
                    >
                      <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d={social.icon} />
                      </svg>
                    </button>
                  ))}
                </div>
              </div>
            </div>
          </div>

          {/* Map */}
          <div className="mt-12 bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl">
            <h2 className="text-3xl font-black mb-6">Notre Localisation</h2>
            <div className="aspect-video bg-gray-200 dark:bg-gray-700 rounded-2xl overflow-hidden">
              <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.2!2d-4.0!3d5.3!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNcKwMTgnMDAuMCJOIDTCsDAwJzAwLjAiVw!5e0!3m2!1sfr!2sci!4v1234567890"
                width="100%"
                height="100%"
                style={{ border: 0 }}
                allowFullScreen=""
                loading="lazy"
                referrerPolicy="no-referrer-when-downgrade"
              ></iframe>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default Contact;
