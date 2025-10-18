import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import { useLanguage } from '../contexts/LanguageContext';
import HeaderModern from '../components/layout/HeaderModern';
import FooterModern from '../components/layout/FooterModern';

const Register = () => {
  const { t } = useLanguage();
  const { register } = useAuth();
  const navigate = useNavigate();
  
  const [formData, setFormData] = useState({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
    date_of_birth: '',
    gender: '',
    country: 'Côte d\'Ivoire',
    preferred_language: 'fr',
    preferred_currency: 'XOF',
    accept_terms: false
  });
  
  const [loading, setLoading] = useState(false);
  const [errors, setErrors] = useState({});
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [step, setStep] = useState(1);

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData({
      ...formData,
      [name]: type === 'checkbox' ? checked : value
    });
    if (errors[name]) {
      setErrors({ ...errors, [name]: null });
    }
  };

  const validateStep1 = () => {
    const newErrors = {};
    
    if (!formData.first_name.trim()) {
      newErrors.first_name = t('auth.firstNameRequired');
    }
    if (!formData.last_name.trim()) {
      newErrors.last_name = t('auth.lastNameRequired');
    }
    if (!formData.email.trim()) {
      newErrors.email = t('auth.emailRequired');
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = t('auth.emailInvalid');
    }
    
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const validateStep2 = () => {
    const newErrors = {};
    
    if (!formData.password) {
      newErrors.password = t('auth.passwordRequired');
    } else if (formData.password.length < 8) {
      newErrors.password = t('auth.passwordTooShort');
    }
    
    if (formData.password !== formData.password_confirmation) {
      newErrors.password_confirmation = t('auth.passwordsNotMatch');
    }
    
    if (!formData.accept_terms) {
      newErrors.accept_terms = t('auth.termsRequired');
    }
    
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleNext = () => {
    if (step === 1 && validateStep1()) {
      setStep(2);
    }
  };

  const handleBack = () => {
    setStep(1);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (!validateStep2()) return;
    
    setLoading(true);
    setErrors({});

    const result = await register(formData);
    
    if (result.success) {
      navigate('/profile');
    } else {
      if (result.errors) {
        setErrors(result.errors);
      }
      if (result.message) {
        setErrors(prev => ({ ...prev, general: result.message }));
      }
      if (step === 2 && result.errors && (result.errors.first_name || result.errors.last_name || result.errors.email)) {
        setStep(1);
      }
    }
    
    setLoading(false);
  };

  const getPasswordStrength = () => {
    const password = formData.password;
    if (!password) return { strength: 0, label: '', color: '' };
    
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z\d]/.test(password)) strength++;
    
    if (strength <= 2) return { strength, label: t('auth.weak'), color: 'bg-red-500' };
    if (strength <= 3) return { strength, label: t('auth.medium'), color: 'bg-yellow-500' };
    return { strength, label: t('auth.strong'), color: 'bg-green-500' };
  };

  const passwordStrength = getPasswordStrength();

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col">
      <HeaderModern />
      
      <div className="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div className="max-w-2xl w-full space-y-8">
          {/* Header */}
          <div className="text-center">
            <h2 className="text-4xl font-black text-gray-900 dark:text-white mb-2">
              {t('auth.createAccount')}
            </h2>
            <p className="text-gray-600 dark:text-gray-400">
              {t('auth.registerSubtitle')}
            </p>
          </div>

          {/* Progress Steps */}
          <div className="flex items-center justify-center space-x-4">
            <div className={`flex items-center ${step >= 1 ? 'text-purple-600' : 'text-gray-400'}`}>
              <div className={`w-10 h-10 rounded-full flex items-center justify-center font-bold ${step >= 1 ? 'bg-purple-600 text-white' : 'bg-gray-200 dark:bg-gray-700'}`}>
                1
              </div>
              <span className="ml-2 font-semibold hidden sm:inline">{t('auth.personalInfo')}</span>
            </div>
            <div className="w-16 h-1 bg-gray-200 dark:bg-gray-700"></div>
            <div className={`flex items-center ${step >= 2 ? 'text-purple-600' : 'text-gray-400'}`}>
              <div className={`w-10 h-10 rounded-full flex items-center justify-center font-bold ${step >= 2 ? 'bg-purple-600 text-white' : 'bg-gray-200 dark:bg-gray-700'}`}>
                2
              </div>
              <span className="ml-2 font-semibold hidden sm:inline">{t('auth.security')}</span>
            </div>
          </div>

          {/* Form */}
          <div className="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8">
            <form onSubmit={handleSubmit} className="space-y-6">
              {/* General Error */}
              {errors.general && (
                <div className="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-lg">
                  <div className="flex items-center">
                    <i className="fas fa-exclamation-circle text-red-500 mr-3"></i>
                    <p className="text-sm text-red-700 dark:text-red-400">{errors.general}</p>
                  </div>
                </div>
              )}

              {/* Step 1: Personal Information */}
              {step === 1 && (
                <div className="space-y-6">
                  {/* First Name & Last Name */}
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        {t('auth.firstName')} *
                      </label>
                      <input
                        type="text"
                        name="first_name"
                        required
                        value={formData.first_name}
                        onChange={handleChange}
                        className={`block w-full px-4 py-3 border-2 ${errors.first_name ? 'border-red-500' : 'border-gray-200 dark:border-gray-700'} rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0 transition-colors`}
                        placeholder="John"
                      />
                      {errors.first_name && <p className="mt-1 text-sm text-red-500">{errors.first_name}</p>}
                    </div>

                    <div>
                      <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        {t('auth.lastName')} *
                      </label>
                      <input
                        type="text"
                        name="last_name"
                        required
                        value={formData.last_name}
                        onChange={handleChange}
                        className={`block w-full px-4 py-3 border-2 ${errors.last_name ? 'border-red-500' : 'border-gray-200 dark:border-gray-700'} rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0 transition-colors`}
                        placeholder="Doe"
                      />
                      {errors.last_name && <p className="mt-1 text-sm text-red-500">{errors.last_name}</p>}
                    </div>
                  </div>

                  {/* Email */}
                  <div>
                    <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                      {t('auth.email')} *
                    </label>
                    <div className="relative">
                      <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i className="fas fa-envelope text-gray-400"></i>
                      </div>
                      <input
                        type="email"
                        name="email"
                        required
                        value={formData.email}
                        onChange={handleChange}
                        className={`block w-full pl-12 pr-4 py-3 border-2 ${errors.email ? 'border-red-500' : 'border-gray-200 dark:border-gray-700'} rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0 transition-colors`}
                        placeholder="john.doe@example.com"
                      />
                    </div>
                    {errors.email && <p className="mt-1 text-sm text-red-500">{errors.email}</p>}
                  </div>

                  {/* Phone */}
                  <div>
                    <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                      {t('auth.phone')}
                    </label>
                    <div className="relative">
                      <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i className="fas fa-phone text-gray-400"></i>
                      </div>
                      <input
                        type="tel"
                        name="phone"
                        value={formData.phone}
                        onChange={handleChange}
                        className="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0 transition-colors"
                        placeholder="+225 XX XX XX XX XX"
                      />
                    </div>
                  </div>

                  {/* Date of Birth & Gender */}
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        {t('auth.dateOfBirth')}
                      </label>
                      <input
                        type="date"
                        name="date_of_birth"
                        value={formData.date_of_birth}
                        onChange={handleChange}
                        max={new Date().toISOString().split('T')[0]}
                        className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0 transition-colors"
                      />
                    </div>

                    <div>
                      <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        {t('auth.gender')}
                      </label>
                      <select
                        name="gender"
                        value={formData.gender}
                        onChange={handleChange}
                        className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0 transition-colors"
                      >
                        <option value="">{t('auth.selectGender')}</option>
                        <option value="male">{t('auth.male')}</option>
                        <option value="female">{t('auth.female')}</option>
                        <option value="other">{t('auth.other')}</option>
                      </select>
                    </div>
                  </div>

                  {/* Country */}
                  <div>
                    <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                      {t('auth.country')}
                    </label>
                    <select
                      name="country"
                      value={formData.country}
                      onChange={handleChange}
                      className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0 transition-colors"
                    >
                      <option value="Côte d'Ivoire">🇨🇮 Côte d'Ivoire</option>
                      <option value="France">🇫🇷 France</option>
                      <option value="Sénégal">🇸🇳 Sénégal</option>
                      <option value="Mali">🇲🇱 Mali</option>
                      <option value="Burkina Faso">🇧🇫 Burkina Faso</option>
                      <option value="Bénin">🇧🇯 Bénin</option>
                      <option value="Togo">🇹🇬 Togo</option>
                      <option value="Ghana">🇬🇭 Ghana</option>
                      <option value="Nigeria">🇳🇬 Nigeria</option>
                      <option value="Cameroun">🇨🇲 Cameroun</option>
                    </select>
                  </div>

                  {/* Preferences */}
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        {t('auth.preferredLanguage')}
                      </label>
                      <select
                        name="preferred_language"
                        value={formData.preferred_language}
                        onChange={handleChange}
                        className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0 transition-colors"
                      >
                        <option value="fr">🇫🇷 Français</option>
                        <option value="en">🇬🇧 English</option>
                      </select>
                    </div>

                    <div>
                      <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        {t('auth.preferredCurrency')}
                      </label>
                      <select
                        name="preferred_currency"
                        value={formData.preferred_currency}
                        onChange={handleChange}
                        className="block w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0 transition-colors"
                      >
                        <option value="XOF">XOF (Franc CFA)</option>
                        <option value="EUR">EUR (Euro)</option>
                        <option value="USD">USD (Dollar)</option>
                      </select>
                    </div>
                  </div>

                  {/* Next Button */}
                  <button
                    type="button"
                    onClick={handleNext}
                    className="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl text-white font-bold bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 transition-all duration-300 shadow-lg hover:shadow-2xl"
                  >
                    {t('auth.continue')}
                    <i className="fas fa-arrow-right ml-2"></i>
                  </button>
                </div>
              )}

              {/* Step 2: Security */}
              {step === 2 && (
                <div className="space-y-6">
                  {/* Password */}
                  <div>
                    <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                      {t('auth.password')} *
                    </label>
                    <div className="relative">
                      <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i className="fas fa-lock text-gray-400"></i>
                      </div>
                      <input
                        type={showPassword ? 'text' : 'password'}
                        name="password"
                        required
                        value={formData.password}
                        onChange={handleChange}
                        className={`block w-full pl-12 pr-12 py-3 border-2 ${errors.password ? 'border-red-500' : 'border-gray-200 dark:border-gray-700'} rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0 transition-colors`}
                        placeholder="••••••••"
                      />
                      <button
                        type="button"
                        onClick={() => setShowPassword(!showPassword)}
                        className="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                      >
                        <i className={`fas ${showPassword ? 'fa-eye-slash' : 'fa-eye'}`}></i>
                      </button>
                    </div>
                    {errors.password && <p className="mt-1 text-sm text-red-500">{errors.password}</p>}
                    
                    {/* Password Strength */}
                    {formData.password && (
                      <div className="mt-2">
                        <div className="flex items-center justify-between mb-1">
                          <span className="text-xs text-gray-600 dark:text-gray-400">
                            {t('auth.passwordStrength')}
                          </span>
                          <span className={`text-xs font-semibold ${
                            passwordStrength.strength <= 2 ? 'text-red-500' :
                            passwordStrength.strength <= 3 ? 'text-yellow-500' :
                            'text-green-500'
                          }`}>
                            {passwordStrength.label}
                          </span>
                        </div>
                        <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                          <div
                            className={`h-2 rounded-full transition-all duration-300 ${passwordStrength.color}`}
                            style={{ width: `${(passwordStrength.strength / 5) * 100}%` }}
                          ></div>
                        </div>
                      </div>
                    )}
                  </div>

                  {/* Confirm Password */}
                  <div>
                    <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                      {t('auth.confirmPassword')} *
                    </label>
                    <div className="relative">
                      <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i className="fas fa-lock text-gray-400"></i>
                      </div>
                      <input
                        type={showConfirmPassword ? 'text' : 'password'}
                        name="password_confirmation"
                        required
                        value={formData.password_confirmation}
                        onChange={handleChange}
                        className={`block w-full pl-12 pr-12 py-3 border-2 ${errors.password_confirmation ? 'border-red-500' : 'border-gray-200 dark:border-gray-700'} rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-purple-600 focus:ring-0 transition-colors`}
                        placeholder="••••••••"
                      />
                      <button
                        type="button"
                        onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                        className="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                      >
                        <i className={`fas ${showConfirmPassword ? 'fa-eye-slash' : 'fa-eye'}`}></i>
                      </button>
                    </div>
                    {errors.password_confirmation && <p className="mt-1 text-sm text-red-500">{errors.password_confirmation}</p>}
                  </div>

                  {/* Terms & Conditions */}
                  <div>
                    <label className="flex items-start">
                      <input
                        type="checkbox"
                        name="accept_terms"
                        checked={formData.accept_terms}
                        onChange={handleChange}
                        className="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                      />
                      <span className="ml-3 text-sm text-gray-700 dark:text-gray-300">
                        {t('auth.acceptTerms')}{' '}
                        <Link to="/terms" className="text-purple-600 hover:text-purple-700 font-semibold">
                          {t('auth.termsAndConditions')}
                        </Link>
                        {' '}{t('auth.and')}{' '}
                        <Link to="/privacy" className="text-purple-600 hover:text-purple-700 font-semibold">
                          {t('auth.privacyPolicy')}
                        </Link>
                      </span>
                    </label>
                    {errors.accept_terms && <p className="mt-1 text-sm text-red-500">{errors.accept_terms}</p>}
                  </div>

                  {/* Buttons */}
                  <div className="flex space-x-4">
                    <button
                      type="button"
                      onClick={handleBack}
                      className="flex-1 py-4 px-6 border-2 border-gray-300 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 font-bold hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors"
                    >
                      <i className="fas fa-arrow-left mr-2"></i>
                      {t('auth.back')}
                    </button>
                    <button
                      type="submit"
                      disabled={loading}
                      className="flex-1 flex justify-center items-center py-4 px-6 border border-transparent rounded-xl text-white font-bold bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-2xl"
                    >
                      {loading ? (
                        <>
                          <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                          </svg>
                          {t('auth.creating')}
                        </>
                      ) : (
                        <>
                          <i className="fas fa-user-plus mr-2"></i>
                          {t('auth.registerButton')}
                        </>
                      )}
                    </button>
                  </div>
                </div>
              )}
            </form>

            {/* Login Link */}
            <div className="mt-6 text-center">
              <p className="text-sm text-gray-600 dark:text-gray-400">
                {t('auth.alreadyHaveAccount')}{' '}
                <Link
                  to="/login"
                  className="font-bold text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300"
                >
                  {t('auth.loginLink')}
                </Link>
              </p>
            </div>
          </div>

          {/* Benefits */}
          <div className="bg-gradient-to-r from-purple-50 to-amber-50 dark:from-purple-900/20 dark:to-amber-900/20 rounded-2xl p-6">
            <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-4 text-center">
              {t('auth.whyJoinUs')}
            </h3>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div className="text-center">
                <div className="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-2">
                  <i className="fas fa-gift text-white text-xl"></i>
                </div>
                <p className="text-sm font-semibold text-gray-700 dark:text-gray-300">
                  {t('auth.exclusiveOffers')}
                </p>
              </div>
              <div className="text-center">
                <div className="w-12 h-12 bg-amber-600 rounded-full flex items-center justify-center mx-auto mb-2">
                  <i className="fas fa-star text-white text-xl"></i>
                </div>
                <p className="text-sm font-semibold text-gray-700 dark:text-gray-300">
                  {t('auth.loyaltyPoints')}
                </p>
              </div>
              <div className="text-center">
                <div className="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-2">
                  <i className="fas fa-headset text-white text-xl"></i>
                </div>
                <p className="text-sm font-semibold text-gray-700 dark:text-gray-300">
                  {t('auth.prioritySupport')}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <FooterModern />
    </div>
  );
};

export default Register;
