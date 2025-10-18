import React, { useState } from 'react';

const PassengerForm = ({ passengers, onPassengersUpdate }) => {
  const [passengerData, setPassengerData] = useState(
    Array.from({ length: passengers }, (_, i) => ({
      id: i + 1,
      title: 'Mr',
      firstName: '',
      lastName: '',
      dateOfBirth: '',
      nationality: 'Côte d\'Ivoire',
      passportNumber: '',
      email: i === 0 ? '' : '',
      phone: i === 0 ? '' : ''
    }))
  );

  const handleChange = (index, field, value) => {
    const updated = [...passengerData];
    updated[index][field] = value;
    setPassengerData(updated);
    onPassengersUpdate(updated);
  };

  const countries = [
    'Côte d\'Ivoire', 'France', 'Sénégal', 'Mali', 'Burkina Faso', 'Bénin', 
    'Togo', 'Ghana', 'Nigeria', 'Cameroun', 'Gabon', 'Congo', 'Autre'
  ];

  return (
    <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl">
      <h2 className="text-2xl font-black mb-6">Informations des Passagers</h2>
      
      <div className="space-y-8">
        {passengerData.map((passenger, index) => (
          <div key={passenger.id} className="p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl">
            <h3 className="text-lg font-bold mb-4 flex items-center">
              <span className="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center mr-3">
                {index + 1}
              </span>
              Passager {index + 1} {index === 0 && <span className="ml-2 text-sm text-purple-600">(Contact principal)</span>}
            </h3>
            
            <div className="grid md:grid-cols-2 gap-4">
              {/* Civilité */}
              <div>
                <label className="block text-sm font-bold mb-2">Civilité *</label>
                <select
                  value={passenger.title}
                  onChange={(e) => handleChange(index, 'title', e.target.value)}
                  className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-purple-600 focus:outline-none"
                  required
                >
                  <option value="Mr">M.</option>
                  <option value="Mrs">Mme</option>
                  <option value="Miss">Mlle</option>
                </select>
              </div>

              {/* Prénom */}
              <div>
                <label className="block text-sm font-bold mb-2">Prénom *</label>
                <input
                  type="text"
                  value={passenger.firstName}
                  onChange={(e) => handleChange(index, 'firstName', e.target.value)}
                  placeholder="Prénom"
                  className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-purple-600 focus:outline-none"
                  required
                />
              </div>

              {/* Nom */}
              <div>
                <label className="block text-sm font-bold mb-2">Nom *</label>
                <input
                  type="text"
                  value={passenger.lastName}
                  onChange={(e) => handleChange(index, 'lastName', e.target.value)}
                  placeholder="Nom"
                  className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-purple-600 focus:outline-none"
                  required
                />
              </div>

              {/* Date de naissance */}
              <div>
                <label className="block text-sm font-bold mb-2">Date de naissance *</label>
                <input
                  type="date"
                  value={passenger.dateOfBirth}
                  onChange={(e) => handleChange(index, 'dateOfBirth', e.target.value)}
                  className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-purple-600 focus:outline-none"
                  required
                />
              </div>

              {/* Nationalité */}
              <div>
                <label className="block text-sm font-bold mb-2">Nationalité *</label>
                <select
                  value={passenger.nationality}
                  onChange={(e) => handleChange(index, 'nationality', e.target.value)}
                  className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-purple-600 focus:outline-none"
                  required
                >
                  {countries.map(country => (
                    <option key={country} value={country}>{country}</option>
                  ))}
                </select>
              </div>

              {/* Numéro de passeport */}
              <div>
                <label className="block text-sm font-bold mb-2">N° Passeport *</label>
                <input
                  type="text"
                  value={passenger.passportNumber}
                  onChange={(e) => handleChange(index, 'passportNumber', e.target.value)}
                  placeholder="XX1234567"
                  className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-purple-600 focus:outline-none"
                  required
                />
              </div>

              {/* Email (uniquement pour le premier passager) */}
              {index === 0 && (
                <>
                  <div>
                    <label className="block text-sm font-bold mb-2">Email *</label>
                    <input
                      type="email"
                      value={passenger.email}
                      onChange={(e) => handleChange(index, 'email', e.target.value)}
                      placeholder="email@exemple.com"
                      className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-purple-600 focus:outline-none"
                      required
                    />
                  </div>

                  {/* Téléphone */}
                  <div>
                    <label className="block text-sm font-bold mb-2">Téléphone *</label>
                    <input
                      type="tel"
                      value={passenger.phone}
                      onChange={(e) => handleChange(index, 'phone', e.target.value)}
                      placeholder="+225 XX XX XX XX XX"
                      className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-purple-600 focus:outline-none"
                      required
                    />
                  </div>
                </>
              )}
            </div>
          </div>
        ))}
      </div>

      <div className="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
        <div className="flex items-start space-x-3">
          <svg className="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clipRule="evenodd" />
          </svg>
          <div className="text-sm text-gray-700 dark:text-gray-300">
            <p className="font-bold mb-1">Important:</p>
            <ul className="list-disc list-inside space-y-1">
              <li>Les noms doivent correspondre exactement à ceux du passeport</li>
              <li>Vérifiez la validité de votre passeport (min. 6 mois)</li>
              <li>Un email de confirmation sera envoyé au contact principal</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  );
};

export default PassengerForm;
