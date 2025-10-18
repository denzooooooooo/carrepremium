@extends('admin.layouts.app')

@section('title', 'Gestion des Avis')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Gestion des Avis Clients</h1>
            <p class="text-muted">Modérez et répondez aux avis des clients</p>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-star fa-2x text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Avis</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">En Attente</h6>
                            <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Approuvés</h6>
                            <h3 class="mb-0">{{ $stats['approved'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-star-half-alt fa-2x text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Note Moyenne</h6>
                            <h3 class="mb-0">{{ number_format($stats['average_rating'], 1) }}/5</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Recherche</label>
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select">
                        <option value="">Tous</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvés</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Note</label>
                    <select name="rating" class="form-select">
                        <option value="">Toutes</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 étoiles</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 étoiles</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 étoiles</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 étoiles</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 étoile</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Filtrer
                    </button>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-redo me-1"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des avis -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Liste des Avis ({{ $reviews->total() }})</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-success" onclick="bulkApprove()">
                        <i class="fas fa-check me-1"></i> Approuver sélection
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="bulkDelete()">
                        <i class="fas fa-trash me-1"></i> Supprimer sélection
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="30">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Client</th>
                            <th>Produit</th>
                            <th>Note</th>
                            <th>Commentaire</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input review-checkbox" value="{{ $review->id }}">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        @if($review->user->avatar)
                                            <img src="{{ $review->user->avatar }}" class="rounded-circle" width="32">
                                        @else
                                            <div class="avatar-placeholder">{{ substr($review->user->first_name, 0, 1) }}</div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $review->user->first_name }} {{ $review->user->last_name }}</div>
                                        <small class="text-muted">{{ $review->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($review->item_type) }}</span>
                                <br>
                                <small class="text-muted">ID: {{ $review->item_id }}</small>
                            </td>
                            <td>
                                <div class="rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-muted"></i>
                                        @endif
                                    @endfor
                                </div>
                                <small class="text-muted">{{ $review->rating }}/5</small>
                            </td>
                            <td>
                                @if($review->title)
                                    <div class="fw-bold">{{ Str::limit($review->title, 30) }}</div>
                                @endif
                                <div class="text-muted small">{{ Str::limit($review->comment, 50) }}</div>
                            </td>
                            <td>
                                @if($review->is_approved)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i> Approuvé
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i> En attente
                                    </span>
                                @endif
                                @if($review->is_verified)
                                    <br><span class="badge bg-primary mt-1">
                                        <i class="fas fa-shield-alt me-1"></i> Vérifié
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $review->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(!$review->is_approved)
                                        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" title="Approuver">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning" title="Rejeter">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet avis ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun avis trouvé</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($reviews->hasPages())
        <div class="card-footer bg-white">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Select all checkboxes
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.review-checkbox').forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Bulk approve
function bulkApprove() {
    const selected = Array.from(document.querySelectorAll('.review-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un avis');
        return;
    }
    
    if (confirm(`Approuver ${selected.length} avis ?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.reviews.bulk-approve") }}';
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        selected.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Bulk delete
function bulkDelete() {
    const selected = Array.from(document.querySelectorAll('.review-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un avis');
        return;
    }
    
    if (confirm(`Supprimer ${selected.length} avis ? Cette action est irréversible.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.reviews.bulk-delete") }}';
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        selected.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush

@push('styles')
<style>
.avatar-placeholder {
    width: 32px;
    height: 32px;
    background: #9333EA;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
</style>
@endpush
@endsection
