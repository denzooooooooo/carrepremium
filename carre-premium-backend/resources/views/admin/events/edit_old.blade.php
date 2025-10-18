@extends('admin.layouts.app')

@section('title', 'Modifier l\'Événement')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Modifier l'Événement</h1>
            <p class="text-gray-600 mt-2">{{ $event->title_fr }}</p>
        </div>
        <a href="{{ route('admin.events.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Titre FR -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre (Français) <span class="text-red-500">*</span></label>
                    <input type="text" name="title_fr" value="{{ old('title_fr', $event->title_fr) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <!-- Titre EN -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre (Anglais) <span class="text-red-500">*</span></label>
                    <input type="text" name="title_en" value="{{ old('title_en', $event->title_en) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <!-- Type d'événement -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type d'événement <span class="text-red-500">*</span></label>
                    <select name="event_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Sélectionner</option>
                        <option value="sport" {{ $event->event_type == 'sport' ? 'selected' : '' }}>Sport</option>
                        <option value="concert" {{ $event->event_type == 'concert' ? 'selected' : '' }}>Concert</option>
                        <option value="theater" {{ $event->event_type == 'theater' ? 'selected' : '' }}>Théâtre</option>
                        <option value="festival" {{ $event->event_type == 'festival' ? 'selected' : '' }}>Festival</option>
                        <option value="other" {{ $event->event_type == 'other' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                <!-- Sport Type (si sport) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de sport</label>
                    <input type="text" name="sport_type" value="{{ old('sport_type', $event->sport_type) }}"
                           placeholder="tennis, football, formula1..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Lieu -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lieu <span class="text-red-500">*</span></label>
                    <input type="text" name="venue_name" value="{{ old('venue_name', $event->venue_name) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Adresse -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                    <input type="text" name="venue_address" value="{{ old('venue_address', $event->venue_address) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Ville -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ville <span class="text-red-500">*</span></label>
                    <input type="text" name="city" value="{{ old('city', $event->city) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Pays -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pays <span class="text-red-500">*</span></label>
                    <input type="text" name="country" value="{{ old('country', $event->country) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="event_date" value="{{ old('event_date', $event->event_date) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Heure -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Heure <span class="text-red-500">*</span></label>
                    <input type="time" name="event_time" value="{{ old('event_time', $event->event_time) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Date fin -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $event->end_date) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Heure fin -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Heure de fin</label>
                    <input type="time" name="end_time" value="{{ old('end_time', $event->end_time) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Organisateur -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Organisateur</label>
                    <input type="text" name="organizer" value="{{ old('organizer', $event->organizer) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Prix min -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix minimum (XOF) <span class="text-red-500">*</span></label>
                    <input type="number" name="min_price" value="{{ old('min_price', $event->min_price) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Prix max -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix maximum (XOF) <span class="text-red-500">*</span></label>
                    <input type="number" name="max_price" value="{{ old('max_price', $event->max_price) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Total sièges -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total sièges <span class="text-red-500">*</span></label>
                    <input type="number" name="total_seats" value="{{ old('total_seats', $event->total_seats) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Sièges disponibles -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sièges disponibles <span class="text-red-500">*</span></label>
                    <input type="number" name="available_seats" value="{{ old('available_seats', $event->available_seats) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- En vedette -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ $event->is_featured ? 'checked' : '' }}
                           class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <label class="ml-2 text-sm font-medium text-gray-700">En vedette</label>
                </div>

                <!-- Actif -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ $event->is_active ? 'checked' : '' }}
                           class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <label class="ml-2 text-sm font-medium text-gray-700">Actif</label>
                </div>

                <!-- Description FR -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (Français)</label>
                    <textarea name="description_fr" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ old('description_fr', $event->description_fr) }}</textarea>
                </div>

                <!-- Description EN -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (Anglais)</label>
                    <textarea name="description_en" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ old('description_en', $event->description_en) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.events.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
