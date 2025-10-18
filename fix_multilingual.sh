#!/bin/bash

# Script pour corriger le système multilingue de Carré Premium
# Ce script met à jour automatiquement les composants pour utiliser les nouvelles traductions

echo "🌍 Correction du système multilingue..."
echo ""

# Aller dans le répertoire frontend
cd carre-premium-frontend

echo "✅ Étape 1: Nettoyage du LanguageContext..."

# Créer un nouveau LanguageContext propre
cat > src/contexts/LanguageContext.jsx << 'EOF'
import React, { createContext, useState, useContext, useEffect } from 'react';
import { translations } from '../translations';

const LanguageContext = createContext();

export const useLanguage = () => {
  const context = useContext(LanguageContext);
  if (!context) {
    throw new Error('useLanguage must be used within a LanguageProvider');
  }
  return context;
};

export const LanguageProvider = ({ children }) => {
  const [language, setLanguage] = useState(() => {
    return localStorage.getItem('language') || 'fr';
  });

  useEffect(() => {
    localStorage.setItem('language', language);
    document.documentElement.lang = language;
  }, [language]);

  // Fonction pour accéder aux traductions imbriquées (ex: t('nav.home'))
  const t = (key) => {
    const keys = key.split('.');
    let value = translations[language];
    
    for (const k of keys) {
      value = value?.[k];
    }
    
    return value || key;
  };

  const value = {
    language,
    setLanguage,
    t,
  };

  return (
    <LanguageContext.Provider value={value}>
      {children}
    </LanguageContext.Provider>
  );
};
EOF

echo "✅ LanguageContext nettoyé et corrigé"
echo ""

echo "✅ Étape 2: Création d'un composant de sélecteur de langue..."

# Créer un composant LanguageSelector
mkdir -p src/components/common
cat > src/components/common/LanguageSelector.jsx << 'EOF'
import React from 'react';
import { useLanguage } from '../../contexts/LanguageContext';

const LanguageSelector = () => {
  const { language, setLanguage } = useLanguage();

  return (
    <div className="flex items-center space-x-2">
      <button
        onClick={() => setLanguage('fr')}
        className={`px-3 py-1 rounded-md text-sm font-medium transition-colors ${
          language === 'fr'
            ? 'bg-purple-600 text-white'
            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
        }`}
      >
        FR
      </button>
      <button
        onClick={() => setLanguage('en')}
        className={`px-3 py-1 rounded-md text-sm font-medium transition-colors ${
          language === 'en'
            ? 'bg-purple-600 text-white'
            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
        }`}
      >
        EN
      </button>
    </div>
  );
};

export default LanguageSelector;
EOF

echo "✅ Composant LanguageSelector créé"
echo ""

echo "✅ Étape 3: Mise à jour du Header avec le sélecteur de langue..."

# Sauvegarder l'ancien Header
cp src/components/layout/Header.jsx src/components/layout/Header.jsx.backup

# Créer un nouveau Header avec le sélecteur
cat > src/components/layout/Header.jsx << 'EOF'
import React from 'react';
import { Link } from 'react-router-dom';
import { useLanguage } from '../../contexts/LanguageContext';
import { useTheme } from '../../contexts/ThemeContext';
import { useCurrency } from '../../contexts/CurrencyContext';
import { useCart } from '../../contexts/CartContext';
import LanguageSelector from '../common/LanguageSelector';

const Header = () => {
  const { t } = useLanguage();
  const { theme, toggleTheme } = useTheme();
  const { currency, setCurrency } = useCurrency();
  const { cart } = useCart();

  const cartItemsCount = cart.reduce((total, item) => total + item.quantity, 0);

  return (
    <header className="bg-white dark:bg-gray-900 shadow-md sticky top-0 z-50">
      <div className="container mx-auto px-4">
        <div className="flex items-center justify-between h-20">
          {/* Logo */}
          <Link to="/" className="flex items-center space-x-2">
            <div className="w-12 h-12 bg-gradient-to-br from-purple-600 to-purple-800 rounded-lg flex items-center justify-center">
              <span className="text-2xl font-bold text-white">CP</span>
            </div>
            <div>
              <h1 className="text-xl font-bold text-gray-900 dark:text-white">
                Carré Premium
              </h1>
              <p className="text-xs text-gray-500 dark:text-gray-400">
                {t('home.hero.subtitle')}
              </p>
            </div>
          </Link>

          {/* Navigation */}
          <nav className="hidden md:flex items-center space-x-8">
            <Link
              to="/"
              className="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium transition-colors"
            >
              {t('nav.home')}
            </Link>
            <Link
              to="/flights"
              className="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium transition-colors"
            >
              {t('nav.flights')}
            </Link>
            <Link
              to="/events"
              className="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium transition-colors"
            >
              {t('nav.events')}
            </Link>
            <Link
              to="/packages"
              className="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium transition-colors"
            >
              {t('nav.packages')}
            </Link>
            <Link
              to="/about"
              className="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium transition-colors"
            >
              {t('nav.about')}
            </Link>
            <Link
              to="/contact"
              className="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium transition-colors"
            >
              {t('nav.contact')}
            </Link>
          </nav>

          {/* Actions */}
          <div className="flex items-center space-x-4">
            {/* Sélecteur de langue */}
            <LanguageSelector />

            {/* Sélecteur de devise */}
            <select
              value={currency}
              onChange={(e) => setCurrency(e.target.value)}
              className="px-3 py-1 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
            >
              <option value="XOF">XOF</option>
              <option value="EUR">EUR</option>
              <option value="USD">USD</option>
              <option value="GBP">GBP</option>
            </select>

            {/* Thème */}
            <button
              onClick={toggleTheme}
              className="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
              aria-label="Toggle theme"
            >
              {theme === 'light' ? (
                <svg className="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
              ) : (
                <svg className="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
              )}
            </button>

            {/* Panier */}
            <Link
              to="/cart"
              className="relative p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
            >
              <svg className="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              {cartItemsCount > 0 && (
                <span className="absolute -top-1 -right-1 bg-purple-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                  {cartItemsCount}
                </span>
              )}
            </Link>

            {/* Connexion */}
            <Link
              to="/login"
              className="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium"
            >
              {t('nav.login')}
            </Link>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;
EOF

echo "✅ Header mis à jour avec traductions et sélecteur de langue"
echo ""

echo "🎉 Correction terminée !"
echo ""
echo "📝 Prochaines étapes:"
echo "1. Le serveur React va se recharger automatiquement"
echo "2. Ouvrez http://localhost:3000 dans votre navigateur"
echo "3. Vous devriez voir les boutons FR/EN en haut à droite"
echo "4. Cliquez sur EN pour passer en anglais"
echo "5. Tous les textes du header devraient changer"
echo ""
echo "⚠️  Note: Seul le Header est traduit pour l'instant."
echo "   Les autres pages doivent être mises à jour manuellement."
echo "   Voir CORRECTION_MULTILINGUE_GUIDE.md pour plus d'infos."
echo ""
echo "✨ Le système multilingue est maintenant fonctionnel !"
