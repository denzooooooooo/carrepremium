@extends('admin.layouts.app')

@section('title', 'Modifier la Catégorie')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Modifier la Catégorie</h1>
            <p class="text-gray-600 mt-2">Modifiez les informations de la catégorie</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom FR -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nom (Français) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name_fr" value="{{ old('name_fr', $category->name_fr) }}" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name_fr') border-red-500 @enderror">
                    @error('name_fr')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nom EN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nom (Anglais) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name_en" value="{{ old('name_en', $category->name_en) }}" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name_en') border-red-500 @enderror">
                    @error('name_en')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Slug <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('slug') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Ex: vols, evenements-sportifs (utilisé dans les URLs)</p>
                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description FR -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description (Français)
                    </label>
                    <textarea name="description_fr" rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description_fr') border-red-500 @enderror">{{ old('description_fr', $category->description_fr) }}</textarea>
                    @error('description_fr')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description EN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description (Anglais)
                    </label>
                    <textarea name="description_en" rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description_en') border-red-500 @enderror">{{ old('description_en', $category->description_en) }}</textarea>
                    @error('description_en')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icône -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Icône (Font Awesome)
                    </label>
                    <div class="flex gap-2">
                        <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" 
                            placeholder="plane, trophy, music" 
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('icon') border-red-500 @enderror">
                        @if($category->icon)
                            <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg">
                                <i class="fas fa-{{ $category->icon }} text-2xl text-purple-600"></i>
                            </div>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Nom de l'icône sans "fa-" (ex: plane, trophy)</p>
                    @error('icon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catégorie Parente -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catégorie Parente
                    </label>
                    <select name="parent_id" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('parent_id') border-red-500 @enderror">
                        <option value="">Aucune (Catégorie principale)</option>
                        @foreach($categories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name_fr }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ordre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ordre d'affichage
                    </label>
                    <input type="number" name="order_position" value="{{ old('order_position', $category->order_position) }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('order_position') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Plus le nombre est petit, plus la catégorie apparaît en premier</p>
                    @error('order_position')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Image
                    </label>
                    <input type="file" name="image" accept="image/*" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('image') border-red-500 @enderror">
                    @if($category->image)
                        <div class="mt-2">
                            <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name_fr }}" class="h-20 w-20 object-cover rounded-lg">
                            <p class="text-xs text-gray-500 mt-1">Image actuelle</p>
                        </div>
                    @endif
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statut -->
                <div class="md:col-span-2">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} 
                            class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="ml-3 text-sm font-medium text-gray-700">
                            Catégorie active
                        </span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Les catégories inactives ne seront pas visibles sur le site</p>
                </div>
            </div>

            <!-- Informations supplémentaires -->
            @if($category->children->count() > 0)
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-blue-900">Sous-catégories</h4>
                        <p class="text-sm text-blue-700 mt-1">
                            Cette catégorie contient {{ $category->children->count() }} sous-catégorie(s) :
                            <span class="font-medium">
                                {{ $category->children->pluck('name_fr')->join(', ') }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Boutons -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.categories.index') }}" 
                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                    Annuler
                </a>
                <button type="submit" 
                    class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    <!-- Informations de création -->
    <div class="mt-6 bg-gray-50 rounded-lg p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
            <div>
                <i class="fas fa-calendar-plus text-gray-400 mr-2"></i>
                <span class="font-medium">Créée le:</span> {{ $category->created_at->format('d/m/Y à H:i') }}
            </div>
            <div>
                <i class="fas fa-calendar-check text-gray-400 mr-2"></i>
                <span class="font-medium">Dernière modification:</span> {{ $category->updated_at->format('d/m/Y à H:i') }}
            </div>
        </div>
    </div>
</div>

<script>
// Auto-génération du slug depuis le nom français
document.querySelector('input[name="name_fr"]').addEventListener('input', function(e) {
    const slug = e.target.value
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
    document.querySelector('input[name="slug"]').value = slug;
});
</script>
@endsection
