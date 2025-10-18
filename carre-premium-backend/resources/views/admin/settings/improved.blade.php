@extends('admin.layouts.app')

@section('title', 'Paramètres du Site')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Paramètres du Site</h1>
            <p class="text-gray-600 mt-2">Configurez tous les aspects de votre plateforme</p>
        </div>
        <button type="submit" form="settingsForm" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 shadow-lg">
            <i class="fas fa-save"></i>
            Enregistrer les modifications
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center">
        <i class="fas fa-check-circle text-2xl mr-3"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <p class="font-semibold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Erreurs de validation:</p>
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px overflow-x-auto">
                <button type="button" onclick="switchTab('general')" class="tab-button active px-6 py-4 text-sm font-medium border-b-2 border-purple-600 text-purple-600 whitespace-nowrap">
                    <i class="fas fa-info-circle mr-2"></i>Général
                </button>
                <button type="button" onclick="switchTab('regional')" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                    <i class="fas fa-globe mr-2"></i>Régional
                </button>
                <button type="button" onclick="switchTab('appearance')" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                    <i class="fas fa-palette mr-2"></i>Apparence
                </button>
                <button type="button" onclick="switchTab('features')" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                    <i class="fas fa-cogs mr-2"></i>Fonctionnalités
                </button>
                <button type="button" onclick="switchTab('payment')" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                    <i class="fas fa-credit-card mr-2"></i>Paiement
                </button>
                <button type="button" onclick="switchTab('email')" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                    <i class="fas fa-envelope mr-2"></i>Email
                </button>
                <button type="button" onclick="switchTab('social')" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                    <i class="fas fa-share-alt mr-2"></i>Réseaux Sociaux
                </button>
                <button type="button" onclick="switchTab('seo')" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                    <i class="fas fa-search mr-2"></i>SEO
                </button>
            </nav>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Tab: Général -->
        <div id="tab-general" class="tab-content">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-purple-600 mr-3"></i>
                    Informations Générales
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nom du Site <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Carré Premium' }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Slogan du Site
                        </label>
                        <input type="text" name="site_slogan" value="{{ $settings['site_slogan'] ?? 'Votre partenaire voyage premium' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email de Contact <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="site_email" value="{{ $settings['site_email'] ?? 'contact@carrepremium.com' }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="site_phone" value="{{ $settings['site_phone'] ?? '+225 XX XX XX XX XX' }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Adresse Complète
                        </label>
                        <textarea name="site_address" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ $settings['site_address'] ?? 'Abidjan, Côte d\'Ivoire' }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Description du Site
                        </label>
                        <textarea name="site_description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ $settings['site_description'] ?? 'Plateforme de réservation de billets d\'avion, événements et packages touristiques premium' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Logo du Site
                        </label>
                        <input type="file" name="site_logo" accept="image/*"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @if(isset($settings['site_logo']))
                            <img src="{{ Storage::url($settings['site_logo']) }}" alt="Logo" class="mt-2 h-16">
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Favicon
                        </label>
                        <input type="file" name="site_favicon" accept="image/*"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Régional -->
        <div id="tab-regional" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-globe text-purple-600 mr-3"></i>
                    Paramètres Régionaux
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Langue par Défaut
                        </label>
                        <select name="default_language" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="fr" {{ ($settings['default_language'] ?? 'fr') == 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                            <option value="en" {{ ($settings['default_language'] ?? 'fr') == 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Devise par Défaut
                        </label>
                        <select name="default_currency" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="XOF" {{ ($settings['default_currency'] ?? 'XOF') == 'XOF' ? 'selected' : '' }}>XOF - Franc CFA</option>
                            <option value="EUR" {{ ($settings['default_currency'] ?? 'XOF') == 'EUR' ? 'selected' : '' }}>EUR - Euro (€)</option>
                            <option value="USD" {{ ($settings['default_currency'] ?? 'XOF') == 'USD' ? 'selected' : '' }}>USD - Dollar ($)</option>
                            <option value="GBP" {{ ($settings['default_currency'] ?? 'XOF') == 'GBP' ? 'selected' : '' }}>GBP - Livre Sterling (£)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Fuseau Horaire
                        </label>
                        <select name="timezone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="Africa/Abidjan" {{ ($settings['timezone'] ?? 'Africa/Abidjan') == 'Africa/Abidjan' ? 'selected' : '' }}>Africa/Abidjan (GMT)</option>
                            <option value="Europe/Paris" {{ ($settings['timezone'] ?? 'Africa/Abidjan') == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris (GMT+1)</option>
                            <option value="America/New_York" {{ ($settings['timezone'] ?? 'Africa/Abidjan') == 'America/New_York' ? 'selected' : '' }}>America/New_York (GMT-5)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Format de Date
                        </label>
                        <select name="date_format" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="d/m/Y">DD/MM/YYYY (31/12/2024)</option>
                            <option value="m/d/Y">MM/DD/YYYY (12/31/2024)</option>
                            <option value="Y-m-d">YYYY-MM-DD (2024-12-31)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_multi_currency" value="1" {{ ($settings['enable_multi_currency'] ?? 'true') == 'true' ? 'checked' : '' }}
                                class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="ml-3 text-sm font-medium text-gray-700">
                                Activer le multi-devises (permettre aux utilisateurs de choisir leur devise)
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Apparence -->
        <div id="tab-appearance" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-palette text-purple-600 mr-3"></i>
                    Apparence et Design
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Mode Thème par Défaut
                        </label>
                        <select name="theme_mode" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="light" {{ ($settings['theme_mode'] ?? 'light') == 'light' ? 'selected' : '' }}>☀️ Clair</option>
                            <option value="dark" {{ ($settings['theme_mode'] ?? 'light') == 'dark' ? 'selected' : '' }}>🌙 Sombre</option>
                            <option value="auto" {{ ($settings['theme_mode'] ?? 'light') == 'auto' ? 'selected' : '' }}>🔄 Automatique</option>
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="allow_theme_switch" value="1" {{ ($settings['allow_theme_switch'] ?? 'true') == 'true' ? 'checked' : '' }}
                                class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="ml-3 text-sm font-medium text-gray-700">
                                Permettre aux utilisateurs de changer le thème
                            </span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Couleur Primaire (Violet)
                        </label>
                        <div class="flex gap-2">
                            <input type="color" name="primary_color" value="{{ $settings['primary_color'] ?? '#9333EA' }}"
                                class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer">
                            <input type="text" value="{{ $settings['primary_color'] ?? '#9333EA' }}" readonly
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-gray-50">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Couleur Secondaire (Doré)
                        </label>
                        <div class="flex gap-2">
                            <input type="color" name="secondary_color" value="{{ $settings['secondary_color'] ?? '#D4AF37' }}"
                                class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer">
                            <input type="text" value="{{ $settings['secondary_color'] ?? '#D4AF37' }}" readonly
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-gray-50">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Police Principale
                        </label>
                        <select name="primary_font" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="Montserrat" {{ ($settings['primary_font'] ?? 'Montserrat') == 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                            <option value="Poppins" {{ ($settings['primary_font'] ?? 'Montserrat') == 'Poppins' ? 'selected' : '' }}>Poppins</option>
                            <option value="Inter" {{ ($settings['primary_font'] ?? 'Montserrat') == 'Inter' ? 'selected' : '' }}>Inter</option>
                            <option value="Roboto" {{ ($settings['primary_font'] ?? 'Montserrat') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Police Secondaire
                        </label>
                        <select name="secondary_font" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="Poppins" {{ ($settings['secondary_font'] ?? 'Poppins') == 'Poppins' ? 'selected' : '' }}>Poppins</option>
                            <option value="Montserrat" {{ ($settings['secondary_font'] ?? 'Poppins') == 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                            <option value="Open Sans" {{ ($settings['secondary_font'] ?? 'Poppins') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Fonctionnalités -->
        <div id="tab-features" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-cogs text-purple-600 mr-3"></i>
                    Fonctionnalités du Site
                </h2>
                
                <div class="space-y-6">
                    <!-- Chatbot -->
                    <div class="p-6 bg-gray-50 rounded-lg">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-robot text-3xl text-purple-600 mr-4"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Chatbot IA</h3>
                                    <p class="text-sm text-gray-600">Assistant virtuel pour aider les clients</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_chatbot" value="1" {{ ($settings['enable_chatbot'] ?? 'true') == 'true' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">API Key Chatbot</label>
                                <input type="text" name="chatbot_api_key" value="{{ $settings['chatbot_api_key'] ?? '' }}" placeholder="sk-..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Modèle IA</label>
                                <select name="chatbot_model" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="gpt-4">GPT-4</option>
                                    <option value="gpt-3.5-turbo">GPT-3.5 Turbo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- WhatsApp -->
                    <div class="p-6 bg-gray-50 rounded-lg">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center">
                                <i class="fab fa-whatsapp text-3xl text-green-600 mr-4"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">WhatsApp Business</h3>
                                    <p class="text-sm text-gray-600">Chat direct via WhatsApp</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_whatsapp" value="1" {{ ($settings['enable_whatsapp'] ?? 'true') == 'true' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Numéro WhatsApp</label>
                            <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '+225XXXXXXXXX' }}" placeholder="+225XXXXXXXXX"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>

                    <!-- Recommandations -->
                    <div class="p-6 bg-gray-50 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-magic text-3xl text-purple-600 mr-4"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Recommandations Personnalisées</h3>
                                    <p class="text-sm text-gray-600">Suggestions basées sur les préférences utilisateur</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_recommendations" value="1" {{ ($settings['enable_recommendations'] ?? 'true') == 'true' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Newsletter -->
                    <div class="p-6 bg-gray-50 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-envelope-open-text text-3xl text-blue-600 mr-4"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Newsletter</h3>
                                    <p class="text-sm text-gray-600">Inscription à la newsletter</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_newsletter" value="1" {{ ($settings['enable_newsletter'] ?? 'true') == 'true' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Reviews -->
                    <div class="p-6 bg-gray-50 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-star text-3xl text-yellow-500 mr-4"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Avis Clients</h3>
                                    <p class="text-sm text-gray-600">Permettre aux clients de laisser des avis</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_reviews" value="1" {{ ($settings['enable_reviews'] ?? 'true') == 'true' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Paiement -->
        <div id="tab-payment" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="
