@extends('admin.layouts.app')

@section('title', 'Règles de Prix')
@section('page-title', 'Gestion des Règles de Prix')

@section('content')
<div class="space-y-6">
    <!-- Header avec bouton d'ajout -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 font-montserrat">Règles de Pricing</h2>
            <p class="text-gray-600 mt-1">Gérez les marges appliquées sur vos produits</p>
        </div>
        <button onclick="openModal('add')" class="flex items-center px-6 py-3 bg-gradient-to-r from-primary to-purple-600 text-white rounded-lg hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i>
            Nouvelle Règle
        </button>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de Produit</label>
                <select id="filterType" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    <option value="">Tous</option>
                    <option value="flight">Vols</option>
                    <option value="event">Événements</option>
                    <option value="package">Packages</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select id="filterStatus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    <option value="">Tous</option>
                    <option value="1">Actif</option>
                    <option value="0">Inactif</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de Marge</label>
                <select id="filterMarginType" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    <option value="">Tous</option>
                    <option value="percentage">Pourcentage</option>
                    <option value="fixed">Fixe</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="applyFilters()" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrer
                </button>
            </div>
        </div>
    </div>

    <!-- Tableau des règles -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-primary to-purple-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nom de la Règle</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Type Produit</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Catégorie</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Type Marge</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Valeur</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Priorité</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Statut</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody id="rulesTableBody" class="divide-y divide-gray-200">
                    @forelse($rules as $rule)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $rule->rule_name }}</div>
                            @if($rule->description)
                                <div class="text-sm text-gray-500">{{ Str::limit($rule->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($rule->product_type == 'flight') bg-blue-100 text-blue-800
                                @elseif($rule->product_type == 'event') bg-green-100 text-green-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                @if($rule->product_type == 'flight') ✈️ Vols
                                @elseif($rule->product_type == 'event') 🎫 Événements
                                @else 🎒 Packages
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $rule->category ?? 'Toutes' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                {{ $rule->margin_type == 'percentage' ? 'Pourcentage' : 'Fixe' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-lg font-bold text-purple-600">
                                {{ $rule->margin_type == 'percentage' ? $rule->margin_value . '%' : number_format($rule->margin_value, 0, ',', ' ') . ' XOF' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                {{ $rule->priority }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($rule->is_active)
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle"></i> Actif
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle"></i> Inactif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button onclick='openModal("edit", @json($rule))' class="text-blue-600 hover:text-blue-900" title="Modifier">
                                    <i class="fas fa-edit text-lg"></i>
                                </button>
                                <button onclick="toggleRule({{ $rule->id }})" class="text-yellow-600 hover:text-yellow-900" title="Toggle">
                                    <i class="fas fa-toggle-on text-lg"></i>
                                </button>
                                <button onclick="deleteRule({{ $rule->id }})" class="text-red-600 hover:text-red-900" title="Supprimer">
                                    <i class="fas fa-trash text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-percentage text-4xl mb-3 text-gray-300"></i>
                            <p>Aucune règle de pricing configurée</p>
                            <button onclick="openModal('add')" class="mt-4 text-primary hover:underline">
                                Créer votre première règle
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ajout/Modification -->
<div id="ruleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-primary to-purple-600 px-6 py-4 flex justify-between items-center">
            <h3 id="modalTitle" class="text-xl font-bold text-white font-montserrat">Nouvelle Règle de Prix</h3>
            <button onclick="closeModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form id="ruleForm" class="p-6 space-y-4">
            <input type="hidden" id="ruleId">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la Règle *</label>
                    <input type="text" id="ruleName" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de Produit *</label>
                    <select id="productType" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        <option value="">Sélectionner...</option>
                        <option value="flight">Vols</option>
                        <option value="event">Événements</option>
                        <option value="package">Packages</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                <input type="text" id="category" placeholder="Ex: domestic, international, vip..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                <p class="text-xs text-gray-500 mt-1">Laissez vide pour appliquer à toutes les catégories</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de Marge *</label>
                    <select id="marginType" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        <option value="percentage">Pourcentage (%)</option>
                        <option value="fixed">Montant Fixe (XOF)</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Valeur de la Marge *</label>
                    <input type="number" id="marginValue" required step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix Minimum</label>
                    <input type="number" id="minPrice" step="0.01" min="0" placeholder="Optionnel" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix Maximum</label>
                    <input type="number" id="maxPrice" step="0.01" min="0" placeholder="Optionnel" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priorité</label>
                <input type="number" id="priority" value="0" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                <p class="text-xs text-gray-500 mt-1">Plus la priorité est élevée, plus la règle sera appliquée en premier</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"></textarea>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="isActive" checked class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                <label for="isActive" class="ml-2 text-sm text-gray-700">Règle active</label>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-primary to-purple-600 text-white rounded-lg hover:shadow-lg transition-all">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModal(mode, rule = null) {
    const modal = document.getElementById('ruleModal');
    const title = document.getElementById('modalTitle');
    
    if (mode === 'add') {
        title.textContent = 'Nouvelle Règle de Prix';
        document.getElementById('ruleForm').reset();
        document.getElementById('ruleId').value = '';
    } else {
        title.textContent = 'Modifier la Règle';
        // Remplir le formulaire avec les données de la règle
        if (rule) {
            document.getElementById('ruleId').value = rule.id;
            document.getElementById('ruleName').value = rule.rule_name;
            document.getElementById('productType').value = rule.product_type;
            document.getElementById('category').value = rule.category || '';
            document.getElementById('marginType').value = rule.margin_type;
            document.getElementById('marginValue').value = rule.margin_value;
            document.getElementById('minPrice').value = rule.min_price || '';
            document.getElementById('maxPrice').value = rule.max_price || '';
            document.getElementById('priority').value = rule.priority;
            document.getElementById('description').value = rule.description || '';
            document.getElementById('isActive').checked = rule.is_active;
        }
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('ruleModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('ruleForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const ruleId = document.getElementById('ruleId').value;
    const url = ruleId ? `/admin/pricing-rules/${ruleId}` : '/admin/pricing-rules';
    const method = ruleId ? 'PUT' : 'POST';
    
    const data = {
        rule_name: document.getElementById('ruleName').value,
        product_type: document.getElementById('productType').value,
        category: document.getElementById('category').value || null,
        margin_type: document.getElementById('marginType').value,
        margin_value: document.getElementById('marginValue').value,
        min_price: document.getElementById('minPrice').value || null,
        max_price: document.getElementById('maxPrice').value || null,
        priority: document.getElementById('priority').value,
        description: document.getElementById('description').value || null,
        is_active: document.getElementById('isActive').checked
    };
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            closeModal();
            location.reload();
        } else {
            alert('Erreur lors de l\'enregistrement');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Erreur de connexion');
    }
});

async function toggleRule(id) {
    if (confirm('Voulez-vous changer le statut de cette règle ?')) {
        try {
            const response = await fetch(`/admin/pricing-rules/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (response.ok) {
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
}

async function deleteRule(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette règle ?')) {
        try {
            const response = await fetch(`/admin/pricing-rules/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (response.ok) {
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
}
</script>
@endpush
@endsection
