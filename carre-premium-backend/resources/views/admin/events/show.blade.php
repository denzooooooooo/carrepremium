@extends('admin.layouts.app')

@section('title', 'Détails de l\'Événement')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $event->title_fr }}</h1>
            <p class="text-gray-600 mt-2">{{ $event->title_en }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.events.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
            <a href="{{ route('admin.events.edit', $event->id) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg flex items-center gap-2">
                <i class="fas fa-edit"></i>
                Modifier
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Image principale -->
            @if($event->image)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title_fr }}" class="w-full h-96 object-cover">
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
                        <label class="block text-sm font-medium text-gray-500 mb-1">Type d'Événement</label>
                        <p class="text-lg font-semibold text-gray-800">
                            @if($event->event_type == 'sport') 🏆 Sport
                            @elseif($event->event_type == 'concert') 🎵 Concert
                            @elseif($event->event_type == 'theater') 🎭 Théâtre
                            @elseif($event->event_type == 'festival') 🎉 Festival
                            @else 📅 Autre
                            @endif
                        </p>
                    </div>

                    @if($event->sport_type)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Sport</label>
                        <p class="text-lg font-semibold text-gray-800">{{ ucfirst($event->sport_type) }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Date de l'Événement</label>
                        <p class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-calendar text-purple-600 mr-2"></i>
                            {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Heure</label>
                        <p class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-clock text-purple-600 mr-2"></i>
                            {{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Lieu</label>
                        <p class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                            {{ $event->venue_name }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Ville</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $event->city }}, {{ $event->country }}</p>
                    </div>

                    @if($event->organizer)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Organisateur</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $event->organizer }}</p>
                    </div>
                    @endif
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
                        <p class="text-gray-600 leading-relaxed">{{ $event->description_fr }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">🇬🇧 English</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $event->description_en }}</p>
                    </div>
                </div>
            </div>

            <!-- Zones de sièges -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-chair text-purple-600 mr-3"></i>
                    Zones de Sièges ({{ $event->seatZones->count() }})
                </h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Zone</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Places Totales</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Disponibles</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendues</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($event->seatZones as $zone)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="font-medium text-gray-900">{{ $zone->zone_name_fr }}</div>
                                    <div class="text-sm text-gray-500">{{ $zone->zone_code }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-lg font-bold text-purple-600">
                                        {{ number_format($zone->price, 0, ',', ' ') }} XOF
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $zone->total_seats }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        {{ $zone->available_seats }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                        {{ $zone->total_seats - $zone->available_seats }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    @if($zone->is_active)
                                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle"></i> Actif
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle"></i> Inactif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    Aucune zone de siège configurée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Galerie -->
            @if($event->gallery)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-images text-purple-600 mr-3"></i>
                    Galerie Photos
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach(json_decode($event->gallery) as $image)
                    <img src="{{ Storage::url($image) }}" alt="Gallery" class="w-full h-48 object-cover rounded-lg">
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="space-y-6">
            <!-- Statistiques -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Statistiques</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Places Totales</span>
                        <span class="text-2xl font-bold text-blue-600">{{ $event->total_seats }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Disponibles</span>
                        <span class="text-2xl font-bold text-green-600">{{ $event->available_seats }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Vendues</span>
                        <span class="text-2xl font-bold text-orange-600">{{ $event->total_seats - $event->available_seats }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Taux de Remplissage</span>
                        <span class="text-2xl font-bold text-purple-600">
                            {{ $event->total_seats > 0 ? round((($event->total_seats - $event->available_seats) / $event->total_seats) * 100) : 0 }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Prix -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Tarification</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Prix Minimum</span>
                        <span class="text-lg font-bold text-green-600">
                            {{ number_format($event->min_price, 0, ',', ' ') }} XOF
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Prix Maximum</span>
                        <span class="text-lg font-bold text-purple-600">
                            {{ number_format($event->max_price, 0, ',', ' ') }} XOF
                        </span>
                    </div>
                </div>
            </div>

            <!-- Statut -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Statut</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Événement</span>
                        @if($event->is_active)
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
                        @if($event->is_featured)
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
                
                @if($event->category)
                <div class="flex items-center p-3 bg-purple-50 rounded-lg">
                    @if($event->category->icon)
                        <i class="fas fa-{{ $event->category->icon }} text-2xl text-purple-600 mr-3"></i>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-800">{{ $event->category->name_fr }}</p>
                        <p class="text-sm text-gray-600">{{ $event->category->name_en }}</p>
                    </div>
                </div>
                @else
                <p class="text-gray-500">Aucune catégorie</p>
                @endif
            </div>

            <!-- Dates -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Informations</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Créé le:</span>
                        <p class="font-medium text-gray-800">{{ $event->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Modifié le:</span>
                        <p class="font-medium text-gray-800">{{ $event->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
