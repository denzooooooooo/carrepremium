import React from 'react';
import { Routes, Route } from 'react-router-dom';

// Contexts
import { AuthProvider } from './contexts/AuthContext';
import { LanguageProvider } from './contexts/LanguageContext';
import { ThemeProvider } from './contexts/ThemeContext';
import { CurrencyProvider } from './contexts/CurrencyContext';
import { CartProvider } from './contexts/CartContext';

// Layout
import Header from './components/layout/HeaderModern';
import Footer from './components/layout/FooterModern';

// Components
import ChatbotButton from './components/ChatbotButton';

// Pages
import Home from './pages/HomeModern';
import FlightsModern from './pages/FlightsModern';
import FlightDetailsComplete from './pages/FlightDetailsComplete';
import EventsModern from './pages/EventsModern';
import EventDetailsModern from './pages/EventDetailsModern';
import PackagesModern from './pages/PackagesModern';
import PackageDetailsModern from './pages/PackageDetailsModern';
import Cart from './pages/Cart';
import Checkout from './pages/Checkout';
import Confirmation from './pages/Confirmation';
import Contact from './pages/Contact';
import Login from './pages/Login';
import Register from './pages/Register';
import Dashboard from './pages/account/Dashboard';
import MyBookings from './pages/account/MyBookings';
import MyFavorites from './pages/account/MyFavorites';
import Profile from './pages/account/Profile';
import About from './pages/About';
import FAQ from './pages/FAQ';
import Terms from './pages/Terms';
import Privacy from './pages/Privacy';

function App() {
  return (
    <AuthProvider>
      <LanguageProvider>
        <ThemeProvider>
          <CurrencyProvider>
            <CartProvider>
              <div className="min-h-screen flex flex-col bg-white dark:bg-gray-900">
                <Header />
                <main className="flex-grow">
                  <Routes>
                    {/* Public Routes */}
                    <Route path="/" element={<Home />} />

                    {/* Flights */}
                    <Route path="/flights" element={<FlightsModern />} />
                    <Route path="/flights/:id" element={<FlightDetailsComplete />} />
                    <Route path="/flight/:id" element={<FlightDetailsComplete />} />

                    {/* Events */}
                    <Route path="/events" element={<EventsModern />} />
                    <Route path="/events/:id" element={<EventDetailsModern />} />

                    {/* Packages */}
                    <Route path="/packages" element={<PackagesModern />} />
                    <Route path="/packages/:id" element={<PackageDetailsModern />} />

                    {/* Cart & Checkout */}
                    <Route path="/cart" element={<Cart />} />
                    <Route path="/checkout" element={<Checkout />} />
                    <Route path="/confirmation/:bookingId" element={<Confirmation />} />

                    {/* Auth */}
                    <Route path="/login" element={<Login />} />
                    <Route path="/register" element={<Register />} />

                    {/* Account */}
                    <Route path="/account" element={<Dashboard />} />
                    <Route path="/account/bookings" element={<MyBookings />} />
                    <Route path="/account/favorites" element={<MyFavorites />} />
                    <Route path="/account/profile" element={<Profile />} />

                    {/* Info Pages */}
                    <Route path="/about" element={<About />} />
                    <Route path="/contact" element={<Contact />} />
                    <Route path="/faq" element={<FAQ />} />
                    <Route path="/terms" element={<Terms />} />
                    <Route path="/privacy" element={<Privacy />} />
                  </Routes>
                </main>
                <Footer />
              </div>

              {/* Global Chatbot Button */}
              <ChatbotButton />
            </CartProvider>
          </CurrencyProvider>
        </ThemeProvider>
      </LanguageProvider>
    </AuthProvider>
  );
}

export default App;
