@extends('admin.layouts.app')

@section('title', 'Gestion des Vols')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Vols</h1>
            <p class="text-gray-600 mt-2">Gérez tous les vols disponibles sur la plateforme</p>
        </div>
        <button onclick="openCreateModal()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
            <i class="fas fa-plus"></i>
            Ajouter un vol
        </button>
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Vols</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <i class="fas fa-plane text-4xl text-blue-200"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Actifs</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <i class="fas fa-check-circle text-4xl text-green-200"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Aujourd'hui</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['today'] ?? 0 }}</p>
                </div>
                <i class="fas fa-calendar-day text-4xl text-yellow-200"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Compagnies</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['airlines'] ?? 0 }}</p>
                </div>
                <i class="fas fa-building text-4xl text-purple-200"></i>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.flights.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="N° vol, compagnie..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Compagnie</label>
                <select name="airline" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Toutes</option>
                    @foreach($airlines as $airline)
                        <option value="{{ $airline->id }}" {{ request('airline') == $airline->id ? 'selected' : '' }}>
                            {{ $airline->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Départ</label>
                <select name="departure" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Tous</option>
                    @foreach($airports as $airport)
                        <option value="{{ $airport->id }}" {{ request('departure') == $airport->id ? 'selected' : '' }}>
                            {{ $airport->city }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Tous</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Programmé</option>
                    <option value="delayed" {{ request('status') == 'delayed' ? 'selected' : '' }}>Retardé</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complété</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
                <a href="{{ route('admin.flights.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition-colors text-center">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table des vols -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vol</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Itinéraire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Heure</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix Economy</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Disponibilité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($flights as $flight)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-plane text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $flight->flight_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $flight->airline->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center text-sm">
                                <div class="text-center">
                                    <div class="font-medium text-gray-900">{{ $flight->departureAirport->iata_code ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $flight->departureAirport->city ?? 'N/A' }}</div>
                                </div>
                                <div class="mx-3">
                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                </div>
                                <div class="text-center">
                                    <div class="font-medium text-gray-900">{{ $flight->arrivalAirport->iata_code ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $flight->arrivalAirport->city ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($flight->departure_date)->format('d/m/Y') }}</div>
                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ floor($flight->duration / 60) }}h{{ $flight->duration % 60 }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ number_format($flight->economy_price, 0, ',', ' ') }} XOF</div>
                            @if($flight->business_price)
                                <div class="text-xs text-gray-500">Business: {{ number_format($flight->business_price, 0, ',', ' ') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <div class="flex items-center">
                                    <span class="text-gray-900 font-medium">{{ $flight->available_economy }}</span>
                                    <span class="text-gray-500 mx-1">/</span>
                                    <span class="text-gray-500">{{ $flight->economy_seats }}</span>
                                </div>
                                @php
                                    $percentage = $flight->economy_seats > 0 ? ($flight->available_economy / $flight->economy_seats) * 100 : 0;
                                @endphp
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                    <div class="bg-{{ $percentage > 50 ? 'green' : ($percentage > 20 ? 'yellow' : 'red') }}-600 h-1.5 rounded-full" 
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="toggleStatus({{ $flight->id }}, {{ $flight->is_active ? 'true' : 'false' }})"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium hover:opacity-80 transition-opacity
                                    {{ $flight->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-{{ $flight->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                {{ $flight->is_active ? 'Actif' : 'Inactif' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="editFlight({{ $flight->id }})" class="text-blue-600 hover:text-blue-900 mr-3" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="viewFlight({{ $flight->id }})" class="text-purple-600 hover:text-purple-900 mr-3" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </button>
                            <form action="{{ route('admin.flights.destroy', $flight->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce vol ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-plane text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 text-lg">Aucun vol trouvé</p>
                                <p class="text-gray-400 text-sm mt-2">Les vols apparaîtront ici</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($flights->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $flights->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Créer/Modifier Vol -->
<div id="flightModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4" id="modalTitle">Ajouter un vol</h3>
        <form id="flightForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- N° de vol -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">N° de vol <span class="text-red-500">*</span></label>
                    <input type="text" name="flight_number" id="flight_number" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Compagnie -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Compagnie <span class="text-red-500">*</span></label>
                    <select name="airline_id" id="airline_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Sélectionner</option>
                        @foreach($airlines as $airline)
                            <option value="{{ $airline->id }}">{{ $airline->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Aéroport de départ -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aéroport de départ <span class="text-red-500">*</span></label>
                    <select name="departure_airport_id" id="departure_airport_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Sélectionner</option>
                        @foreach($airports as $airport)
                            <option value="{{ $airport->id }}">{{ $airport->city }} ({{ $airport->iata_code }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Aéroport d'arrivée -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aéroport d'arrivée <span class="text-red-500">*</span></label>
                    <select name="arrival_airport_id" id="arrival_airport_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Sélectionner</option>
                        @foreach($airports as $airport)
                            <option value="{{ $airport->id }}">{{ $airport->city }} ({{ $airport->iata_code }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date de départ -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de départ <span class="text-red-500">*</span></label>
                    <input type="date" name="departure_date" id="departure_date" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Heure de départ -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Heure de départ <span class="text-red-500">*</span></label>
                    <input type="time" name="departure_time" id="departure_time" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Date d'arrivée -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date d'arrivée <span class="text-red-500">*</span></label>
                    <input type="date" name="arrival_date" id="arrival_date" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Heure d'arrivée -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Heure d'arrivée <span class="text-red-500">*</span></label>
                    <input type="time" name="arrival_time" id="arrival_time" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Durée (minutes) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durée (minutes) <span class="text-red-500">*</span></label>
                    <input type="number" name="duration" id="duration" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Type d'avion -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type d'avion</label>
                    <input type="text" name="aircraft_type" id="aircraft_type"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Sièges Economy -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sièges Economy <span class="text-red-500">*</span></label>
                    <input type="number" name="economy_seats" id="economy_seats" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Prix Economy -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix Economy (XOF) <span class="text-red-500">*</span></label>
                    <input type="number" name="economy_price" id="economy_price" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Sièges Business -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sièges Business</label>
                    <input type="number" name="business_seats" id="business_seats"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Prix Business -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix Business (XOF)</label>
                    <input type="number" name="business_price" id="business_price"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Sièges First Class -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sièges First Class</label>
                    <input type="number" name="first_class_seats" id="first_class_seats"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Prix First Class -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix First Class (XOF)</label>
                    <input type="number" name="first_class_price" id="first_class_price"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Ajouter un vol';
    document.getElementById('flightForm').action = '{{ route("admin.flights.store") }}';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('flightForm').reset();
    document.getElementById('flightModal').classList.remove('hidden');
}

function editFlight(id) {
    document.getElementById('modalTitle').textContent = 'Modifier le vol';
    document.getElementById('flightForm').action = `/admin/flights/${id}`;
    document.getElementById('formMethod').value = 'PUT';
    
    // TODO: Charger les données du vol via AJAX
    fetch(`/admin/flights/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            // Remplir le formulaire avec les données
            Object.keys(data).forEach(key => {
                const input = document.getElementById(key);
                if (input) input.value = data[key];
            });
            document.getElementById('flightModal').classList.remove('hidden');
        });
}

function viewFlight(id) {
    window.location.href = `/admin/flights/${id}`;
}

function toggleStatus(id, currentStatus) {
    if(confirm(`${currentStatus ? 'Désactiver' : 'Activer'} ce vol ?`)) {
        fetch(`/admin/flights/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            }
        });
    }
}

function closeModal() {
    document.getElementById('flightModal').classList.add('hidden');
}

// Fermer le modal en cliquant en dehors
document.getElementById('flightModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection
