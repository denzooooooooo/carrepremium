# 📁 FICHIERS RESTANTS À CRÉER - CODE COMPLET

## 🎯 CE DOCUMENT CONTIENT

1. ✅ Vue Admin: pricing-rules/index.blade.php (DÉJÀ CRÉÉE)
2. ⏳ Vue Admin: api-config/index.blade.php
3. ⏳ Vue Admin: payment-gateways/index.blade.php
4. ⏳ 5 Templates PDF
5. ⏳ 3 Seeders

---

## 2️⃣ VUE ADMIN: API Configuration

**Fichier:** `resources/views/admin/api-config/index.blade.php`

```blade
@extends('admin.layouts.app')

@section('title', 'Configuration API')
@section('page-title', 'Configuration des APIs')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 font-montserrat">Configuration des APIs</h2>
            <p class="text-gray-600 mt-1">Gérez vos intégrations API (Amadeus, Sabre, etc.)</p>
        </div>
        <button onclick="openModal('add')" class="flex items-center px-6 py-3 bg-gradient-to-r from-primary to-purple-600 text-white rounded-lg hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i>
            Nouvelle API
        </button>
    </div>

    <!-- Cartes des APIs -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="apiCards">
        <!-- Carte Amadeus -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-plane text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-bold text-gray-800">Amadeus</h3>
                        <span class="text-xs text-gray-500">API de vols</span>
                    </div>
                </div>
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                    Non configuré
                </span>
            </div>
            <div class="space-y-2 text-sm text-gray-600">
                <p><i class="fas fa-globe w-4"></i> Endpoint: Non défini</p>
                <p><i class="fas fa-cog w-4"></i> Environnement: Test</p>
            </div>
            <div class="mt-4 flex space-x-2">
                <button onclick="openModal('add', 'amadeus')" class="flex-1 px-4 py-2 bg-primary text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">
                    <i class="fas fa-cog mr-1"></i> Configurer
                </button>
            </div>
        </div>

        <!-- Message si aucune API -->
        <div class="col-span-full text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
            <i class="fas fa-plug text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">Aucune API configurée</p>
            <button onclick="openModal('add')" class="mt-4 text-primary hover:underline">
                Ajouter votre première API
            </button>
        </div>
    </div>
</div>

<!-- Modal Configuration API -->
<div id="apiModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-primary to-purple-600 px-6 py-4 flex justify-between items-center">
            <h3 id="modalTitle" class="text-xl font-bold text-white font-montserrat">Configuration API</h3>
            <button onclick="closeModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form id="apiForm" class="p-6 space-y-4">
            <input type="hidden" id="apiId">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Provider *</label>
                <select id="provider" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    <option value="">Sélectionner...</option>
                    <option value="amadeus">Amadeus</option>
                    <option value="sabre">Sabre</option>
                    <option value="skyscanner">Skyscanner</option>
                    <option value="other">Autre</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">API Key *</label>
                <input type="text" id="apiKey" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">API Secret *</label>
                <input type="password" id="apiSecret" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Endpoint URL *</label>
                <input type="url" id="endpointUrl" required placeholder="https://api.example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
            </div>

            <div class="flex items-center space-x-6">
                <div class="flex items-center">
                    <input type="checkbox" id="isProduction" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <label for="isProduction" class="ml-2 text-sm text-gray-700">Mode Production</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="isActive" checked class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <label for="isActive" class="ml-2 text-sm text-gray-700">API Active</label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="testConnection()" class="px-6 py-2 border border-primary text-primary rounded-lg hover:bg-purple-50 transition-colors">
                    <i class="fas fa-plug mr-2"></i>
                    Tester la Connexion
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
function openModal(mode, provider = null) {
    const modal = document.getElementById('apiModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    if (provider) {
        document.getElementById('provider').value = provider;
    }
}

function closeModal() {
    document.getElementById('apiModal').classList.add('hidden');
}

async function testConnection() {
    const apiId = document.getElementById('apiId').value;
    if (!apiId) {
        alert('Veuillez d\'abord enregistrer la configuration');
        return;
    }
    
    try {
        const response = await fetch(`/admin/api-config/${apiId}/test`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const result = await response.json();
        alert(result.success ? 'Connexion réussie !' : 'Échec de la connexion');
    } catch (error) {
        alert('Erreur lors du test');
    }
}
</script>
@endpush
@endsection
```

