import React from 'react';
import { Link } from 'react-router-dom';
import { useLanguage } from '../../contexts/LanguageContext';

const Footer = () => {
  const { t } = useLanguage();
  const currentYear = new Date().getFullYear();

  return (
    <footer className="bg-primary text-white">
      <div className="container-custom py-12">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {/* About */}
          <div>
            <h3 className="text-2xl font-montserrat font-bold mb-4 text-gold">
              Carré Premium
            </h3>
            <p className="text-sm opacity-90 leading-relaxed mb-4">
              Votre partenaire de confiance pour vos voyages et événements. 
              Nous offrons les meilleures expériences de voyage depuis 2024.
            </p>
            <div className="flex space-x-4">
              <a href="#" className="hover:text-gold transition-colors" aria-label="Facebook">
                📘
              </a>
              <a href="#" className="hover:text-gold transition-colors" aria-label="Twitter">
                🐦
              </a>
              <a href="#" className="hover:text-gold transition-colors" aria-label="Instagram">
                📷
              </a>
              <a href="#" className="hover:text-gold transition-colors" aria-label="LinkedIn">
                💼
              </a>
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="font-montserrat font-semibold text-lg mb-4 text-gold">
              Liens Rapides
            </h4>
            <ul className="space-y-2 text-sm">
              <li>
                <Link to="/flights" className="hover:text-gold transition-colors opacity-90 hover:opacity-100">
                  ✈️ {t('flights')}
                </Link>
              </li>
              <li>
                <Link to="/events" className="hover:text-gold transition-colors opacity-90 hover:opacity-100">
                  🎫 {t('events')}
                </Link>
              </li>
              <li>
                <Link to="/packages" className="hover:text-gold transition-colors opacity-90 hover:opacity-100">
                  🎒 {t('packages')}
                </Link>
              </li>
              <li>
                <Link to="/about" className="hover:text-gold transition-colors opacity-90 hover:opacity-100">
                  ℹ️ {t('aboutUs')}
                </Link>
              </li>
            </ul>
          </div>

          {/* Support */}
          <div>
            <h4 className="font-montserrat font-semibold text-lg mb-4 text-gold">
              Support
            </h4>
            <ul className="space-y-2 text-sm">
              <li>
                <Link to="/faq" className="hover:text-gold transition-colors opacity-90 hover:opacity-100">
                  ❓ {t('faq')}
                </Link>
              </li>
              <li>
                <Link to="/contact" className="hover:text-gold transition-colors opacity-90 hover:opacity-100">
                  📧 {t('contact')}
                </Link>
              </li>
              <li>
                <Link to="/terms" className="hover:text-gold transition-colors opacity-90 hover:opacity-100">
                  📄 {t('terms')}
                </Link>
              </li>
              <li>
                <Link to="/privacy" className="hover:text-gold transition-colors opacity-90 hover:opacity-100">
                  🔒 {t('privacy')}
                </Link>
              </li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="font-montserrat font-semibold text-lg mb-4 text-gold">
              Contact
            </h4>
            <ul className="space-y-3 text-sm">
              <li className="flex items-start opacity-90">
                <span className="mr-2">📧</span>
                <a href="mailto:contact@carrepremium.com" className="hover:text-gold transition-colors">
                  contact@carrepremium.com
                </a>
              </li>
              <li className="flex items-start opacity-90">
                <span className="mr-2">📞</span>
                <a href="tel:+225XXXXXXXXX" className="hover:text-gold transition-colors">
                  +225 XX XX XX XX XX
                </a>
              </li>
              <li className="flex items-start opacity-90">
                <span className="mr-2">💬</span>
                <a href="https://wa.me/225XXXXXXXXX" className="hover:text-gold transition-colors">
                  WhatsApp
                </a>
              </li>
              <li className="flex items-start opacity-90">
                <span className="mr-2">📍</span>
                <span>Abidjan, Côte d'Ivoire</span>
              </li>
            </ul>
          </div>
        </div>

        {/* Payment Methods */}
        <div className="mt-12 pt-8 border-t border-white/20">
          <div className="flex flex-wrap items-center justify-center gap-6 mb-6">
            <span className="text-sm opacity-75">Moyens de paiement acceptés:</span>
            <div className="flex gap-4 text-2xl">
              <span title="Visa">💳</span>
              <span title="Mastercard">💳</span>
              <span title="Mobile Money">📱</span>
              <span title="PayPal">💰</span>
            </div>
          </div>
        </div>

        {/* Bottom Bar */}
        <div className="mt-8 pt-8 border-t border-white/20">
          <div className="flex flex-col md:flex-row justify-between items-center gap-4">
            <p className="text-sm opacity-75 text-center md:text-left">
              &copy; {currentYear} Carré Premium. Tous droits réservés.
            </p>
            <div className="flex items-center gap-4 text-sm opacity-75">
              <span>Développé avec ❤️ en Côte d'Ivoire</span>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
