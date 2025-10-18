import React from 'react';
import { Link } from 'react-router-dom';
import { useCart } from '../contexts/CartContext';
import { useCurrency } from '../contexts/CurrencyContext';
import { useLanguage } from '../contexts/LanguageContext';

const Cart = () => {
  const { cart, removeFromCart, updateQuantity, getCartTotal, getTaxes, getFees, getGrandTotal, clearCart } = useCart();
  const { formatPrice } = useCurrency();
  const { t } = useLanguage();

  if (cart.length === 0) {
    return (
      <div className="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
        <div className="text-center">
          <div className="text-8xl mb-4">🛒</div>
          <h2 className="text-3xl font-montserrat font-bold text-gray-800 dark:text-white mb-4">
            Votre panier est vide
          </h2>
          <p className="text-gray-600 dark:text-gray-400 mb-8">
            Découvrez nos vols, événements et packages touristiques
          </p>
          <div className="flex gap-4 justify-center">
            <Link to="/flights" className="btn btn-primary">
              ✈️ Voir les vols
            </Link>
            <Link to="/events" className="btn btn-secondary">
              🎫 Voir les événements
            </Link>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      {/* Header */}
      <section className="bg-gradient-to-r from-primary to-purple-700 text-white py-12">
        <div className="container-custom">
          <h1 className="text-4xl font-montserrat font-bold mb-2">
            Votre <span className="text-gold">Panier</span>
          </h1>
          <p className="text-lg opacity-90">{cart.length} article(s)</p>
        </div>
      </section>

      {/* Cart Content */}
      <section className="section">
        <div className="container-custom">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Cart Items */}
            <div className="lg:col-span-2 space-y-4">
              {cart.map((item, index) => (
                <div key={index} className="card">
                  <div className="flex gap-4 p-6">
                    {/* Item Icon */}
                    <div className="flex-shrink-0">
                      <div className="w-20 h-20 bg-primary/10 rounded-lg flex items-center justify-center text-3xl">
                        {item.type === 'flight' && '✈️'}
                        {item.type === 'event' && '🎫'}
                        {item.type === 'package' && '🎒'}
                      </div>
                    </div>

                    {/* Item Details */}
                    <div className="flex-1">
                      <div className="flex justify-between items-start mb-2">
                        <div>
                          <h3 className="font-bold text-lg text-gray-800 dark:text-white">
                            {item.name}
                          </h3>
                          {item.airline && (
                            <p className="text-sm text-gray-600 dark:text-gray-400">
                              {item.airline}
                            </p>
                          )}
                        </div>
                        <button
                          onClick={() => removeFromCart(item.id, item.type)}
                          className="text-red-500 hover:text-red-700 transition-colors"
                          title="Supprimer"
                        >
                          ✕
                        </button>
                      </div>

                      <div className="space-y-1 text-sm text-gray-600 dark:text-gray-400 mb-4">
                        {item.date && <p>📅 {item.date}</p>}
                        {item.departure && <p>🕐 Départ: {item.departure}</p>}
                        {item.class && <p>💺 Classe: {item.class}</p>}
                        {item.passengers && <p>👥 {item.passengers} passager(s)</p>}
                      </div>

                      <div className="flex justify-between items-center">
                        <div className="flex items-center gap-3">
                          <button
                            onClick={() => updateQuantity(item.id, item.type, item.quantity - 1)}
                            className="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 flex items-center justify-center transition-colors"
                            disabled={item.quantity <= 1}
                          >
                            −
                          </button>
                          <span className="font-semibold text-gray-800 dark:text-white w-8 text-center">
                            {item.quantity}
                          </span>
                          <button
                            onClick={() => updateQuantity(item.id, item.type, item.quantity + 1)}
                            className="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 flex items-center justify-center transition-colors"
                          >
                            +
                          </button>
                        </div>
                        <div className="text-right">
                          <p className="text-2xl font-bold text-primary">
                            {formatPrice(item.price * item.quantity)}
                          </p>
                          <p className="text-xs text-gray-500 dark:text-gray-400">
                            {formatPrice(item.price)} × {item.quantity}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              ))}

              {/* Clear Cart Button */}
              <button
                onClick={clearCart}
                className="btn btn-outline text-red-500 border-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 w-full"
              >
                🗑️ Vider le panier
              </button>
            </div>

            {/* Order Summary */}
            <div className="lg:col-span-1">
              <div className="card sticky top-24">
                <div className="p-6">
                  <h3 className="text-xl font-montserrat font-bold text-gray-800 dark:text-white mb-6">
                    Récapitulatif
                  </h3>

                  <div className="space-y-4 mb-6">
                    <div className="flex justify-between text-gray-600 dark:text-gray-400">
                      <span>Sous-total</span>
                      <span className="font-semibold">{formatPrice(getCartTotal())}</span>
                    </div>
                    <div className="flex justify-between text-gray-600 dark:text-gray-400">
                      <span>Taxes (18%)</span>
                      <span className="font-semibold">{formatPrice(getTaxes())}</span>
                    </div>
                    <div className="flex justify-between text-gray-600 dark:text-gray-400">
                      <span>Frais de service</span>
                      <span className="font-semibold">{formatPrice(getFees())}</span>
                    </div>
                    <div className="border-t border-gray-200 dark:border-gray-700 pt-4">
                      <div className="flex justify-between items-center">
                        <span className="text-lg font-bold text-gray-800 dark:text-white">
                          Total
                        </span>
                        <span className="text-2xl font-bold text-primary">
                          {formatPrice(getGrandTotal())}
                        </span>
                      </div>
                    </div>
                  </div>

                  <Link to="/checkout" className="btn btn-primary w-full mb-3">
                    Passer la commande
                  </Link>
                  <Link to="/" className="btn btn-outline w-full">
                    Continuer mes achats
                  </Link>

                  {/* Trust Badges */}
                  <div className="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div className="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                      <div className="flex items-center gap-2">
                        <span>✅</span>
                        <span>Paiement 100% sécurisé</span>
                      </div>
                      <div className="flex items-center gap-2">
                        <span>🔒</span>
                        <span>Données cryptées SSL</span>
                      </div>
                      <div className="flex items-center gap-2">
                        <span>📧</span>
                        <span>Confirmation par email</span>
                      </div>
                      <div className="flex items-center gap-2">
                        <span>💳</span>
                        <span>Plusieurs moyens de paiement</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default Cart;
