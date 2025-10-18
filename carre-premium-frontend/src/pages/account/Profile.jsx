import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import { useLanguage } from '../../contexts/LanguageContext';
import { useCurrency } from '../../contexts/CurrencyContext';
import HeaderModern from '../../components/layout/HeaderModern';
import FooterModern from '../../components/layout/FooterModern';
import axios from 'axios';

const Profile = () => {
  const { t } = useLanguage();
  const { user, updateProfile, changePassword, uploadAvatar, logout } = useAuth();
  const { formatPrice } = useCurrency();
  
  const [activeTab, setActiveTab] = useState('info');
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState({ type: '', text: '' });
  const [statistics, setStatistics] = useState(null);
  
  const [profileData, setProfileData] = useState({
    first_name: '', last_name: '', phone: '', date_of_birth: '',
    gender: '', nationality: '', passport_number: '', address: '',
    city: '', country: '', postal_code: '', preferred_language: '', preferred_currency: ''
  });

  const [passwordData, setPasswordData] = useState({
    current_password: '', new_password: '', new_password_confirmation: ''
  });

  const [avatarFile, setAvatarFile] = useState(null);
  const [avatarPreview, setAvatarPreview] = useState(null);

  useEffect(() => {
    if (user) {
      setProfileData({
        first_name: user.first_name || '', last_name: user.last_name || '',
        phone: user.phone || '', date_of_birth: user.date_of_birth || '',
        gender: user.gender || '', nationality: user.nationality || '',
        passport_number: user.passport_number || '', address: user.address || '',
        city: user.city || '', country: user.country || '', postal_code: user.postal_code || '',
        preferred_language: user.preferred_language || 'fr', preferred_currency: user.preferred_currency || 'XOF'
      });
    }
  }, [user]);

  useEffect(() => {
    const loadStats = async () => {
      try {
        const response = await axios.get('http://localhost:8000/api/v1/user/statistics');
        if (response.data.success) setStatistics(response.data.data);
      } catch (error) {
        console.error('Erreur chargement statistiques:', error);
      }
    };
    if (activeTab === 'stats') loadStats();
  }, [activeTab]);

  const handleProfileChange = (e) => {
    setProfileData({ ...profileData, [e.target.name]: e.target.value });
  };

  const handlePasswordChange = (e) => {
    setPasswordData({ ...passwordData, [e.target.name]: e.target.value });
  };

  const handleAvatarChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setAvatarFile(file);
      setAvatarPreview(URL.createObjectURL(file));
    }
  };

  const handleProfileSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setMessage({ type: '', text: '' });
    const result = await updateProfile(profileData);
    setMessage(result.success ? 
      { type: 'success', text: 'Profil mis à jour avec succès !' } :
      { type: 'error', text: result.message || 'Erreur lors de la mise à jour' }
    );
    setLoading(false);
    setTimeout(() => setMessage({ type: '', text: '' }), 5000);
  };

  const handlePasswordSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setMessage({ type: '', text: '' });
    const result = await changePassword(passwordData.current_password, passwordData.new_password, passwordData.new_password_confirmation);
    if (result.success) {
      setMessage({ type: 'success', text: 'Mot de passe modifié avec succès !' });
      setPasswordData({ current_password: '', new_password: '', new_password_confirmation: '' });
    } else {
      setMessage({ type: 'error', text: result.message || 'Erreur lors du changement' });
    }
    setLoading(false);
    setTimeout(() => setMessage({ type: '', text: '' }), 5000);
  };

  const handleAvatarSubmit = async (e) => {
    e.preventDefault();
    if (!avatarFile) return;
    setLoading(true);
    setMessage({ type: '', text: '' });
    const result = await uploadAvatar(avatarFile);
    if (result.success) {
      setMessage({ type: 'success', text: 'Avatar mis à jour avec succès !' });
      setAvatarFile(null);
      setAvatarPreview(null);
    } else {
      setMessage({ type: 'error', text: result.message || 'Erreur lors de l\'upload' });
    }
    setLoading(false);
    setTimeout(() => setMessage({ type: '', text: '' }), 5000);
  };

  if (!user) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      <HeaderModern />
      
      <div className="py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          {/* Header */}
          <div className="bg-gradient-to-r from-purple-600 to-amber-600 rounded-3xl p-8 mb-8 text-white">
            <div className="flex flex-col md:flex-row items-center justify-between gap-6">
              <div className="flex items-center space-x-6">
                <div className="relative">
                  <img
                    src={avatarPreview || user.avatar || `https://ui-avatars.com/api/?name=${user.first_name}+${user.last_name}&size=200&background=9333EA&color=fff`}
                    alt={user.first_name}
                    className="w-24 h-24 rounded-full border-4 border-white shadow-xl object-cover"
                  />
                  <label className="absolute bottom-0 right-0 w-8 h-8 bg-white rounded-full flex items-center justify-center cursor-pointer hover:bg-gray-100 transition-colors shadow-lg">
                    <i className="fas fa-camera text-purple-600 text-sm"></i>
                    <input type="file" accept="image/*" onChange={handleAvatarChange} className="hidden" />
                  </label>
                </div>
                <div>
                  <h1 className="text-3xl font-black mb-1">{user.first_name} {user.last_name}</h1>
                  <p className="text-white/80 mb-2">{user.email}</p>
                  <div className="flex flex-wrap gap-2">
                    <div className="flex items-center space-x-2 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full">
                      <i className="fas fa-star text-yellow-400"></i>
                      <span className="font-semibold">{user.loyalty_points || 0} points</span>
                    </div>
                    <div className="flex items-center space-x-2 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full">
                      <i className="fas fa-shield-check text-green-400"></i>
                      <span className="font-semibold">{user.email_verified_at ? 'Vérifié' : 'Non vérifié'}</span>
                    </div>
                  </div>
                </div>
              </div>
              {avatarFile && (
                <button onClick={handleAvatarSubmit} disabled={loading} className="px-6 py-3 bg-white text-purple-600 font-bold rounded-xl hover:bg-gray-100 transition-colors disabled:opacity-50">
                  <i className="fas fa-upload mr-2"></i>{loading ? 'Upload...' : 'Enregistrer l\'avatar'}
                </button>
              )}
            </div>
          </div>

          {/* Message */}
          {message.text && (
            <div className={`mb-6 p-4 rounded-xl ${message.type === 'success' ? 'bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500' : 'bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500'}`}>
              <div className="flex items-center">
                <i className={`fas ${message.type === 'success' ? 'fa-check-circle text-green-500' : 'fa-exclamation-circle text-red-500'} mr-3`}></i>
                <p className={`text-sm ${message.type === 'success' ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400'}`}>{message.text}</p>
              </div>
            </div>
          )}

          <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {/* Sidebar */}
            <div className="lg:col-span-1">
              <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sticky top-24">
                <nav className="space-y-2">
                  {[
                    { id: 'info', icon: 'fa-user', label: 'Informations Personnelles' },
                    { id: 'security', icon: 'fa-lock', label: 'Sécurité' },
                    { id: 'stats', icon: 'fa-chart-line', label: 'Statistiques' }
                  ].map(tab => (
                    <button key={tab.id} onClick={() => setActiveTab(tab.id)} className={`w-full flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold transition-colors ${activeTab === tab.id ? 'bg-purple-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'}`}>
                      <i className={`fas ${tab.icon}`}></i><span>{tab.label}</span>
                    </button>
                  ))}
                  <Link to="/account/bookings" className="w-full flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i className="fas fa-ticket-alt"></i><span>Mes Réservations</span>
                  </Link>
                  <button onClick={logout} className="w-full flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <i className="fas fa-sign-out-alt"></i><span>Déconnexion</span>
                  </button>
                </nav>
              </div>
            </div>

            {/* Main Content */}
            <div className="lg:col-span-3">
              {/* Personal Information Tab */}
              {activeTab === 'info' && (
                <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                  <h2 className="text-2xl font-black text-gray-900 dark:text-white mb-6">Informations Personnelles</h2>
                  <form onSubmit={handleProfileSubmit} className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div>
                        <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Prénom</label>
                        <input type="text" name="first_name" value={profileData.first_name} onChange={handleProfileChange} className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0" />
                      </div>
                      <div>
                        <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nom</label>
                        <input type="text" name="last_name" value={profileData.last_name} onChange={handleProfileChange} className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0" />
                      </div>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div>
                        <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Téléphone</label>
                        <input type="tel" name="phone" value={profileData.phone} onChange={handleProfileChange} className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0" />
                      </div>
                      <div>
                        <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Date de naissance</label>
                        <input type="date" name="date_of_birth" value={profileData.date_of_birth} onChange={handleProfileChange} className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0" />
                      </div>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div>
                        <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Genre</label>
                        <select name="gender" value={profileData.gender} onChange={handleProfileChange} className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0">
                          <option value="">Sélectionner</option>
                          <option value="male">Homme</option>
                          <option value="female">Femme</option>
                          <option value="other">Autre</option>
                        </select>
                      </div>
                      <div>
                        <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nationalité</label>
                        <input type="text" name="nationality" value={profileData.nationality} onChange={handleProfileChange} className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0" placeholder="Ivoirienne" />
                      </div>
                    </div>
                    <div>
                      <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Numéro de passeport</label>
                      <input type="text" name="passport_number" value={profileData.passport_number} onChange={handleProfileChange} className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0" placeholder="CI123456789" />
                    </div>
                    <div>
                      <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Adresse</label>
                      <textarea name="address" value={profileData.address} onChange={handleProfileChange} rows="3" className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0" placeholder="Votre adresse complète"></textarea>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                      <div>
                        <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Ville</label>
                        <input type="text" name="city" value={profileData.city} onChange={handleProfileChange} className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0" placeholder="Abidjan" />
                      </div>
                      <div>
                        <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Pays</label>
                        <input type="text" name="country" value={profileData.country} onChange={handleProfileChange} className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0" placeholder="Côte d'Ivoire" />
                      </div>
                      <div>
                        <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Code postal</label>
                        <input type="text" name="postal_code" value={profileData.postal_code} onChange={handleProfileChange} className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0" placeholder="00225" />
                      </div>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div>
                        <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Langue préférée</label>
                        <select name="preferred_language" value={profileData.preferred_language} onChange={handleProfileChange} className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0">
                          <option value="fr">🇫🇷 Français</option>
                          <option value="en">🇬🇧 English</option>
                        </select>
                      </div>
                      <div>
                        <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Devise préférée</label>
                        <select name="preferred_currency" value={profileData.preferred_currency} onChange={handleProfileChange} className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0">
                          <option value="XOF">XOF (Franc CFA)</option>
                          <option value="EUR">EUR (Euro)</option>
                          <option value="USD">USD (Dollar)</option>
                        </select>
                      </div>
                    </div>
                    <button type="submit" disabled={loading} className="w-full py-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-300 disabled:opacity-50 shadow-lg hover:shadow-2xl">
                      {loading ? <><i className="fas fa-spinner fa-spin mr-2"></i>Enregistrement...</> : <><i className="fas fa-save mr-2"></i>Enregistrer les modifications</>}
                    </button>
                  </form>
                </div>
              )}

              {/* Security Tab */}
              {activeTab === 'security' && (
                <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                  <h2 className="text-2xl font-black text-gray-900 dark:text-white mb-6">Paramètres de Sécurité</h2>
                  <form onSubmit={handlePasswordSubmit} className="space-y-6">
                    <div>
                      <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Mot de passe actuel</label>
                      <input type="password" name="current_password" value={passwordData.current_password} onChange={handlePasswordChange} required className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0" placeholder="••••••••" />
                    </div>
                    <div>
                      <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nouveau mot de passe</label>
                      <input type="password" name="new_password" value={passwordData.new_password} onChange={handlePasswordChange} required className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0" placeholder="••••••••" />
                    </div>
                    <div>
                      <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Confirmer le nouveau mot de passe</label>
                      <input type="password" name="new_password_confirmation" value={passwordData.new_password_confirmation} onChange={handlePasswordChange} required className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0" placeholder="••••••••" />
                    </div>
                    <button type="submit" disabled={loading} className="w-full py-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-300 disabled:opacity-50 shadow-lg hover:shadow-2xl">
                      {loading ? <><i className="fas fa-spinner fa-spin mr-2"></i>Mise à jour...</> : <><i className="fas fa-key mr-2"></i>Changer le mot de passe</>}
                    </button>
                  </form>
                </div>
              )}

              {/* Statistics Tab */}
              {activeTab === 'stats' && (
                <div className="space-y-6">
                  <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                    {[
                      { icon: 'fa-ticket-alt', color: 'purple', value: statistics?.total_bookings || 0, label: 'Réservations' },
                      { icon: 'fa-check-circle', color: 'green', value: statistics?.confirmed_bookings || 0, label: 'Confirmées' },
                      { icon: 'fa-wallet', color: 'amber', value: formatPrice(statistics?.total_spent || 0), label: 'Dépensé' },
                      { icon: 'fa-star', color: 'yellow', value: statistics?.loyalty_points || user.loyalty_points || 0, label: 'Points' }
                    ].map((stat, idx) => (
                      <div key={idx} className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <i className={`fas ${stat.icon} text-3xl text-${stat.color}-600 mb-2`}></i>
                        <p className="text-3xl font-black text-gray-900 dark:text-white">{stat.value}</p>
                        <p className="text-sm text-gray-600 dark:text-gray-400">{stat.label}</p>
                      </div>
                    ))}
                  </div>

                  {/* Message si pas de statistiques */}
                  {!statistics?.recent_bookings || statistics.recent_bookings.length === 0 ? (
                    <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                      <i className="fas fa-chart-line text-6xl text-gray-300 dark:text-gray-700 mb-4"></i>
                      <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2">Aucune statistique disponible</h3>
                      <p className="text-gray-600 dark:text-gray-400 mb-6">Effectuez votre première réservation pour voir vos statistiques</p>
                      <Link to="/flights" className="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition-colors">
                        <i className="fas fa-plane mr-2"></i>
                        Réserver un vol
                      </Link>
                    </div>
                  ) : (
                    <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                      <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-6">Réservations Récentes</h3>
                      <div className="space-y-4">
                        {statistics.recent_bookings.map((booking) => (
                          <Link key={booking.id} to={`/account/bookings/${booking.id}`} className="block p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-purple-600 transition-colors">
                            <div className="flex items-center justify-between">
                              <div>
                                <p className="font-bold text-gray-900 dark:text-white">{booking.booking_number}</p>
                                <p className="text-sm text-gray-600 dark:text-gray-400">{new Date(booking.created_at).toLocaleDateString('fr-FR')}</p>
                              </div>
                              <div className="text-right">
                                <p className="font-bold text-purple-600">{formatPrice(booking.final_amount)}</p>
                                <span className={`text-xs px-2 py-1 rounded-full ${booking.status === 'confirmed' ? 'bg-green-100 text-green-700' : booking.status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700'}`}>
                                  {booking.status}
                                </span>
                              </div>
                            </div>
                          </Link>
                        ))}
                      </div>
                    </div>
                  )}
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      <FooterModern />
    </div>
  );
};

export default Profile;
