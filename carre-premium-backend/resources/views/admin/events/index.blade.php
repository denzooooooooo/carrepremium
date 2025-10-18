@extends('admin.layouts.app')

@section('title', 'Gestion des Événements')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Événements</h1>
            <p class="text-gray-600 mt-2">Gérez tous les événements sportifs et culturels</p>
        </div>
        <button onclick="window.location.href='{{ route('admin.events.create') }}'" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Ajouter un événement
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Total Événements</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <i class="fas fa-calendar-alt text-4xl text-purple-200"></i>
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
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">À venir</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['upcoming'] ?? 0 }}</p>
                </div>
                <i class="fas fa-clock text-4xl text-blue-200"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">En vedette</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['featured'] ?? 0 }}</p>
                </div>
                <i class="fas fa-star text-4xl text-yellow-200"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Événement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lieu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($events as $event)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $event->title_fr }}</div>
                            <div class="text-sm text-gray-500">{{ $event->category->name_fr ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($event->event_type === 'sport')
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-trophy"></i> Sport
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-music"></i> Culturel
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $event->venue_name }}</div>
                            <div class="text-sm text-gray-500">{{ $event->city }}, {{ $event->country }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $event->event_date }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ number_format($event->min_price ?? 0) }} XOF</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($event->is_active)
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle"></i> Actif
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle"></i> Inactif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="window.location.href='{{ route('admin.events.edit', $event->id) }}'" class="text-purple-600 hover:text-purple-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteItem({{ $event->id }})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="fas fa-calendar-alt text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg">Aucun événement trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($events->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t">
            {{ $events->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function deleteItem(id) {
    if(confirm('Supprimer cet événement ?')) {
        alert('Fonctionnalité en cours de développement');
    }
}
</script>
@endsection
