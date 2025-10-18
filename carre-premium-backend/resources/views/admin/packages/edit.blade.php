@extends('admin.layouts.app')

@section('title', 'Modifier le Package')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Modifier le Package</h1>
            <p class="text-gray-600 mt-2">{{ $package->title_fr }}</p>
        </div>
        <a href="{{ route('admin.packages.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.packages.update', $package->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Titre FR -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre (Français) <span class="text-red-500">*</span></label>
                    <input type="text" name="title_fr" value="{{ old('title_fr', $package->title_fr) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <!-- Titre EN -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre (Anglais) <span class="text-red-500">*</span></label>
                    <input type="text" name="title_en" value="{{ old('title_en', $package->title_en) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <!-- Type de package -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de package <span class="text-red-500">*</span></label>
                    <select name="package_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Sélectionner</option>
                        <option value="helicopter" {{ $package->package_type == 'helicopter' ? 'selected' : '' }}>Hélicoptère</option>
                        <option value="private_jet" {{ $package->package_type == 'private_jet' ? 'selected' : '' }}>Jet Privé</option>
                        <option value="cruise" {{ $package->package_type == 'cruise' ? 'selected' : '' }}>Croisière</option>
                        <option value="safari" {{ $package->package_type == 'safari' ? 'selected' : '' }}>Safari</option>
                        <option value="city_tour" {{ $package->package_type == 'city_tour' ? 'selected' : '' }}>City Tour</option>
                        <option value="adventure" {{ $package->package_type == 'adventure' ? 'selected' : '' }}>Aventure</option>
                        <option value="luxury" {{ $package->package_type == 'luxury' ? 'selected' : '' }}>Luxe</option>
                    </select>
                </div>

                <!-- Destination -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Destination <span class="text-red-500">*</span></label>
                    <input type="text" name="destination" value="{{ old('destination', $package->destination) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Durée (jours) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durée (jours) <span class="text-red-500">*</span></label>
                    <input type="number" name="duration" value="{{ old('duration', $package->duration) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Durée texte FR -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durée texte (FR)</label>
                    <input type="text" name="duration_text_fr" value="{{ old('duration_text_fr', $package->duration_text_fr) }}"
                           placeholder="Ex: 3 jours / 2 nuits"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Ville de départ -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ville de départ</label>
                    <input type="text" name="departure_city" value="{{ old('departure_city', $package->departure_city) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Prix -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix (XOF) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price', $package->price) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Prix réduit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix réduit (XOF)</label>
                    <input type="number" name="discount_price" value="{{ old('discount_price', $package->discount_price) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Participants max -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Participants max <span class="text-red-500">*</span></label>
                    <input type="number" name="max_participants" value="{{ old('max_participants', $package->max_participants) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Participants min -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Participants min <span class="text-red-500">*</span></label>
                    <input type="number" name="min_participants" value="{{ old('min_participants', $package->min_participants) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Note -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Note (0-5)</label>
                    <input type="number" step="0.1" min="0" max="5" name="rating" value="{{ old('rating', $package->rating) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Total avis -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total avis</label>
                    <input type="number" name="total_reviews" value="{{ old('total_reviews', $package->total_reviews) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- En vedette -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ $package->is_featured ? 'checked' : '' }}
                           class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <label class="ml-2 text-sm font-medium text-gray-700">En vedette</label>
                </div>

                <!-- Actif -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ $package->is_active ? 'checked' : '' }}
                           class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <label class="ml-2 text-sm font-medium text-gray-700">Actif</label>
                </div>

                <!-- Description FR -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (Français)</label>
                    <textarea name="description_fr" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ old('description_fr', $package->description_fr) }}</textarea>
                </div>

                <!-- Description EN -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (Anglais)</label>
                    <textarea name="description_en" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ old('description_en', $package->description_en) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.packages.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
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
