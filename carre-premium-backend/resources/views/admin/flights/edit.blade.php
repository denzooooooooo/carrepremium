@extends('admin.layouts.app')

@section('title', 'Modifier le Vol')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Modifier le Vol</h1>
            <p class="text-gray-600 mt-2">{{ $flight->flight_number }}</p>
        </div>
        <a href="{{ route('admin.flights.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Retour à la liste
        </a>
    </div>

    <!-- Formulaire -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.flights.update', $flight->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Image Upload Section -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h5 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-image text-purple-600 mr-2"></i>
                    Image du Vol
                </h5>
                
                <!-- Current Image -->
                @if($flight->image)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Image Actuelle:</label>
                        <div class="relative inline-block">
                            <img src="{{ asset('storage/' . $flight->image) }}" alt="Current" class="rounded-lg shadow-md" style="max-width: 300px; max-height: 200px;">
                        </div>
                    </div>
                @endif

                <!-- New Image Upload -->
                <div class="mb-3">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $flight->image ? 'Changer l\'image' : 'Ajouter une image' }} (Optionnel)
                    </label>
                    <input type="file" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('image') border-red-500 @enderror" 
                           id="image" 
                           name="image" 
                           accept="image/*"
                           onchange="previewImage(event)">
                    <small class="text-gray-500 text-sm">
                        Formats acceptés: JPEG, PNG, GIF, WebP. Taille max: 5 Mo
                    </small>
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Preview -->
                <div id="imagePreview" class="mt-3" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aperçu:</label>
                    <div class="relative inline-block">
                        <img id="preview" src="" alt="Preview" class="rounded-lg shadow-md" style="max-width: 300px; max-height: 200px;">
                        <button type="button" class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full text-sm" onclick="removeImage()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- N° de vol -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">N° de vol <span class="text-red-500">*</span></label>
                    <input type="text" name="flight_number" value="{{ old('flight_number', $flight->flight_number) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('flight_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Compagnie -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Compagnie <span class="text-red-500">*</span></label>
                    <select name="airline_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Sélectionner</option>
                        @foreach($airlines as $airline)
                            <option value="{{ $airline->id }}" {{ old('airline_id', $flight->airline_id) == $airline->id ? 'selected' : '' }}>
                                {{ $airline->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('airline_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Aéroport de départ -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aéroport de départ <span class="text-red-500">*</span></label>
                    <select name="departure_airport_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Sélectionner</option>
                        @foreach($airports as $airport)
                            <option value="{{ $airport->id }}" {{ old('departure_airport_id', $flight->departure_airport_id) == $airport->id ? 'selected' : '' }}>
                                {{ $airport->city }} ({{ $airport->iata_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('departure_airport_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Aéroport d'arrivée -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aéroport d'arrivée <span class="text-red-500">*</span></label>
                    <select name="arrival_airport_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Sélectionner</option>
                        @foreach($airports as $airport)
                            <option value="{{ $airport->id }}" {{ old('arrival_airport_id', $flight->arrival_airport_id) == $airport->id ? 'selected' : '' }}>
                                {{ $airport->city }} ({{ $airport->iata_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('arrival_airport_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de départ -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de départ <span class="text-red-500">*</span></label>
                    <input type="date" name="departure_date" value="{{ old('departure_date', $flight->departure_date) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('departure_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Heure de départ -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Heure de départ <span class="text-red-500">*</span></label>
                    <input type="time" name="departure_time" value="{{ old('departure_time', substr($flight->departure_time, 0, 5)) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('departure_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date d'arrivée -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date d'arrivée <span class="text-red-500">*</span></label>
                    <input type="date" name="arrival_date" value="{{ old('arrival_date', $flight->arrival_date) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('arrival_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Heure d'arrivée -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Heure d'arrivée <span class="text-red-500">*</span></label>
                    <input type="time" name="arrival_time" value="{{ old('arrival_time', substr($flight->arrival_time, 0, 5)) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('arrival_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Durée (minutes) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durée (minutes) <span class="text-red-500">*</span></label>
                    <input type="number" name="duration" value="{{ old('duration', $flight->duration) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('duration')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type d'avion -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type d'avion</label>
                    <input type="text" name="aircraft_type" value="{{ old('aircraft_type', $flight->aircraft_type) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('aircraft_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sièges Economy -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sièges Economy <span class="text-red-500">*</span></label>
                    <input type="number" name="economy_seats" value="{{ old('economy_seats', $flight->economy_seats) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('economy_seats')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prix Economy -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix Economy (XOF) <span class="text-red-500">*</span></label>
                    <input type="number" name="economy_price" value="{{ old('economy_price', $flight->economy_price) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('economy_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sièges Business -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sièges Business</label>
                    <input type="number" name="business_seats" value="{{ old('business_seats', $flight->business_seats) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('business_seats')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prix Business -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix Business (XOF)</label>
                    <input type="number" name="business_price" value="{{ old('business_price', $flight->business_price) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('business_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sièges First Class -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sièges First Class</label>
                    <input type="number" name="first_class_seats" value="{{ old('first_class_seats', $flight->first_class_seats) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('first_class_seats')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prix First Class -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix First Class (XOF)</label>
                    <input type="number" name="first_class_price" value="{{ old('first_class_price', $flight->first_class_price) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('first_class_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.flights.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Preview image before upload
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

// Remove image
function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('preview').src = '';
}
</script>
@endpush
@endsection
