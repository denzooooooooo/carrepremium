@extends('admin.layouts.app')

@section('title', 'Détails Utilisateur')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <a href="{{ route('admin.users.index') }}" class="text-purple-600 hover:text-purple-800 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Détails de l'utilisateur</h1>
        </div>
        <div class="flex gap-3">
            <button onclick="window.location.href='{{ route('admin.users.edit', $user->id) }}'" 
                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg flex items-center gap-2">
                <i class="fas fa-edit"></i>
                Modifier
            </button>
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" 
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg flex items-center gap-2">
                    <i class="fas fa-trash"></i>
                    Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center mb-6">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->first_name }}" 
                             class="w-32 h-32 rounded-full mx-auto object-cover mb-4">
                    @else
                        <div class="w-32 h-32 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-4">
                            <span class="text-purple-600 text-4xl font-bold">
                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                    <h2 class="text-2xl font-bold text-gray-800">{{ $user->first_name }} {{ $user->last_name }}</h2>
                    <p class="text-gray-500">ID: #{{ $user->id }}</p>
                    
                    <div class="mt-4">
                        @if($user->is_active)
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-2"></i>Compte Actif
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-2"></i>Compte Inactif
                            </span>
                        @endif
                    </div>
                </div>

                <div class="border-t pt-6 space-y-4">
                    <div>
                        <label class="text-sm text-gray-500">Email</label>
                        <p class="text-gray-800 font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Téléphone</label>
                        <p class="text-gray-800 font-medium">{{ $user->phone ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Pays</label>
                        <p class="text-gray-800 font-medium">{{ $user->country }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Ville</label>
                        <p class="text-gray-800 font-medium">{{ $user->city ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Adresse</label>
                        <p class="text-gray-800 font-medium">{{ $user->address ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Points de fidélité</label>
                        <p class="text-gray-800 font-medium">
                            <i class="fas fa-star text-yellow-500 mr-1"></i>
                            {{ $user->loyalty_points }} points
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Membre depuis</label>
                        <p class="text-gray-800 font-medium">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques et activités -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm">Réservations</p>
                            <p class="text-3xl font-bold mt-2">{{ $bookings->count() }}</p>
                        </div>
                        <i class="fas fa-ticket-alt text-4xl text-blue-200"></i>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm">Confirmées</p>
                            <p class="text-3xl font-bold mt-2">{{ $bookings->where('status', 'confirmed')->count() }}</p>
                        </div>
                        <i class="fas fa-check-circle text-4xl text-green-200"></i>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm">Avis</p>
                            <p class="text-3xl font-bold mt-2">{{ $reviews->count() }}</p>
                        </div>
                        <i class="fas fa-star text-4xl text-purple-200"></i>
                    </div>
                </div>
            </div>

            <!-- Réservations récentes -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">Réservations récentes</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Réservation</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $booking->booking_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                        {{ $booking->booking_type === 'flight' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $booking->booking_type === 'event' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $booking->booking_type === 'package' ? 'bg-purple-100 text-purple-800' : '' }}">
                                        {{ ucfirst($booking->booking_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $booking->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ number_format($booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-purple-600 hover:text-purple-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    Aucune réservation pour cet utilisateur
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Avis récents -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">Avis récents</h3>
                </div>
                <div class="p-6">
                    @forelse($reviews as $review)
                    <div class="mb-4 pb-4 border-b border-gray-200 last:border-0">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">{{ $review->rating }}/5</span>
                            </div>
                            <span class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</span>
                        </div>
                        @if($review->title)
                        <h4 class="font-semibold text-gray-800 mb-1">{{ $review->title }}</h4>
                        @endif
                        <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-4">Aucun avis pour cet utilisateur</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
