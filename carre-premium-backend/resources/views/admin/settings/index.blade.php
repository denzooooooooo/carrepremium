@extends('admin.layouts.app')

@section('title', 'Paramètres')
@section('page-title', 'Paramètres')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Paramètres du Site</h1>
        <button type="submit" form="settingsForm" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-purple-700">
            <i class="fas fa-save mr-2"></i>Enregistrer
        </button>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Général -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-bold mb-4 text-primary">
                    <i class="fas fa-info-circle mr-2"></i>Informations Générales
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Nom du Site</label>
                        <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Carré Premium' }}" 
                               class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="site_email" value="{{ $settings['site_email'] ?? '' }}" 
                               class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Téléphone</label>
                        <input type="text" name="site_phone" value="{{ $settings['site_phone'] ?? '' }}" 
                               class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Adresse</label>
                        <textarea name="site_address" rows="2" class="w-full px-3 py-2 border rounded-lg">{{ $settings['site_address'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Régional -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-bold mb-4 text-primary">
                    <i class="fas fa-globe mr-2"></i>Paramètres Régionaux
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Langue par Défaut</label>
                        <select name="default_language" class="w-full px-3 py-2 border rounded-lg">
                            <option value="fr" {{ ($settings['default_language'] ?? 'fr') == 'fr' ? 'selected' : '' }}>Français</option>
                            <option value="en" {{ ($settings['default_language'] ?? 'fr') == 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Devise par Défaut</label>
                        <select name="default_currency" class="w-full px-3 py-2 border rounded-lg">
                            <option value="XOF" {{ ($settings['default_currency'] ?? 'XOF') == 'XOF' ? 'selected' : '' }}>XOF - Franc CFA</option>
                            <option value="EUR" {{ ($settings['default_currency'] ?? 'XOF') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                            <option value="USD" {{ ($settings['default_currency'] ?? 'XOF') == 'USD' ? 'selected' : '' }}>USD - Dollar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Fuseau Horaire</label>
                        <select name="timezone" class="w-full px-3 py-2 border rounded-lg">
                            <option value="Africa/Abidjan">Africa/Abidjan (GMT)</option>
                            <option value="Europe/Paris">Europe/Paris (GMT+1)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Apparence -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-bold mb-4 text-primary">
                    <i class="fas fa-palette mr-2"></i>Apparence
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Mode Thème</label>
                        <select name="theme_mode" class="w-full px-3 py-2 border rounded-lg">
                            <option value="light">Clair</option>
                            <option value="dark">Sombre</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Couleur Primaire (Violet)</label>
                        <input type="color" name="primary_color" value="{{ $settings['primary_color'] ?? '#9333EA' }}" 
                               class="w-full h-10 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Couleur Secondaire (Doré)</label>
                        <input type="color" name="secondary_color" value="{{ $settings['secondary_color'] ?? '#D4AF37' }}" 
                               class="w-full h-10 border rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Fonctionnalités -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-bold mb-4 text-primary">
                    <i class="fas fa-cogs mr-2"></i>Fonctionnalités
                </h2>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="enable_chatbot" value="1" {{ ($settings['enable_chatbot'] ?? 'true') == 'true' ? 'checked' : '' }} class="mr-2">
                        <span>Activer le Chatbot</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="enable_whatsapp" value="1" {{ ($settings['enable_whatsapp'] ?? 'true') == 'true' ? 'checked' : '' }} class="mr-2">
                        <span>Activer WhatsApp</span>
                    </label>
                    <div>
                        <label class="block text-sm font-medium mb-1">Numéro WhatsApp</label>
                        <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}" 
                               placeholder="+225XXXXXXXXX" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <label class="flex items-center">
                        <input type="checkbox" name="enable_recommendations" value="1" {{ ($settings['enable_recommendations'] ?? 'true') == 'true' ? 'checked' : '' }} class="mr-2">
                        <span>Activer les Recommandations</span>
                    </label>
                </div>
            </div>

            <!-- Paiement -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-bold mb-4 text-primary">
                    <i class="fas fa-credit-card mr-2"></i>Paiement
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Taux de Taxe (%)</label>
                        <input type="number" step="0.01" name="tax_rate" value="{{ $settings['tax_rate'] ?? '0.18' }}" 
                               class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Frais de Réservation (XOF)</label>
                        <input type="number" name="booking_fee" value="{{ $settings['booking_fee'] ?? '5000' }}" 
                               class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Frais d'Annulation (%)</label>
                        <input type="number" name="cancellation_fee_percentage" value="{{ $settings['cancellation_fee_percentage'] ?? '10' }}" 
                               class="w-full px-3 py-2 border rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Réseaux Sociaux -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-bold mb-4 text-primary">
                    <i class="fas fa-share-alt mr-2"></i>Réseaux Sociaux
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            <i class="fab fa-facebook text-blue-600 mr-1"></i>Facebook
                        </label>
                        <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? '' }}" 
                               class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            <i class="fab fa-instagram text-pink-600 mr-1"></i>Instagram
                        </label>
                        <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? '' }}" 
                               class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            <i class="fab fa-twitter text-blue-400 mr-1"></i>Twitter
                        </label>
                        <input type="url" name="twitter_url" value="{{ $settings['twitter_url'] ?? '' }}" 
                               class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            <i class="fab fa-linkedin text-blue-700 mr-1"></i>LinkedIn
                        </label>
                        <input type="url" name="linkedin_url" value="{{ $settings['linkedin_url'] ?? '' }}" 
                               class="w-full px-3 py-2 border rounded-lg">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-primary to-purple-600 text-white rounded-lg hover:shadow-lg transition-all">
                <i class="fas fa-save mr-2"></i>Enregistrer tous les Paramètres
            </button>
        </div>
    </form>
</div>
@endsection
