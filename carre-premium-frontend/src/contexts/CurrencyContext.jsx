import React, { createContext, useState, useContext, useEffect } from 'react';

const CurrencyContext = createContext();

export const useCurrency = () => {
  const context = useContext(CurrencyContext);
  if (!context) {
    throw new Error('useCurrency must be used within a CurrencyProvider');
  }
  return context;
};

export const CurrencyProvider = ({ children }) => {
  const [currency, setCurrency] = useState(() => {
    return localStorage.getItem('currency') || 'XOF';
  });

  const [exchangeRates, setExchangeRates] = useState({
    XOF: 1,
    EUR: 655.957,
    USD: 600.000,
    GBP: 760.000,
  });

  useEffect(() => {
    localStorage.setItem('currency', currency);
  }, [currency]);

  const currencies = [
    { code: 'XOF', symbol: 'CFA', name: 'Franc CFA' },
    { code: 'EUR', symbol: '€', name: 'Euro' },
    { code: 'USD', symbol: '$', name: 'US Dollar' },
    { code: 'GBP', symbol: '£', name: 'British Pound' },
  ];

  const getCurrentCurrency = () => {
    return currencies.find(c => c.code === currency) || currencies[0];
  };

  const formatPrice = (amount, showSymbol = true) => {
    const currentCurrency = getCurrentCurrency();
    const convertedAmount = amount / exchangeRates[currency];
    
    const formatted = new Intl.NumberFormat('fr-FR', {
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    }).format(convertedAmount);

    return showSymbol ? `${formatted} ${currentCurrency.symbol}` : formatted;
  };

  const convertPrice = (amount, fromCurrency = 'XOF') => {
    const amountInXOF = amount * exchangeRates[fromCurrency];
    return amountInXOF / exchangeRates[currency];
  };

  const value = {
    currency,
    setCurrency,
    currencies,
    exchangeRates,
    getCurrentCurrency,
    formatPrice,
    convertPrice,
  };

  return (
    <CurrencyContext.Provider value={value}>
      {children}
    </CurrencyContext.Provider>
  );
};
