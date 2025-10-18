@extends('admin.layouts.app')

@section('title', 'Créer un Vol')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plane-departure text-primary me-2"></i>
                Créer un Nouveau Vol
            </h1>
            <p class="text-muted mb-0">Ajoutez un nouveau vol au système</p>
        </div>
        <a href="{{ route('admin.flights.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <!-- Form Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.flights.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Image Upload Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="fas fa-image text-primary me-2"></i>
                                    Image du Vol
                                </h5>
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image (Optionnel)</label>
                                    <input type="file" 
                                           class="form-control @error('image') is-invalid @enderror" 
                                           id="image" 
                                           name="image" 
                                           accept="image/*"
                                           onchange="previewImage(event)">
                                    <small class="form-text text-muted">
                                        Formats acceptés: JPEG, PNG, GIF, WebP. Taille max: 5 Mo
                                    </small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Image Preview -->
                                <div id="imagePreview" class="mt-3" style="display: none;">
                                    <label class="form-label">Aperçu:</label>
                                    <div class="position-relative d-inline-block">
                                        <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px; max-height: 200px;">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeImage()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Flight Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Informations du Vol
                        </h5>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="flight_number" class="form-label">Numéro de Vol <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('flight_number') is-invalid @enderror" 
                               id="flight_number" 
                               name="flight_number" 
                               value="{{ old('flight_number') }}" 
                               placeholder="Ex: AF1234"
                               required>
                        @error('flight_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="airline_id" class="form-label">Compagnie Aérienne <span class="text-danger">*</span></label>
                        <select class="form-select @error('airline_id') is-invalid @enderror" 
                                id="airline_id" 
                                name="airline_id" 
                                required>
                            <option value="">Sélectionner une compagnie</option>
                            @foreach($airlines as $airline)
                                <option value="{{ $airline->id }}" {{ old('airline_id') == $airline->id ? 'selected' : '' }}>
                                    {{ $airline->name }} ({{ $airline->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('airline_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="aircraft_type" class="form-label">Type d'Appareil</label>
                        <input type="text" 
                               class="form-control @error('aircraft_type') is-invalid @enderror" 
                               id="aircraft_type" 
                               name="aircraft_type" 
                               value="{{ old('aircraft_type') }}" 
                               placeholder="Ex: Boeing 777">
                        @error('aircraft_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Route Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fas fa-route text-primary me-2"></i>
                            Itinéraire
                        </h5>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="departure_airport_id" class="form-label">Aéroport de Départ <span class="text-danger">*</span></label>
                        <select class="form-select @error('departure_airport_id') is-invalid @enderror" 
                                id="departure_airport_id" 
                                name="departure_airport_id" 
                                required>
                            <option value="">Sélectionner un aéroport</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}" {{ old('departure_airport_id') == $airport->id ? 'selected' : '' }}>
                                    {{ $airport->city }} - {{ $airport->name }} ({{ $airport->iata_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('departure_airport_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="arrival_airport_id" class="form-label">Aéroport d'Arrivée <span class="text-danger">*</span></label>
                        <select class="form-select @error('arrival_airport_id') is-invalid @enderror" 
                                id="arrival_airport_id" 
                                name="arrival_airport_id" 
                                required>
                            <option value="">Sélectionner un aéroport</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}" {{ old('arrival_airport_id') == $airport->id ? 'selected' : '' }}>
                                    {{ $airport->city }} - {{ $airport->name }} ({{ $airport->iata_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('arrival_airport_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Schedule Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fas fa-clock text-primary me-2"></i>
                            Horaires
                        </h5>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="departure_date" class="form-label">Date de Départ <span class="text-danger">*</span></label>
                        <input type="date" 
                               class="form-control @error('departure_date') is-invalid @enderror" 
                               id="departure_date" 
                               name="departure_date" 
                               value="{{ old('departure_date') }}" 
                               required>
                        @error('departure_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="departure_time" class="form-label">Heure de Départ <span class="text-danger">*</span></label>
                        <input type="time" 
                               class="form-control @error('departure_time') is-invalid @enderror" 
                               id="departure_time" 
                               name="departure_time" 
                               value="{{ old('departure_time') }}" 
                               required>
                        @error('departure_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="arrival_date" class="form-label">Date d'Arrivée <span class="text-danger">*</span></label>
                        <input type="date" 
                               class="form-control @error('arrival_date') is-invalid @enderror" 
                               id="arrival_date" 
                               name="arrival_date" 
                               value="{{ old('arrival_date') }}" 
                               required>
                        @error('arrival_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="arrival_time" class="form-label">Heure d'Arrivée <span class="text-danger">*</span></label>
                        <input type="time" 
                               class="form-control @error('arrival_time') is-invalid @enderror" 
                               id="arrival_time" 
                               name="arrival_time" 
                               value="{{ old('arrival_time') }}" 
                               required>
                        @error('arrival_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="duration" class="form-label">Durée (en minutes) <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('duration') is-invalid @enderror" 
                               id="duration" 
                               name="duration" 
                               value="{{ old('duration') }}" 
                               placeholder="Ex: 360 (pour 6h)"
                               min="1"
                               required>
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Seats and Pricing - Economy -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fas fa-chair text-primary me-2"></i>
                            Classe Économique
                        </h5>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="economy_seats" class="form-label">Nombre de Sièges <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('economy_seats') is-invalid @enderror" 
                               id="economy_seats" 
                               name="economy_seats" 
                               value="{{ old('economy_seats') }}" 
                               min="0"
                               required>
                        @error('economy_seats')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="economy_price" class="form-label">Prix (XOF) <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('economy_price') is-invalid @enderror" 
                               id="economy_price" 
                               name="economy_price" 
                               value="{{ old('economy_price') }}" 
                               step="0.01"
                               min="0"
                               required>
                        @error('economy_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Seats and Pricing - Business -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fas fa-briefcase text-warning me-2"></i>
                            Classe Affaires (Optionnel)
                        </h5>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="business_seats" class="form-label">Nombre de Sièges</label>
                        <input type="number" 
                               class="form-control @error('business_seats') is-invalid @enderror" 
                               id="business_seats" 
                               name="business_seats" 
                               value="{{ old('business_seats', 0) }}" 
                               min="0">
                        @error('business_seats')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="business_price" class="form-label">Prix (XOF)</label>
                        <input type="number" 
                               class="form-control @error('business_price') is-invalid @enderror" 
                               id="business_price" 
                               name="business_price" 
                               value="{{ old('business_price', 0) }}" 
                               step="0.01"
                               min="0">
                        @error('business_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Seats and Pricing - First Class -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fas fa-crown text-warning me-2"></i>
                            Première Classe (Optionnel)
                        </h5>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="first_class_seats" class="form-label">Nombre de Sièges</label>
                        <input type="number" 
                               class="form-control @error('first_class_seats') is-invalid @enderror" 
                               id="first_class_seats" 
                               name="first_class_seats" 
                               value="{{ old('first_class_seats', 0) }}" 
                               min="0">
                        @error('first_class_seats')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="first_class_price" class="form-label">Prix (XOF)</label>
                        <input type="number" 
                               class="form-control @error('first_class_price') is-invalid @enderror" 
                               id="first_class_price" 
                               name="first_class_price" 
                               value="{{ old('first_class_price', 0) }}" 
                               step="0.01"
                               min="0">
                        @error('first_class_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row">
                    <div class="col-12">
                        <hr class="my-4">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.flights.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Créer le Vol
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
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
