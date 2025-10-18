@extends('admin.layouts.app')

@section('title', 'Configuration APIs')
@section('page-title', 'Configuration des APIs')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 font-montserrat">Configuration APIs</h2>
            <p class="text-gray-600 mt-1">Gérez vos intégrations API externes</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($configs as $config)
        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-plug text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-800">{{ ucfirst($config->provider) }}</h3>
                        <p class="text-sm text-gray-500">API Configuration</p>
                    </div>
                </div>
                <span class="px-2 py-1 {{ $config->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-xs rounded-full">
                    {{ $config->is_active ? 'Actif' : 'Inactif' }}
                </span>
            </div>
            <div class="space-y-2 text-sm text-gray-600">
                <p><strong>Endpoint:</strong> {{ $config->endpoint_url }}</p>
                <p><strong>Mode:</strong> {{ $config->is_production ? 'Production' : 'Test' }}</p>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
            <i class="fas fa-plug text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">Aucune configuration API</p>
            <p class="text-sm text-gray-400 mt-2">Consultez REMAINING_FILES_COMPLETE.md pour ajouter des configurations</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
