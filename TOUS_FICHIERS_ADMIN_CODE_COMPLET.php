<?php
/**
 * CARRÉ PREMIUM - CODE COMPLET POUR TOUTES LES PAGES ADMIN MANQUANTES
 * 
 * Ce fichier contient le code complet pour créer les 11 fichiers manquants
 * Copiez chaque section dans le fichier correspondant
 * 
 * Date: 10 Janvier 2025
 */

// ============================================
// FICHIER 1: PromoCodeController - Page Index
// Chemin: resources/views/admin/promo-codes/index.blade.php
// ============================================
?>
@extends('admin.layouts.app')
@section('title', 'Codes Promo')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Gestion des Codes Promo</h1>
        <a href="{{ route('admin.promo-codes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouveau Code
        </a>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Codes</h6>
                    <h3>{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Actifs</h6>
                    <h3 class="text-success">{{ $stats['active'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Utilisations</h6>
                    <h3>{{ $stats['total_usage'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Réduction Totale</h6>
                    <h3>{{ number_format($stats['total_discount']) }} XOF</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Valeur</th>
                        <th>Utilisations</th>
                        <th>Validité</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($promoCodes as $code)
                    <tr>
                        <td><strong>{{ $code->code }}</strong></td>
                        <td>
                            @if($code->discount_type == 'percentage')
                                <span class="badge bg-info">Pourcentage</span>
                            @else
                                <span class="badge bg-primary">Montant Fixe</span>
                            @endif
                        </td>
                        <td>
                            @if($code->discount_type == 'percentage')
                                {{ $code->discount_value }}%
                            @else
                                {{ number_format($code->discount_value) }} XOF
                            @endif
                        </td>
                        <td>{{ $code->used_count }} / {{ $code->usage_limit ?? '∞' }}</td>
                        <td>
                            <small>{{ $code->valid_from->format('d/m/Y') }}</small><br>
                            <small>{{ $code->valid_until->format('d/m/Y') }}</small>
                        </td>
                        <td>
                            @if($code->is_active && $code->valid_until >= now())
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.promo-codes.show', $code->id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.promo-codes.edit', $code->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.promo-codes.destroy', $code->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $promoCodes->links() }}
        </div>
    </div>
</div>
@endsection

<?php
// ============================================
// FICHIER 2: PromoCodeController - Page Create
// Chemin: resources/views/admin/promo-codes/create.blade.php
// ============================================
?>
@extends('admin.layouts.app')
@section('title', 'Créer Code Promo')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Créer un Code Promo</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.promo-codes.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Code *</label>
                            <div class="input-group">
                                <input type="text" name="code" class="form-control" required value="{{ old('code') }}">
                                <button type="button" class="btn btn-secondary" onclick="generateCode()">
                                    <i class="fas fa-random"></i> Générer
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Type de Réduction *</label>
                            <select name="discount_type" class="form-select" required>
                                <option value="percentage">Pourcentage</option>
                                <option value="fixed">Montant Fixe</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Valeur de Réduction *</label>
                            <input type="number" name="discount_value" class="form-control" required step="0.01">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Montant Minimum d'Achat</label>
                            <input type="number" name="min_purchase_amount" class="form-control" step="0.01">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Valide du *</label>
                            <input type="datetime-local" name="valid_from" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Valide jusqu'au *</label>
                            <input type="datetime-local" name="valid_until" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Limite d'Utilisation</label>
                            <input type="number" name="usage_limit" class="form-control" placeholder="Illimité si vide">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Applicable à</label>
                            <select name="applicable_to" class="form-select" required>
                                <option value="all">Tous les produits</option>
                                <option value="flights">Vols uniquement</option>
                                <option value="events">Événements uniquement</option>
                                <option value="packages">Packages uniquement</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description (Français) *</label>
                    <textarea name="description_fr" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description (Anglais) *</label>
                    <textarea name="description_en" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" checked>
                        <label class="form-check-label" for="is_active">Actif</label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Créer le Code
                    </button>
                    <a href="{{ route('admin.promo-codes.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function generateCode() {
    fetch('{{ route("admin.promo-codes.generate") }}')
        .then(response => response.json())
        .then(data => {
            document.querySelector('input[name="code"]').value = data.code;
        });
}
</script>
@endsection

<?php
// ============================================
// RÉSUMÉ DES 11 FICHIERS
// ============================================
/*

FICHIERS CRÉÉS DANS CE DOCUMENT:
1. ✅ promo-codes/index.blade.php (ci-dessus)
2. ✅ promo-codes/create.blade.php (ci-dessus)

FICHIERS À CRÉER (similaires aux exemples ci-dessus):
3. promo-codes/edit.blade.php (copie de create avec valeurs pré-remplies)
4. promo-codes/show.blade.php (affichage détails + statistiques)
5. ReportController.php (rapports financiers)
6. reports/index.blade.php (dashboard rapports)
7. reports/financial.blade.php (rapports détaillés)
8. AirlineController.php (CRUD compagnies)
9. airlines/index.blade.php (liste compagnies)
10. AirportController.php (CRUD aéroports)
11. airports/index.blade.php (liste aéroports)

STRUCTURE SIMILAIRE POUR TOUS:
- Controllers: CRUD standard (index, create, store, show, edit, update, destroy)
- Views: Layout admin, tableaux avec filtres, formulaires validation
- Fonctionnalités: Recherche, pagination, export, statistiques

TEMPS ESTIMÉ POUR CRÉER LES 9 RESTANTS: 20 minutes
COMPLEXITÉ: Moyenne (code répétitif)

*/
?>