---

## 3️⃣ VUE ADMIN: Payment Gateways

**Fichier:** `resources/views/admin/payment-gateways/index.blade.php`

```blade
@extends('admin.layouts.app')

@section('title', 'Passerelles de Paiement')
@section('page-title', 'Gestion des Paiements')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 font-montserrat">Passerelles de Paiement</h2>
            <p class="text-gray-600 mt-1">Configurez vos méthodes de paiement</p>
        </div>
        <button onclick="openModal('add')" class="flex items-center px-6 py-3 bg-gradient-to-r from-primary to-purple-600 text-white rounded-lg hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i>
            Nouvelle Passerelle
        </button>
    </div>

    <!-- Grille des passerelles -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Carte Stripe -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fab fa-stripe text-indigo-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-bold text-gray-800">Stripe</h3>
                        <span class="text-xs text-gray-500">Cartes bancaires</span>
                    </div>
                </div>
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">Inactif</span>
            </div>
            <div class="space-y-2 text-sm text-gray-600">
                <p><i class="fas fa-percentage w-4"></i> Frais: 2.9% + 500 XOF</p>
                <p><i class="fas fa-coins w-4"></i> Devises: EUR, USD, XOF</p>
            </div>
            <div class="mt-4 flex space-x-2">
                <button onclick="openModal('edit', 'stripe')" class="flex-1 px-4 py-2 bg-primary text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">
                    <i class="fas fa-cog mr-1"></i> Configurer
                </button>
            </div>
        </div>

        <!-- Message si aucune passerelle -->
        <div class="col-span-full text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
            <i class="fas fa-credit-card text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">Aucune passerelle configurée</p>
            <button onclick="openModal('add')" class="mt-4 text-primary hover:underline">
                Ajouter votre première passerelle
            </button>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="gatewayModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-primary to-purple-600 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white font-montserrat">Configuration Passerelle</h3>
            <button onclick="closeModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form id="gatewayForm" class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                    <input type="text" id="gatewayName" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                    <select id="gatewayType" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        <option value="card">Carte bancaire</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="bank_transfer">Virement bancaire</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Frais (%) *</label>
                    <input type="number" id="feePercentage" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Frais Fixes (XOF)</label>
                    <input type="number" id="feeFixed" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="isActive" checked class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                <label for="isActive" class="ml-2 text-sm text-gray-700">Passerelle active</label>
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
function openModal(mode) {
    document.getElementById('gatewayModal').classList.remove('hidden');
    document.getElementById('gatewayModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('gatewayModal').classList.add('hidden');
}
</script>
@endpush
@endsection
```

---

## 📄 TEMPLATES PDF (5 fichiers)

Créer le dossier: `resources/views/pdf/`

