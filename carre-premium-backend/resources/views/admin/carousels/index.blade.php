@extends('admin.layouts.app')

@section('title', 'Gestion des Carrousels')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Carrousels</h1>
            <p class="text-gray-600 mt-2">Gérez les bannières et carrousels de la page d'accueil</p>
        </div>
        <button onclick="openAddModal()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Ajouter un carrousel
        </button>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aperçu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ordre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Période</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($carousels as $carousel)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ $carousel->image ? asset('storage/' . $carousel->image) : 'https://via.placeholder.com/100x60' }}" 
                                 alt="{{ $carousel->title_fr }}" 
                                 class="h-16 w-24 object-cover rounded">
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $carousel->title_fr }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($carousel->subtitle_fr, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $carousel->link_url ? Str::limit($carousel->link_url, 30) : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $carousel->order_position }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($carousel->start_date && $carousel->end_date)
                                {{ $carousel->start_date->format('d/m/Y') }} - {{ $carousel->end_date->format('d/m/Y') }}
                            @else
                                Permanent
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($carousel->is_active)
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
                            <button onclick="editCarousel({{ $carousel->id }})" class="text-purple-600 hover:text-purple-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="toggleStatus({{ $carousel->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-toggle-on"></i>
                            </button>
                            <button onclick="deleteCarousel({{ $carousel->id }})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg">Aucun carrousel trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ajouter/Modifier -->
<div id="carouselModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-2xl font-bold text-gray-800">Ajouter un carrousel</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>
        <form id="carouselForm" action="{{ route('admin.carousels.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image *</label>
                    <input type="file" name="image" accept="image/*" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre (FR) *</label>
                    <input type="text" name="title_fr" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre (EN) *</label>
                    <input type="text" name="title_en" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sous-titre (FR)</label>
                    <input type="text" name="subtitle_fr" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sous-titre (EN)</label>
                    <input type="text" name="subtitle_en" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lien URL</label>
                    <input type="url" name="link_url" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ordre</label>
                    <input type="number" name="order_position" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
                    <input type="date" name="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
                    <input type="date" name="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                        <span class="text-sm font-medium text-gray-700">Actif</span>
                    </label>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    <i class="fas fa-save mr-2"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('carouselModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('carouselModal').classList.add('hidden');
}

function editCarousel(id) {
    window.location.href = `/admin/carousels/${id}/edit`;
}

function toggleStatus(id) {
    if(confirm('Changer le statut de ce carrousel ?')) {
        fetch(`/admin/carousels/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(() => location.reload());
    }
}

function deleteCarousel(id) {
    if(confirm('Supprimer ce carrousel ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/carousels/${id}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
