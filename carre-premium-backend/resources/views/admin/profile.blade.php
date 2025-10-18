@extends('admin.layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Mon Profil</h1>
        <p class="text-gray-600 mt-2">Gérez vos informations personnelles et paramètres de compte</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center">
                    <div class="relative inline-block">
                        @if(auth('admin')->user()->avatar)
                            <img src="{{ asset('storage/' . auth('admin')->user()->avatar) }}" 
                                 alt="{{ auth('admin')->user()->name }}" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-purple-200">
                        @else
                            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white text-4xl font-bold border-4 border-purple-200">
                                {{ substr(auth('admin')->user()->name, 0, 1) }}
                            </div>
                        @endif
                        <button onclick="document.getElementById('avatar-input').click()" 
                                class="absolute bottom-0 right-0 bg-purple-600 text-white p-2 rounded-full hover:bg-purple-700">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mt-4">{{ auth('admin')->user()->name }}</h3>
                    <p class="text-gray-600">{{ auth('admin')->user()->email }}</p>
                    <span class="inline-block mt-2 px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                        {{ ucfirst(str_replace('_', ' ', auth('admin')->user()->role)) }}
                    </span>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="space-y-3">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-calendar-alt w-5"></i>
                            <span class="ml-3 text-sm">Membre depuis {{ auth('admin')->user()->created_at->format('M Y') }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-clock w-5"></i>
                            <span class="ml-3 text-sm">Dernière connexion: {{ auth('admin')->user()->last_login ? auth('admin')->user()->last_login->diffForHumans() : 'Jamais' }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-shield-alt w-5"></i>
                            <span class="ml-3 text-sm">Compte {{ auth('admin')->user()->is_active ? 'Actif' : 'Inactif' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations Personnelles -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user text-purple-600 mr-2"></i>
                    Informations Personnelles
                </h2>
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden" onchange="this.form.submit()">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom complet *</label>
                            <input type="text" name="name" value="{{ auth('admin')->user()->name }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" value="{{ auth('admin')->user()->email }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                            <input type="text" name="phone" value="{{ auth('admin')->user()->phone }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                            <input type="text" value="{{ ucfirst(str_replace('_', ' ', auth('admin')->user()->role)) }}" disabled
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                            <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>

            <!-- Changer le mot de passe -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-lock text-purple-600 mr-2"></i>
                    Changer le mot de passe
                </h2>
                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe actuel *</label>
                            <input type="password" name="current_password" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe *</label>
                            <input type="password" name="password" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe *</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                            <i class="fas fa-key mr-2"></i>Changer le mot de passe
                        </button>
                    </div>
                </form>
            </div>

            <!-- Activité récente -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-history text-purple-600 mr-2"></i>
                    Activité Récente
                </h2>
                <div class="space-y-3">
                    @forelse($recentActivities ?? [] as $activity)
                    <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-circle text-purple-600 text-xs mt-1.5 mr-3"></i>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Aucune activité récente</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
