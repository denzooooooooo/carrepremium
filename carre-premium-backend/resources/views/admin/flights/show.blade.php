@extends('admin.layouts.app')

@section('title', 'Détails du Vol')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Détails du Vol</h1>
            <p class="text-gray-600 mt-2">{{ $flight->flight_number }}</p>
        </div>
        <a href="{{ route('admin.flights.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations du vol -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-plane text-purple-600 mr-2"></i>
                    Informations du Vol
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">N° de vol</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $flight->flight_number }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Compagnie</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $flight->airline->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Type d'avion</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $flight->aircraft_type ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Durée</label>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ floor($flight->duration / 60) }}h{{ $flight->duration % 60 }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Itinéraire -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-route text-purple-600 mr-2"></i>
                    Itinéraire
                </h2>
                
                <div class="flex items-center justify-between">
                    <!-- Départ -->
                    <div class="text-center flex-1">
                        <div class="text-3xl font-bold text-gray-800">{{ $flight->departureAirport->iata_code ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-600 mt-1">{{ $flight->departureAirport->city ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-500">{{ $flight->departureAirport->name ?? 'N/A' }}</div>
                        <div class="mt-3 text-lg font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($flight->departure_date)->format('d/m/Y') }}
                        </div>
                        <div class="text-purple-600 font-bold text-xl">
                            {{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i') }}
                        </div>
                    </div>

                    <!-- Flèche -->
                    <div class="flex-1 flex flex-col items-center px-4">
                        <i class="fas fa-plane text-4xl text-purple-600 mb-2"></i>
                        <div class="w-full h-1 bg-purple-200 relative">
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white px-2">
                                <span class="text-sm text-gray-600">{{ floor($flight->duration / 60) }}h{{ $flight->duration % 60 }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Arrivée -->
                    <div class="text-center flex-1">
                        <div class="text-3xl font-bold text-gray-800">{{ $flight->arrivalAirport->iata_code ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-600 mt-1">{{ $flight->arrivalAirport->city ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-500">{{ $flight->arrivalAirport->name ?? 'N/A' }}</div>
                        <div class="mt-3 text-lg font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($flight->arrival_date)->format('d/m/Y') }}
                        </div>
                        <div class="text-purple-600 font-bold text-xl">
                            {{ \Carbon\Carbon::parse($flight->arrival_time)->format('H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Classes et Prix -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chair text-purple-600 mr-2"></i>
                    Classes et Tarifs
                </h2>
                
                <div class="space-y-4">
                    <!-- Economy -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-semibold text-gray-800">Economy Class</h3>
                            <span class="text-2xl font-bold text-purple-600">{{ number_format($flight->economy_price, 0, ',', ' ') }} XOF</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Sièges disponibles: {{ $flight->available_economy }} / {{ $flight->economy_seats }}</span>
                            <span>{{ number_format(($flight->available_economy / $flight->economy_seats) * 100, 0) }}% disponible</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($flight->available_economy / $flight->economy_seats) * 100 }}%"></div>
                        </div>
                    </div>

                    <!-- Business -->
                    @if($flight->business_seats > 0)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-semibold text-gray-800">Business Class</h3>
                            <span class="text-2xl font-bold text-purple-600">{{ number_format($flight->business_price, 0, ',', ' ') }} XOF</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Sièges disponibles: {{ $flight->available_business }} / {{ $flight->business_seats }}</span>
                            <span>{{ number_format(($flight->available_business / $flight->business_seats) * 100, 0) }}% disponible</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($flight->available_business / $flight->business_seats) * 100 }}%"></div>
                        </div>
                    </div>
                    @endif

                    <!-- First Class -->
                    @if($flight->first_class_seats > 0)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-semibold text-gray-800">First Class</h3>
                            <span class="text-2xl font-bold text-purple-600">{{ number_format($flight->first_class_price, 0, ',', ' ') }} XOF</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Sièges disponibles: {{ $flight->available_first_class }} / {{ $flight->first_class_seats }}</span>
                            <span>{{ number_format(($flight->available_first_class / $flight->first_class_seats) * 100, 0) }}% disponible</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ ($flight->available_first_class / $flight->first_class_seats) * 100 }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Réservations -->
            @if($flight->bookings->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-ticket-alt text-purple-600 mr-2"></i>
                    Réservations ({{ $flight->bookings->count() }})
                </h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">N° Réservation</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Passagers</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($flight->bookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-sm">{{ $booking->booking_number }}</td>
                                <td class="px-4 py-2 text-sm">{{ $booking->user->first_name }} {{ $booking->user->last_name }}</td>
                                <td class="px-4 py-2 text-sm">{{ $booking->number_of_passengers }}</td>
                                <td class="px-4 py-2 text-sm font-semibold">{{ number_format($booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</td>
                                <td class="px-4 py-2 text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statut -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-gray-800 mb-4">Statut du Vol</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Statut</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            {{ $flight->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $flight->status === 'delayed' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $flight->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $flight->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($flight->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Actif</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $flight->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $flight->is_active ? 'Oui' : 'Non' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-gray-800 mb-4">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.flights.edit', $flight->id) }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    <form action="{{ route('admin.flights.destroy', $flight->id) }}" method="POST" onsubmit="return confirm('Supprimer ce vol ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <!-- Informations système -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-gray-800 mb-4">Informations</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-gray-500">Créé le:</span>
                        <p class="font-medium">{{ $flight->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Modifié le:</span>
                        <p class="font-medium">{{ $flight->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
