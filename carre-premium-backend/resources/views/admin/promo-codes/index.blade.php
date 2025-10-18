@extends('admin.layouts.app')

@section('title', 'Codes Promo')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Gestion des Codes Promo</h1>
            <p class="text-muted">Créez et gérez vos codes promotionnels</p>
        </div>
        <a href="{{ route('admin.promo-codes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouveau Code Promo
        </a>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-ticket-alt fa-2x text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Codes</h6>
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
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Actifs</h6>
                            <h3 class="mb-0 text-success">{{ $stats['active'] }}</h3>
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
                            <i class="fas fa-chart-line fa-2x text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Utilisations</h6>
                            <h3 class="mb-0">{{ $stats['total_usage'] }}</h3>
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
                            <i class="fas fa-money-bill-wave fa-2x text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Réduction Totale</h6>
                            <h3 class="mb-0">{{ number_format($stats['total_discount']) }} XOF</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.promo-codes.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Recherche</label>
                    <input type="text" name="search" class="form-control" placeholder="Code ou description..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select">
                        <option value="">Tous</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expirés</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactifs</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">Tous</option>
                        <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Pourcentage</option>
                        <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Montant Fixe</option>
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
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.promo-codes.index') }}" class="btn btn-secondary flex-fill">
                            <i class="fas fa-redo me-1"></i> Réinitialiser
                        </a>
                        <a href="{{ route('admin.promo-codes.export') }}" class="btn btn-success flex-fill">
                            <i class="fas fa-download me-1"></i> Export
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des codes promo -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Liste des Codes Promo ({{ $promoCodes->total() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Valeur</th>
                            <th>Utilisations</th>
                            <th>Validité</th>
                            <th>Statut</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promoCodes as $code)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $code->code }}</strong>
                                @if($code->applicable_to != 'all')
                                    <br><small class="badge bg-secondary">{{ ucfirst($code->applicable_to) }}</small>
                                @endif
                            </td>
                            <td>
                                <div>{{ Str::limit($code->description_fr, 50) }}</div>
                                @if($code->min_purchase_amount)
                                    <small class="text-muted">Min: {{ number_format($code->min_purchase_amount) }} XOF</small>
                                @endif
                            </td>
                            <td>
                                @if($code->discount_type == 'percentage')
                                    <span class="badge bg-info">
                                        <i class="fas fa-percent me-1"></i> Pourcentage
                                    </span>
                                @else
                                    <span class="badge bg-primary">
                                        <i class="fas fa-coins me-1"></i> Montant Fixe
                                    </span>
                                @endif
                            </td>
                            <td>
                                <strong>
                                    @if($code->discount_type == 'percentage')
                                        {{ $code->discount_value }}%
                                    @else
                                        {{ number_format($code->discount_value) }} XOF
                                    @endif
                                </strong>
                                @if($code->max_discount_amount)
                                    <br><small class="text-muted">Max: {{ number_format($code->max_discount_amount) }} XOF</small>
                                @endif
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    @php
                                        $percentage = $code->usage_limit ? ($code->used_count / $code->usage_limit * 100) : 0;
                                    @endphp
                                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%">
                                        {{ $code->used_count }} / {{ $code->usage_limit ?? '∞' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <small>
                                    <i class="fas fa-calendar-alt text-muted"></i> {{ $code->valid_from->format('d/m/Y') }}
                                    <br>
                                    <i class="fas fa-calendar-times text-muted"></i> {{ $code->valid_until->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>
                                @if($code->is_active && $code->valid_until >= now() && (!$code->usage_limit || $code->used_count < $code->usage_limit))
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Actif
                                    </span>
                                @elseif($code->valid_until < now())
                                    <span class="badge bg-danger">
                                        <i class="fas fa-clock me-1"></i> Expiré
                                    </span>
                                @elseif($code->usage_limit && $code->used_count >= $code->usage_limit)
                                    <span class="badge bg-warning">
                                        <i class="fas fa-ban me-1"></i> Limite atteinte
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-pause-circle me-1"></i> Inactif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.promo-codes.show', $code->id) }}" class="btn btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.promo-codes.edit', $code->id) }}" class="btn btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.promo-codes.toggle-status', $code->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-{{ $code->is_active ? 'secondary' : 'success' }}" title="{{ $code->is_active ? 'Désactiver' : 'Activer' }}">
                                            <i class="fas fa-{{ $code->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.promo-codes.destroy', $code->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce code promo ?')">
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
                                <p class="text-muted">Aucun code promo trouvé</p>
                                <a href="{{ route('admin.promo-codes.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Créer le premier code
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($promoCodes->hasPages())
        <div class="card-footer bg-white">
            {{ $promoCodes->links() }}
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.progress {
    background-color: #e9ecef;
}
.progress-bar {
    background-color: #9333EA;
    font-size: 11px;
    line-height: 20px;
}
</style>
@endpush
@endsection
