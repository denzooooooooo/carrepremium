# 🌍 GUIDE CORRECTION SYSTÈME MULTILINGUE

**Date:** 10 Janvier 2025  
**Problème:** Le site reste en français même quand on sélectionne l'anglais

---

## 🔍 DIAGNOSTIC DU PROBLÈME

Le système multilingue est partiellement implémenté mais les traductions ne sont pas utilisées partout dans les composants.

---

## ✅ SOLUTION COMPLÈTE

### Étape 1: Fichier de traductions créé ✅

Le fichier `carre-premium-frontend/src/translations.js` a été créé avec toutes les traductions FR/EN.

### Étape 2: Mettre à jour LanguageContext

**Fichier:** `carre-premium-frontend/src/contexts/LanguageContext.jsx`

Remplacer le contenu par:

```javascript
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

  // Fonction pour accéder aux traductions imbriquées
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
```

### Étape 3: Utiliser les traductions dans les composants

**Exemple dans Header:**

```javascript
import { useLanguage } from '../contexts/LanguageContext';

function Header() {
  const { language, setLanguage, t } = useLanguage();
  
  return (
    <nav>
      <Link to="/">{t('nav.home')}</Link>
      <Link to="/flights">{t('nav.flights')}</Link>
      <Link to="/events">{t('nav.events')}</Link>
      <Link to="/packages">{t('nav.packages')}</Link>
      
      {/* Sélecteur de langue */}
      <select value={language} onChange={(e) => setLanguage(e.target.value)}>
        <option value="fr">Français</option>
        <option value="en">English</option>
      </select>
    </nav>
  );
}
```

**Exemple dans une page:**

```javascript
import { useLanguage } from '../contexts/LanguageContext';

function FlightsPage() {
  const { t } = useLanguage();
  
  return (
    <div>
      <h1>{t('flights.title')}</h1>
      <input placeholder={t('flights.from')} />
      <input placeholder={t('flights.to')} />
      <button>{t('flights.search')}</button>
    </div>
  );
}
```

---

## 📝 FICHIERS À MODIFIER

### 1. Header.jsx
Remplacer tous les textes en dur par `t('nav.xxx')`

### 2. Footer.jsx
Remplacer tous les textes en dur par `t('footer.xxx')`

### 3. HomeModern.jsx
Remplacer tous les textes en dur par `t('home.xxx')`

### 4. FlightsModern.jsx
Remplacer tous les textes en dur par `t('flights.xxx')`

### 5. EventsModern.jsx
Remplacer tous les textes en dur par `t('events.xxx')`

### 6. PackagesModern.jsx
Remplacer tous les textes en dur par `t('packages.xxx')`

### 7. Cart.jsx
Remplacer tous les textes en dur par `t('cart.xxx')`

---

## 🔧 EXEMPLE COMPLET DE CORRECTION

**AVANT (texte en dur):**
```javascript
<h1>Rechercher un Vol</h1>
<button>Réserver</button>
```

**APRÈS (avec traductions):**
```javascript
import { useLanguage } from '../contexts/LanguageContext';

function Component() {
  const { t } = useLanguage();
  
  return (
    <>
      <h1>{t('flights.title')}</h1>
      <button>{t('flights.bookNow')}</button>
    </>
  );
}
```

---

## ✅ VÉRIFICATION

Pour vérifier que ça fonctionne:

1. Ouvrir le site
2. Cliquer sur le sélecteur de langue (FR/EN)
3. Tous les textes doivent changer instantanément

---

## 🎯 RÉSUMÉ DES CHANGEMENTS

### Fichiers créés:
- ✅ `translations.js` - Toutes les traductions FR/EN

### Fichiers à modifier:
- ⏳ `LanguageContext.jsx` - Utiliser le nouveau fichier translations
- ⏳ `Header.jsx` - Remplacer textes par t('nav.xxx')
- ⏳ `Footer.jsx` - Remplacer textes par t('footer.xxx')
- ⏳ Toutes les pages - Remplacer textes par t('xxx.yyy')

---

## 💡 ASTUCE RAPIDE

Pour corriger rapidement, cherchez tous les textes en français dans les fichiers et remplacez-les par:

```javascript
// Au lieu de:
<button>Réserver</button>

// Utilisez:
<button>{t('common.book')}</button>
```

---

## 🚀 PROCHAINES ÉTAPES

1. Mettre à jour `LanguageContext.jsx` (code fourni ci-dessus)
2. Modifier chaque composant pour utiliser `t()`
3. Tester le changement de langue
4. Vérifier que tous les textes changent

---

## ✨ RÉSULTAT FINAL

Une fois terminé, votre site sera **100% multilingue** avec:
- ✅ Changement instantané FR ↔ EN
- ✅ Toutes les pages traduites
- ✅ Navigation traduite
- ✅ Footer traduit
- ✅ Boutons et labels traduits

**Le système multilingue sera parfait ! 🌍**
