@extends('admin.layouts.app')

@section('title', 'Créer un Package')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Créer un Package Touristique</h1>
            <p class="text-gray-600 mt-2">Ajoutez un nouveau package (hélicoptère, jet privé, safari, etc.)</p>
        </div>
        <a href="{{ route('admin.packages.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Titre FR -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre (Français) <span class="text-red-500">*</span></label>
                    <input type="text" name="title_fr" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <!-- Titre EN -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre (Anglais) <span class="text-red-500">*</span></label>
                    <input type="text" name="title_en" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <!-- Type de package -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de package <span class="text-red-500">*</span></label>
                    <select name="package_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Sélectionner</option>
                        <option value="helicopter">Hélicoptère</option>
                        <option value="private_jet">Jet Privé</option>
                        <option value="cruise">Croisière</option>
                        <option value="safari">Safari</option>
                        <option value="city_tour">City Tour</option>
                        <option value="adventure">Aventure</option>
                        <option value="luxury">Luxe</option>
                    </select>
                </div>

                <!-- Destination -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Destination <span class="text-red-500">*</span></label>
                    <input type="text" name="destination" required
                           placeholder="Ex: Abidjan, Côte d'Ivoire"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Durée (jours) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durée (jours) <span class="text-red-500">*</span></label>
                    <input type="number" name="duration" required min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Durée texte FR -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durée texte (FR)</label>
                    <input type="text" name="duration_text_fr"
                           placeholder="Ex: 3 jours / 2 nuits"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Ville de départ -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ville de départ</label>
                    <input type="text" name="departure_city"
                           placeholder="Ex: Abidjan"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Prix -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix (XOF) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" required min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Prix réduit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix réduit (XOF)</label>
                    <input type="number" name="discount_price" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Participants max -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Participants max <span class="text-red-500">*</span></label>
                    <input type="number" name="max_participants" required min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Participants min -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Participants min <span class="text-red-500">*</span></label>
                    <input type="number" name="min_participants" required min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Description FR -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (Français)</label>
                    <textarea name="description_fr" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"></textarea>
                </div>

                <!-- Description EN -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (Anglais)</label>
                    <textarea name="description_en" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"></textarea>
                </div>

                <!-- Image principale -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image principale du package</label>
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

                <!-- Galerie d'images -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Galerie d'images (optionnel)</label>
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

                <!-- Catégorie -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach(\App\Models\Category::where('is_active', true)->get() as $category)
                            <option value="{{ $category->id }}">{{ $category->name_fr }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- En vedette -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Mettre en vedette ce package</span>
                    </label>
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
                                <img src="${e.target.result}" alt="Galerie ${index + 1}" class="w-full h-32 object-cover rounded-lg shadow-md">
                                <span class="absolute top-2 right-2 bg-purple-600 text-white text-xs px-2 py-1 rounded">${index + 1}</span>
                            `;
                            preview.appendChild(div);
                        }
                        reader.readAsDataURL(file);
                    });
                }
            }
            </script>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.packages.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Créer le package
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
