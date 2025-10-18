@extends('admin.layouts.app')

@section('title', 'Détails de la Réservation')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.bookings.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Réservation #{{ $booking->booking_number }}</h1>
            </div>
            <p class="text-gray-600">Créée le {{ $booking->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                <i class="fas fa-print"></i>
                Imprimer
            </button>
            <button onclick="sendEmail()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                <i class="fas fa-envelope"></i>
                Envoyer Email
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations de la réservation -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-purple-600"></i>
                    Informations de la Réservation
                </h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Type de réservation</p>
                        <p class="font-semibold text-gray-800 mt-1">
                            @if($booking->booking_type === 'flight')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                    <i class="fas fa-plane mr-1"></i> Vol
                                </span>
                            @elseif($booking->booking_type === 'event')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                                    <i class="fas fa-calendar mr-1"></i> Événement
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                    <i class="fas fa-suitcase mr-1"></i> Package
                                </span>
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Date de voyage</p>
                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $booking->travel_date ? $booking->travel_date->format('d/m/Y') : 'N/A' }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Nombre de passagers</p>
                        <p class="font-semibold text-gray-800 mt-1">
                            <i class="fas fa-users text-gray-500 mr-1"></i>
                            {{ $booking->number_of_passengers }} {{ $booking->number_of_passengers > 1 ? 'personnes' : 'personne' }}
                        </p>
                    </div>
                    
                    @if($booking->seat_class)
                    <div>
                        <p class="text-sm text-gray-600">Classe</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ ucfirst($booking->seat_class) }}</p>
                    </div>
                    @endif
                    
                    @if($booking->seat_numbers)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Sièges</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $booking->seat_numbers }}</p>
                    </div>
                    @endif
                </div>

                @if($booking->special_requests)
                <div class="mt-4 pt-4 border-t">
                    <p class="text-sm text-gray-600">Demandes spéciales</p>
                    <p class="text-gray-800 mt-1">{{ $booking->special_requests }}</p>
                </div>
                @endif
            </div>

            <!-- Détails du produit -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    @if($booking->booking_type === 'flight')
                        <i class="fas fa-plane text-blue-600"></i>
                        Détails du Vol
                    @elseif($booking->booking_type === 'event')
                        <i class="fas fa-calendar text-purple-600"></i>
                        Détails de l'Événement
                    @else
                        <i class="fas fa-suitcase text-green-600"></i>
                        Détails du Package
                    @endif
                </h2>

                @if($booking->flight)
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600">Départ</p>
                                <p class="font-semibold text-gray-800">{{ $booking->flight->departure_airport->city ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->flight->departure_time ?? 'N/A' }}</p>
                            </div>
                            <i class="fas fa-arrow-right text-2xl text-gray-400"></i>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Arrivée</p>
                                <p class="font-semibold text-gray-800">{{ $booking->flight->arrival_airport->city ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->flight->arrival_time ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Compagnie</p>
                                <p class="font-semibold text-gray-800">{{ $booking->flight->airline->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">N° de vol</p>
                                <p class="font-semibold text-gray-800">{{ $booking->flight->flight_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                @elseif($booking->event)
                    <div class="space-y-3">
                        <h3 class="font-semibold text-lg text-gray-800">{{ $booking->event->title_fr ?? 'N/A' }}</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Lieu</p>
                                <p class="font-semibold text-gray-800">{{ $booking->event->venue_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Date</p>
                                <p class="font-semibold text-gray-800">{{ $booking->event->event_date ? $booking->event->event_date->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                @elseif($booking->package)
                    <div class="space-y-3">
                        <h3 class="font-semibold text-lg text-gray-800">{{ $booking->package->title_fr ?? 'N/A' }}</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Destination</p>
                                <p class="font-semibold text-gray-800">{{ $booking->package->destination ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Durée</p>
                                <p class="font-semibold text-gray-800">{{ $booking->package->duration ?? 'N/A' }} jours</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Informations des passagers -->
            @if($booking->passenger_details)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-users text-purple-600"></i>
                    Informations des Passagers
                </h2>
                
                @php
                    $passengers = is_string($booking->passenger_details) ? json_decode($booking->passenger_details, true) : $booking->passenger_details;
                @endphp
                
                @if(is_array($passengers))
                    @foreach($passengers as $index => $passenger)
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-2">Passager {{ $index + 1 }}</h3>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-gray-600">Nom:</span>
                                <span class="font-medium text-gray-800 ml-2">{{ $passenger['first_name'] ?? '' }} {{ $passenger['last_name'] ?? '' }}</span>
                            </div>
                            @if(isset($passenger['passport']))
                            <div>
                                <span class="text-gray-600">Passeport:</span>
                                <span class="font-medium text-gray-800 ml-2">{{ $passenger['passport'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="space-y-6">
            <!-- Client -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-purple-600"></i>
                    Client
                </h2>
                
                <div class="flex items-center mb-4">
                    <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
                        <span class="text-purple-600 font-semibold text-lg">
                            {{ substr($booking->user->first_name, 0, 1) }}{{ substr($booking->user->last_name, 0, 1) }}
                        </span>
                    </div>
                    <div class="ml-3">
                        <p class="font-semibold text-gray-800">{{ $booking->user->first_name }} {{ $booking->user->last_name }}</p>
                        <p class="text-sm text-gray-500">{{ $booking->user->email }}</p>
                    </div>
                </div>
                
                <div class="space-y-2 text-sm">
                    @if($booking->user->phone)
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-phone w-5"></i>
                        <span>{{ $booking->user->phone }}</span>
                    </div>
                    @endif
                    @if($booking->user->city)
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-map-marker-alt w-5"></i>
                        <span>{{ $booking->user->city }}, {{ $booking->user->country }}</span>
                    </div>
                    @endif
                </div>
                
                <a href="{{ route('admin.users.show', $booking->user->id) }}" class="mt-4 block text-center bg-purple-100 text-purple-700 px-4 py-2 rounded-lg hover:bg-purple-200 transition-colors">
                    Voir le profil
                </a>
            </div>

            <!-- Statut -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-purple-600"></i>
                    Statut
                </h2>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Statut de la réservation</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $booking->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Statut du paiement</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $booking->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $booking->payment_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </div>
                </div>
                
                @if($booking->status !== 'cancelled')
                <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="mt-4" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-ban mr-2"></i>Annuler la réservation
                    </button>
                </form>
                @endif
            </div>

            <!-- Montants -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-money-bill-wave text-purple-600"></i>
                    Montants
                </h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Montant total</span>
                        <span class="font-semibold text-gray-800">{{ number_format($booking->total_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                    </div>
                    
                    @if($booking->discount_amount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Réduction</span>
                        <span class="font-semibold">-{{ number_format($booking->discount_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                    </div>
                    @endif
                    
                    @if($booking->tax_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Taxes</span>
                        <span class="font-semibold text-gray-800">{{ number_format($booking->tax_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                    </div>
                    @endif
                    
                    <div class="pt-3 border-t flex justify-between">
                        <span class="font-bold text-gray-800">Montant final</span>
                        <span class="font-bold text-purple-600 text-lg">{{ number_format($booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function sendEmail() {
    if(confirm('Envoyer un email de confirmation au client ?')) {
        fetch(`/admin/bookings/{{ $booking->id }}/send-email`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Email envoyé avec succès !');
            } else {
                alert('Erreur lors de l\'envoi de l\'email');
            }
        });
    }
}
</script>
@endsection
