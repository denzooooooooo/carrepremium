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
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($gateways as $gateway)
        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-credit-card text-indigo-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $gateway->gateway_name }}</h3>
                        <p class="text-sm text-gray-500">{{ ucfirst($gateway->gateway_type) }}</p>
                    </div>
                </div>
                <span class="px-2 py-1 {{ $gateway->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-xs rounded-full">
                    {{ $gateway->is_active ? 'Actif' : 'Inactif' }}
                </span>
            </div>
            <div class="space-y-2 text-sm text-gray-600">
                <p><strong>Frais:</strong> {{ $gateway->transaction_fee_percentage }}% + {{ number_format($gateway->transaction_fee_fixed, 0, ',', ' ') }} XOF</p>
                <p><strong>Devises:</strong> {{ is_array($gateway->supported_currencies) ? implode(', ', $gateway->supported_currencies) : $gateway->supported_currencies }}</p>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
            <i class="fas fa-credit-card text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">Aucune passerelle de paiement configurée</p>
            <p class="text-sm text-gray-400 mt-2">Consultez REMAINING_FILES_COMPLETE.md pour ajouter des passerelles</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
