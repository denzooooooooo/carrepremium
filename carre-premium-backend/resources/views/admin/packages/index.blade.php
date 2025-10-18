@extends('admin.layouts.app')

@section('title', 'Gestion des Packages')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Packages Touristiques</h1>
            <p class="text-gray-600 mt-2">Gérez tous les packages touristiques</p>
        </div>
        <button onclick="window.location.href='{{ route('admin.packages.create') }}'" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Ajouter un package
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Total Packages</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <i class="fas fa-suitcase text-4xl text-green-200"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Actifs</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <i class="fas fa-check-circle text-4xl text-blue-200"></i>
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
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Prix Moyen</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($stats['avg_price'] ?? 0) }}</p>
                </div>
                <i class="fas fa-money-bill-wave text-4xl text-purple-200"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destination</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($packages as $package)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $package->title_fr }}</div>
                            <div class="text-sm text-gray-500">{{ $package->category->name_fr ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($package->package_type === 'helicopter')
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-helicopter"></i> Hélicoptère
                                </span>
                            @elseif($package->package_type === 'private_jet')
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-plane"></i> Jet Privé
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-suitcase"></i> {{ ucfirst($package->package_type) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $package->destination }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $package->duration }} jours
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ number_format($package->price) }} XOF</div>
                            @if($package->discount_price)
                                <div class="text-xs text-green-600">-{{ number_format($package->discount_price) }} XOF</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($package->is_active)
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
                            <button onclick="window.location.href='{{ route('admin.packages.edit', $package->id) }}'" class="text-purple-600 hover:text-purple-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteItem({{ $package->id }})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="fas fa-suitcase text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg">Aucun package trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($packages->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t">
            {{ $packages->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function deleteItem(id) {
    if(confirm('Supprimer ce package ?')) {
        alert('Fonctionnalité en cours de développement');
    }
}
</script>
@endsection
