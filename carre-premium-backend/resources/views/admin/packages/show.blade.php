@extends('admin.layouts.app')

@section('title', 'Détails du Package')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $package->title_fr }}</h1>
            <p class="text-gray-600 mt-2">{{ $package->title_en }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.packages.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
            <a href="{{ route('admin.packages.edit', $package->id) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg flex items-center gap-2">
                <i class="fas fa-edit"></i>
                Modifier
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Image principale -->
            @if($package->image)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="{{ Storage::url($package->image) }}" alt="{{ $package->title_fr }}" class="w-full h-96 object-cover">
            </div>
            @endif

            <!-- Informations générales -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-purple-600 mr-3"></i>
                    Informations Générales
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Type de Package</label>
                        <p class="text-lg font-semibold text-gray-800">
                            @if($package->package_type == 'helicopter') 🚁 Hélicoptère
                            @elseif($package->package_type == 'private_jet') ✈️ Jet Privé
                            @elseif($package->package_type == 'cruise') 🚢 Croisière
                            @elseif($package->package_type == 'safari') 🦁 Safari
                            @elseif($package->package_type == 'city_tour') 🏙️ City Tour
                            @elseif($package->package_type == 'adventure') 🏔️ Aventure
                            @else 💎 Luxe
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Destination</label>
                        <p class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            {{ $package->destination }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Durée</label>
                        <p class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-clock text-purple-600 mr-2"></i>
                            {{ $package->duration }} jour(s)
                        </p>
                        @if($package->duration_text_fr)
                            <p class="text-sm text-gray-600">{{ $package->duration_text_fr }}</p>
                        @endif
                    </div>

                    @if($package->departure_city)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Ville de Départ</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $package->departure_city }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Participants</label>
                        <p class="text-lg font-semibold text-gray-800">
                            Min: {{ $package->min_participants }} - Max: {{ $package->max_participants }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Prix</label>
                        <p class="text-2xl font-bold text-purple-600">
                            {{ number_format($package->price, 0, ',', ' ') }} XOF
                        </p>
                        @if($package->discount_price)
                            <p class="text-sm text-gray-500 line-through">
                                {{ number_format($package->discount_price, 0, ',', ' ') }} XOF
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-align-left text-purple-600 mr-3"></i>
                    Description
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">🇫🇷 Français</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $package->description_fr }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">🇬🇧 English</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $package->description_en }}</p>
                    </div>
                </div>
            </div>

            <!-- Services inclus -->
            @if($package->included_services_fr)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    Services Inclus
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-3">🇫🇷 Français</h3>
                        <ul class="space-y-2">
                            @foreach(json_decode($package->included_services_fr) as $service)
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                                <span class="text-gray-600">{{ $service }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-3">🇬🇧 English</h3>
                        <ul class="space-y-2">
                            @foreach(json_decode($package->included_services_en) as $service)
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                                <span class="text-gray-600">{{ $service }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Services exclus -->
            @if($package->excluded_services_fr)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-times-circle text-red-600 mr-3"></i>
                    Services Non Inclus
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-3">🇫🇷 Français</h3>
                        <ul class="space-y-2">
                            @foreach(json_decode($package->excluded_services_fr) as $service)
                            <li class="flex items-start">
                                <i class="fas fa-times text-red-600 mr-2 mt-1"></i>
                                <span class="text-gray-600">{{ $service }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-3">🇬🇧 English</h3>
                        <ul class="space-y-2">
                            @foreach(json_decode($package->excluded_services_en) as $service)
                            <li class="flex items-start">
                                <i class="fas fa-times text-red-600 mr-2 mt-1"></i>
                                <span class="text-gray-600">{{ $service }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Itinéraire -->
            @if($package->itinerary_fr)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-route text-purple-600 mr-3"></i>
                    Itinéraire Jour par Jour
                </h2>
                
                <div class="space-y-6">
                    @foreach(json_decode($package->itinerary_fr) as $index => $day)
                    <div class="border-l-4 border-purple-600 pl-4">
                        <h3 class="font-bold text-lg text-gray-800 mb-2">
                            Jour {{ $index + 1 }}
                            @if(isset($day->title))
                                - {{ $day->title }}
                            @endif
                        </h3>
                        <p class="text-gray-600">{{ $day->description ?? $day }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Galerie -->
            @if($package->gallery)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-images text-purple-600 mr-3"></i>
                    Galerie Photos
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach(json_decode($package->gallery) as $image)
                    <img src="{{ Storage::url($image) }}" alt="Gallery" class="w-full h-48 object-cover rounded-lg hover:scale-105 transition-transform">
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Dates disponibles -->
            @if($package->available_dates)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calendar-alt text-purple-600 mr-3"></i>
                    Dates Disponibles
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach(json_decode($package->available_dates) as $date)
                    <div class="p-3 bg-purple-50 rounded-lg text-center">
                        <i class="fas fa-calendar text-purple-600 mb-2"></i>
                        <p class="text-sm font-medium text-gray-800">
                            {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="space-y-6">
            <!-- Prix et réservation -->
            <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-lg shadow-md p-6 text-white">
                <h3 class="text-xl font-bold mb-4">Tarif</h3>
                
                <div class="text-center mb-6">
                    <p class="text-sm opacity-90 mb-2">À partir de</p>
                    <p class="text-4xl font-bold">
                        {{ number_format($package->price, 0, ',', ' ') }}
                    </p>
                    <p class="text-sm opacity-90">XOF / personne</p>
                    
                    @if($package->discount_price)
                    <p class="text-sm line-through opacity-75 mt-2">
                        {{ number_format($package->discount_price, 0, ',', ' ') }} XOF
                    </p>
                    <p class="text-sm bg-yellow-500 text-yellow-900 inline-block px-3 py-1 rounded-full mt-2">
                        -{{ round((($package->discount_price - $package->price) / $package->discount_price) * 100) }}%
                    </p>
                    @endif
                </div>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="opacity-90">Durée:</span>
                        <span class="font-semibold">{{ $package->duration }} jours</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="opacity-90">Participants:</span>
                        <span class="font-semibold">{{ $package->min_participants }}-{{ $package->max_participants }}</span>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Statistiques</h3>
                
                <div class="space-y-4">
                    @if($package->rating > 0)
                    <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Note Moyenne</span>
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-yellow-600 mr-2">{{ number_format($package->rating, 1) }}</span>
                            <i class="fas fa-star text-yellow-500"></i>
                        </div>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Avis</span>
                        <span class="text-2xl font-bold text-blue-600">{{ $package->total_reviews }}</span>
                    </div>
                </div>
            </div>

            <!-- Statut -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Statut</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Package</span>
                        @if($package->is_active)
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle"></i> Actif
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle"></i> Inactif
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Mis en Avant</span>
                        @if($package->is_featured)
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star"></i> Oui
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                Non
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Catégorie -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Catégorie</h3>
                
                @if($package->category)
                <div class="flex items-center p-3 bg-purple-50 rounded-lg">
                    @if($package->category->icon)
                        <i class="fas fa-{{ $package->category->icon }} text-2xl text-purple-600 mr-3"></i>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-800">{{ $package->category->name_fr }}</p>
                        <p class="text-sm text-gray-600">{{ $package->category->name_en }}</p>
                    </div>
                </div>
                @else
                <p class="text-gray-500">Aucune catégorie</p>
                @endif
            </div>

            <!-- Vidéo -->
            @if($package->video_url)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Vidéo</h3>
                
                <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden">
                    <iframe 
                        src="{{ $package->video_url }}" 
                        class="w-full h-full"
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
            @endif

            <!-- Dates -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Informations</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Créé le:</span>
                        <p class="font-medium text-gray-800">{{ $package->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Modifié le:</span>
                        <p class="font-medium text-gray-800">{{ $package->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
