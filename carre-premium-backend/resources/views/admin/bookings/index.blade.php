@extends('admin.layouts.app')

@section('title', 'Gestion des Réservations')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Réservations</h1>
            <p class="text-gray-600 mt-2">Gérez toutes les réservations de la plateforme</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.bookings.index', array_merge(request()->all(), ['export' => 'excel'])) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                <i class="fas fa-file-excel"></i>
                Exporter Excel
            </a>
            <button onclick="openCreateModal()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                <i class="fas fa-plus"></i>
                Nouvelle Réservation
            </button>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Réservations</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <i class="fas fa-ticket-alt text-4xl text-blue-200"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">En Attente</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['pending'] ?? 0 }}</p>
                </div>
                <i class="fas fa-clock text-4xl text-yellow-200"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Confirmées</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['confirmed'] ?? 0 }}</p>
                </div>
                <i class="fas fa-check-circle text-4xl text-green-200"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Revenu Total</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($stats['revenue'] ?? 0) }} XOF</p>
                </div>
                <i class="fas fa-money-bill-wave text-4xl text-purple-200"></i>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="N° réservation, email..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Tous</option>
                    <option value="flight" {{ request('type') == 'flight' ? 'selected' : '' }}>Vols</option>
                    <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>Événements</option>
                    <option value="package" {{ request('type') == 'package' ? 'selected' : '' }}>Packages</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Tous</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complétée</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Paiement</label>
                <select name="payment_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Tous</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Payé</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
                <a href="{{ route('admin.bookings.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition-colors text-center">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table des réservations -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Réservation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Voyage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Passagers</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paiement</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->booking_number }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                        <span class="text-purple-600 font-semibold text-xs">
                                            {{ substr($booking->user->first_name, 0, 1) }}{{ substr($booking->user->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $booking->user->first_name }} {{ $booking->user->last_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($booking->booking_type === 'flight')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-plane mr-1"></i> Vol
                                </span>
                            @elseif($booking->booking_type === 'event')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-calendar mr-1"></i> Événement
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-suitcase mr-1"></i> Package
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $booking->travel_date ? $booking->travel_date->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-users mr-1"></i> {{ $booking->number_of_passengers }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ number_format($booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</div>
                            @if($booking->discount_amount > 0)
                                <div class="text-xs text-green-600">-{{ number_format($booking->discount_amount, 0, ',', ' ') }} {{ $booking->currency }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="changeStatus({{ $booking->id }}, '{{ $booking->status }}')" 
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium hover:opacity-80 transition-opacity
                                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $booking->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                <i class="fas {{ $booking->status === 'confirmed' ? 'fa-check-circle' : ($booking->status === 'pending' ? 'fa-clock' : ($booking->status === 'cancelled' ? 'fa-times-circle' : 'fa-check')) }} mr-1"></i>
                                {{ ucfirst($booking->status) }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $booking->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $booking->payment_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                <i class="fas {{ $booking->payment_status === 'paid' ? 'fa-check' : ($booking->payment_status === 'pending' ? 'fa-clock' : 'fa-times') }} mr-1"></i>
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button onclick="printBooking({{ $booking->id }})" class="text-purple-600 hover:text-purple-900 mr-3" title="Imprimer">
                                <i class="fas fa-print"></i>
                            </button>
                            <button onclick="sendEmail({{ $booking->id }})" class="text-green-600 hover:text-green-900 mr-3" title="Envoyer email">
                                <i class="fas fa-envelope"></i>
                            </button>
                            @if($booking->status !== 'cancelled')
                            <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Annuler cette réservation ?')">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Annuler">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-ticket-alt text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 text-lg">Aucune réservation trouvée</p>
                                <p class="text-gray-400 text-sm mt-2">Les réservations apparaîtront ici</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Changer Statut -->
<div id="statusModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Changer le statut</h3>
        <form id="statusForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau statut</label>
                <select name="status" id="newStatus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="pending">En attente</option>
                    <option value="confirmed">Confirmée</option>
                    <option value="completed">Complétée</option>
                    <option value="cancelled">Annulée</option>
                </select>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeStatusModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
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
function changeStatus(id, currentStatus) {
    document.getElementById('statusModal').classList.remove('hidden');
    document.getElementById('statusForm').action = `/admin/bookings/${id}`;
    document.getElementById('newStatus').value = currentStatus;
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

function printBooking(id) {
    window.open(`/admin/bookings/${id}/print`, '_blank');
}

function sendEmail(id) {
    if(confirm('Envoyer un email de confirmation au client ?')) {
        fetch(`/admin/bookings/${id}/send-email`, {
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

function openCreateModal() {
    alert('Fonctionnalité de création manuelle en cours de développement.\nLes réservations sont normalement créées par les clients sur le site.');
}

// Fermer le modal en cliquant en dehors
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStatusModal();
    }
});
</script>
@endsection