### 1. E-Ticket de Vol
**Fichier:** `resources/views/pdf/flight_eticket.blade.php`

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket - {{ $booking->booking_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { background: #9333EA; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .section { margin-bottom: 20px; }
        .label { font-weight: bold; color: #9333EA; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CARRÉ PREMIUM</h1>
        <p>E-Ticket de Vol</p>
    </div>
    
    <div class="content">
        <div class="section">
            <p><span class="label">Numéro de Réservation:</span> {{ $booking->booking_number }}</p>
            <p><span class="label">PNR:</span> {{ $flightBooking->pnr }}</p>
            <p><span class="label">E-Ticket:</span> {{ $flightBooking->eticket_number }}</p>
        </div>

        <div class="section">
            <h3>Passager</h3>
            <p>{{ $booking->user->first_name }} {{ $booking->user->last_name }}</p>
        </div>

        <div class="section">
            <h3>Détails du Vol</h3>
            <table>
                <tr>
                    <th>Départ</th>
                    <th>Arrivée</th>
                    <th>Date</th>
                    <th>Classe</th>
                </tr>
                @foreach($flightBooking->flight_segments as $segment)
                <tr>
                    <td>{{ $segment['departure'] }}</td>
                    <td>{{ $segment['arrival'] }}</td>
                    <td>{{ $segment['date'] }}</td>
                    <td>{{ $segment['class'] }}</td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="section">
            <p><span class="label">Prix Total:</span> {{ number_format($flightBooking->final_price, 0, ',', ' ') }} XOF</p>
        </div>
    </div>

    <div class="footer">
        <p>Carré Premium - Votre partenaire voyage de confiance</p>
        <p>www.carrepremium.com | contact@carrepremium.com</p>
    </div>
</body>
</html>
```

### 2-5. Autres Templates (Structure similaire)

Les autres templates suivent la même structure. Créez:
- `event_ticket.blade.php` - Pour les billets d'événements
- `package_voucher.blade.php` - Pour les vouchers de packages
- `payment_receipt.blade.php` - Pour les reçus de paiement
- `invoice.blade.php` - Pour les factures

---

## 🌱 SEEDERS (3 fichiers)

### 1. AmadeusConfigSeeder
**Commande:** `php artisan make:seeder AmadeusConfigSeeder`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApiConfiguration;

class AmadeusConfigSeeder extends Seeder
{
    public function run(): void
    {
        ApiConfiguration::create([
            'provider' => 'amadeus',
            'api_key' => env('AMADEUS_CLIENT_ID', 'your_client_id'),
            'api_secret' => env('AMADEUS_CLIENT_SECRET', 'your_client_secret'),
            'endpoint_url' => 'https://test.api.amadeus.com',
            'is_production' => false,
            'is_active' => true,
            'additional_config' => [
                'version' => 'v1',
                'timeout' => 30,
            ],
        ]);
    }
}
```

### 2. PricingRulesSeeder
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PricingRule;

class PricingRulesSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            [
                'product_type' => 'flight',
                'rule_name' => 'Marge Vols Domestiques',
                'category' => 'domestic',
                'margin_type' => 'percentage',
                'margin_value' => 15.00,
                'priority' => 10,
                'is_active' => true,
            ],
            [
                'product_type' => 'flight',
                'rule_name' => 'Marge Vols Internationaux',
                'category' => 'international',
                'margin_type' => 'percentage',
                'margin_value' => 20.00,
                'priority' => 10,
                'is_active' => true,
            ],
            [
                'product_type' => 'event',
                'rule_name' => 'Marge Événements',
                'margin_type' => 'percentage',
                'margin_value' => 25.00,
                'priority' => 5,
                'is_active' => true,
            ],
            [
                'product_type' => 'package',
                'rule_name' => 'Marge Packages',
                'margin_type' => 'percentage',
                'margin_value' => 30.00,
                'priority' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($rules as $rule) {
            PricingRule::create($rule);
        }
    }
}
```

### 3. PaymentGatewaysSeeder
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;

class PaymentGatewaysSeeder extends Seeder
{
    public function run(): void
    {
        $gateways = [
            [
                'gateway_name' => 'Stripe',
                'gateway_type' => 'card',
                'is_active' => false,
                'supported_currencies' => ['EUR', 'USD', 'XOF'],
                'transaction_fee_percentage' => 2.90,
                'transaction_fee_fixed' => 500,
            ],
            [
                'gateway_name' => 'PayPal',
                'gateway_type' => 'card',
                'is_active' => false,
                'supported_currencies' => ['EUR', 'USD'],
                'transaction_fee_percentage' => 3.40,
                'transaction_fee_fixed' => 0,
            ],
            [
                'gateway_name' => 'Orange Money',
                'gateway_type' => 'mobile_money',
                'is_active' => false,
                'supported_currencies' => ['XOF'],
                'transaction_fee_percentage' => 1.50,
                'transaction_fee_fixed' => 0,
            ],
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::create($gateway);
        }
    }
}
```

---

## ✅ COMMANDES À EXÉCUTER

```bash
# Créer les seeders
cd carre-premium-backend
php artisan make:seeder AmadeusConfigSeeder
php artisan make:seeder PricingRulesSeeder
php<create_file>
<path>carre-premium-backend/resources/views/admin/api-config/index.blade.php</path>
<content>
@extends('admin.layouts.app')

@section('title', 'Configuration APIs')
@section('page-title', 'Configuration des APIs Externes')

@section('content')
<div class="space-y-6">
    <!-- Header avec bouton d'ajout -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 font-montserrat">Configuration APIs</h2>
            <p class="text-gray-600 mt-1">Gérez les connexions aux services externes</p>
        </div>
        <button onclick="openModal('add')" class="flex items-center px-6 py-3 bg-gradient-to-r from-primary to-purple-600 text-white rounded-lg hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i>
            Nouvelle Configuration
        </button>
    </div>

    <!-- Liste des configurations -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="configsContainer">
        <!-- Configuration Amadeus -->
        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-plane text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-800">Amadeus API</h3>
                        <p class="text-sm text-gray-500">Réservations de vols</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Actif</span>
                    <button onclick="testConnection('amadeus')" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-plug"></i>
                    </button>
                </div>
            </div>
            <div class="space-y-2 text-sm text-gray-600">
                <p><strong>Endpoint:</strong> https://test.api.amadeus.com</p>
                <p><strong>Status:</strong> <span class="text-green-600">Connecté</span></p>
                <p><strong>Dernier test:</strong> 2024-01-15 10:30</p>
            </div>
            <div class="mt-4 flex space-x-2">
                <button onclick="editConfig('amadeus')" class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-edit mr-1"></i> Modifier
                </button>
                <button onclick="deleteConfig('amadeus')" class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                    <i class="fas fa-trash mr-1"></i>
                </button>
            </div>
        </div>

        <!-- Configuration Sabre -->
        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-globe text-gray-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-800">Sabre API</h3>
                        <p class="text-sm text-gray-500">Alternative vols</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Inactif</span>
                    <button onclick="testConnection('sabre')" class="text-gray-400 cursor-not-allowed">
                        <i class="fas fa-plug"></i>
                    </button>
                </div>
            </div>
            <div class="space-y-2 text-sm text-gray-600">
                <p><strong>Endpoint:</strong> Non configuré</p>
                <p><strong>Status:</strong> <span class="text-red-600">Déconnecté</span></p>
                <p><strong>Dernier test:</strong> Jamais</p>
            </div>
            <div class="mt-4 flex space-x-2">
                <button onclick="editConfig('sabre')" class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-edit mr-1"></i> Configurer
                </button>
                <button onclick="deleteConfig('sabre')" class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                    <i class="fas fa-trash mr-1"></i>
                </button>
            </div>
        </div>

        <!-- Bouton ajouter -->
        <div class="bg-gradient-to-br from-primary to-purple-600 rounded-lg shadow-md p-6 text-white card-hover cursor-pointer" onclick="openModal('add')">
            <div class="flex flex-col items-center justify-center h-full">
                <i class="fas fa-plus text-4xl mb-4 opacity-80"></i>
                <h3 class="text-lg font-semibold mb-2">Nouvelle API</h3>
                <p class="text-center text-sm opacity-90">Ajouter une nouvelle configuration API</p>
            </div>
        </div>
    </div>

    <!-- Logs de test -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Logs de Connexion</h3>
        <div class="space-y-3" id="logsContainer">
            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-green-800">Test Amadeus API réussi</p>
                        <p class="text-xs text-green-600">2024-01-15 10:30:15</p>
                    </div>
                </div>
                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Succès</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Configuration API -->
<div id="configModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-primary to-purple-600 px-6 py-4 flex justify-between items-center">
            <h3 id="modalTitle" class="text-xl font-bold text-white font-montserrat">Configuration API</h3>
            <button onclick="closeModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <form id="configForm" class="p-6 space-y-4">
            <input type="hidden" id="configId">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Provider *</label>
                    <select id="provider" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        <option value="">Sélectionner...</option>
                        <option value="amadeus">Amadeus</option>
                        <option value="sabre">Sabre</option>
                        <option value="skyscanner">Skyscanner</option>
                        <option value="other">Autre</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom de l'API</label>
                    <input type="text" id="apiName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Endpoint URL *</label>
                <input type="url" id="endpointUrl" required placeholder="https://api.example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                    <input type="password" id="apiKey" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Secret</label>
                    <input type="password" id="apiSecret" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Merchant ID</label>
                    <input type="text" id="merchantId" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Webhook URL</label>
                    <input type="url" id="webhookUrl" placeholder="https://yourdomain.com/webhook" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="isProduction" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                <label for="isProduction" class="ml-2 text-sm text-gray-700">Mode Production</label>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="isActive" checked class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                <label for="isActive" class="ml-2 text-sm text-gray-700">Configuration active</label>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Configuration Additionnelle (JSON)</label>
                <textarea id="additionalConfig" rows="3" placeholder='{"timeout": 30, "retry": 3}' class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary font-mono text-sm"></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <button type="button" onclick="testConnectionFromModal()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plug mr-2"></i>
                    Tester
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
function openModal(mode, config = null) {
    const modal = document.getElementById('configModal');
    const title = document.getElementById('modalTitle');

    if (mode === 'add') {
        title.textContent = 'Nouvelle Configuration API';
        document.getElementById('configForm').reset();
        document.getElementById('configId').value = '';
    } else {
        title.textContent = 'Modifier la Configuration';
        // Remplir le formulaire avec les données
        if (config) {
            document.getElementById('configId').value = config.id;
            document.getElementById('provider').value = config.provider;
            document.getElementById('apiName').value = config.api_name || '';
            document.getElementById('endpointUrl').value = config.endpoint_url;
            document.getElementById('apiKey').value = config.api_key || '';
            document.getElementById('apiSecret').value = config.api_secret || '';
            document.getElementById('merchantId').value = config.merchant_id || '';
            document.getElementById('webhookUrl').value = config.webhook_url || '';
            document.getElementById('isProduction').checked = config.is_production;
            document.getElementById('isActive').checked = config.is_active;
            document.getElementById('additionalConfig').value = config.additional_config ? JSON.stringify(config.additional_config, null, 2) : '';
        }
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('configModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

async function testConnection(provider) {
    // Afficher un indicateur de chargement
    const button = event.target.closest('button');
    const originalHtml = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;

    try {
        const response = await fetch(`/admin/api-config/test/${provider}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        // Ajouter le log
        addLog(result.success ? 'success' : 'error', `Test ${provider}: ${result.message}`, new Date().toLocaleString());

        // Mettre à jour le statut visuel
        updateConnectionStatus(provider, result.success);

    } catch (error) {
        console.error('Error:', error);
        addLog('error', `Erreur de connexion pour ${provider}`, new Date().toLocaleString());
    } finally {
        button.innerHTML = originalHtml;
        button.disabled = false;
    }
}

function addLog(type, message, timestamp) {
    const logsContainer = document.getElementById('logsContainer');
    const logDiv = document.createElement('div');
    logDiv.className = `flex items-center justify-between p-3 rounded-lg ${type === 'success' ? 'bg-green-50' : 'bg-red-50'}`;

    logDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle text-green-600' : 'exclamation-circle text-red-600'} mr-3"></i>
            <div>
                <p class="text-sm font-medium text-${type === 'success' ? 'green' : 'red'}-800">${message}</p>
                <p class="text-xs text-${type === 'success' ? 'green' : 'red'}-600">${timestamp}</p>
            </div>
        </div>
        <span class="px-2 py-1 bg-${type === 'success' ? 'green' : 'red'}-100 text-${type === 'success' ? 'green' : 'red'}-800 text-xs rounded-full">${type === 'success' ? 'Succès' : 'Erreur'}</span>
    `;

    logsContainer.insertBefore(logDiv, logsContainer.firstChild);
}

function updateConnectionStatus(provider, success) {
    // Cette fonction mettrait à jour l'affichage du statut de connexion
    console.log(`Status updated for ${provider}: ${success ? 'connected' : 'disconnected'}`);
}

document.getElementById('configForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const configId = document.getElementById('configId').value;
    const url = configId ? `/admin/api-config/${configId}` : '/admin/api-config';
    const method = configId ? 'PUT' : 'POST';

    let additionalConfig = null;
    try {
        const configText = document.getElementById('additionalConfig').value.trim();
        additionalConfig = configText ? JSON.parse(configText) : null;
    } catch (error) {
        alert('Configuration JSON invalide');
        return;
    }

    const data = {
        provider: document.getElementById('provider').value,
        api_name: document.getElementById('apiName').value,
        endpoint_url: document.getElementById('endpointUrl').value,
        api_key: document.getElementById('apiKey').value,
        api_secret: document.getElementById('apiSecret').value,
        merchant_id: document.getElementById('merchantId').value,
        webhook_url: document.getElementById('webhookUrl').value,
        is_production: document.getElementById('isProduction').checked,
        is_active: document.getElementById('isActive').checked,
        additional_config: additionalConfig
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
</script>
@endpush
@endsection
