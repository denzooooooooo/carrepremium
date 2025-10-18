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
                <!-- Catégorie -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $event->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name_fr }}
                            </option>
                        @endforeach
                    </select>
                </div>

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

                <!-- Image principale actuelle -->
                @if($event->image)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image actuelle</label>
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title_fr }}" class="max-w-xs rounded-lg shadow-md">
                    </div>
                </div>
                @endif

                <!-- Nouvelle image principale -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $event->image ? 'Changer l\'image principale' : 'Ajouter une image principale' }}
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                    <span>Télécharger une image</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" onchange="previewImage(event)">
                                </label>
                                <p class="pl-1">ou glisser-déposer</p>
                            </div>
                            <p class="text-xs text-gray-500">JPEG, PNG, GIF, WebP jusqu'à 5MB</p>
                        </div>
                    </div>
                    <div id="imagePreview" class="mt-4 hidden">
                        <img id="preview" src="" alt="Aperçu" class="max-w-xs rounded-lg shadow-md">
                    </div>
                </div>

                <!-- Galerie actuelle -->
                @if($event->gallery && is_array(json_decode($event->gallery, true)) && count(json_decode($event->gallery, true)) > 0)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Galerie actuelle</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                        @foreach(json_decode($event->gallery, true) as $index => $galleryImage)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $galleryImage) }}" alt="Galerie {{ $index + 1 }}" class="w-full h-32 object-cover rounded-lg shadow-md">
                            <span class="absolute top-2 right-2 bg-purple-600 text-white text-xs px-2 py-1 rounded">{{ $index + 1 }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Nouvelle galerie -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $event->gallery ? 'Ajouter des images à la galerie' : 'Créer une galerie d\'images' }}
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="gallery" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                    <span>Télécharger plusieurs images</span>
                                    <input id="gallery" name="gallery[]" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" multiple onchange="previewGallery(event)">
                                </label>
                                <p class="pl-1">ou glisser-déposer</p>
                            </div>
                            <p class="text-xs text-gray-500">Sélectionnez plusieurs images - JPEG, PNG, GIF, WebP jusqu'à 5MB chacune</p>
                        </div>
                    </div>
                    <div id="galleryPreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden"></div>
                </div>
            </div>

            <script>
            function previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('preview').src = e.target.result;
                        document.getElementById('imagePreview').classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            }

            function previewGallery(event) {
                const files = event.target.files;
                const preview = document.getElementById('galleryPreview');
                preview.innerHTML = '';
                
                if (files.length > 0) {
                    preview.classList.remove('hidden');
                    Array.from(files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'relative';
                            div.innerHTML = `
                                <img src="${e.target.result}" alt="Nouvelle ${index + 1}" class="w-full h-32 object-cover rounded-lg shadow-md">
                                <span class="absolute top-2 right-2 bg-green-600 text-white text-xs px-2 py-1 rounded">Nouveau ${index + 1}</span>
                            `;
                            preview.appendChild(div);
                        }
                        reader.readAsDataURL(file);
                    });
                }
            }
            </script>

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
