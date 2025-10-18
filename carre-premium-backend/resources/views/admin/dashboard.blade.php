@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Tableau de Bord')

@section('content')
<div class="space-y-6">
    <!-- Statistiques Principales - Ligne 1 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Réservations Aujourd'hui -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-purple-100 text-sm font-semibold mb-1">Réservations Aujourd'hui</p>
                    <h3 class="text-4xl font-black" id="bookings-today">{{ $stats['bookings_today'] }}</h3>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-calendar-check text-3xl"></i>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="bg-white/20 px-3 py-1 rounded-full font-semibold">{{ $stats['bookings_week'] }} cette semaine</span>
            </div>
        </div>

        <!-- Revenus Aujourd'hui -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-green-100 text-sm font-semibold mb-1">Revenus Aujourd'hui</p>
                    <h3 class="text-4xl font-black" id="revenue-today">{{ number_format($stats['revenue_today'], 0, ',', ' ') }}</h3>
                    <p class="text-green-100 text-xs">XOF</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-money-bill-wave text-3xl"></i>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="bg-white/20 px-3 py-1 rounded-full font-semibold">{{ number_format($stats['revenue_month'], 0, ',', ' ') }} ce mois</span>
            </div>
        </div>

        <!-- Nouveaux Utilisateurs -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-blue-100 text-sm font-semibold mb-1">Nouveaux Utilisateurs</p>
                    <h3 class="text-4xl font-black">{{ $stats['new_users_today'] }}</h3>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-user-plus text-3xl"></i>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="bg-white/20 px-3 py-1 rounded-full font-semibold">{{ $stats['total_users'] }} total</span>
            </div>
        </div>

        <!-- En Attente -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-amber-100 text-sm font-semibold mb-1">En Attente</p>
                    <h3 class="text-4xl font-black" id="pending-bookings">{{ $stats['pending_bookings'] }}</h3>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-clock text-3xl"></i>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="bg-white/20 px-3 py-1 rounded-full font-semibold">{{ $stats['pending_reviews'] }} avis</span>
            </div>
        </div>
    </div>

    <!-- Statistiques Secondaires - Ligne 2 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Vols Réservés</p>
                    <h4 class="text-2xl font-black text-gray-800">{{ $stats['flight_bookings_total'] }}</h4>
                </div>
                <i class="fas fa-plane text-3xl text-purple-500"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Billets Événements</p>
                    <h4 class="text-2xl font-black text-gray-800">{{ $stats['event_tickets_sold'] }}</h4>
                </div>
                <i class="fas fa-ticket-alt text-3xl text-blue-500"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Packages Vendus</p>
                    <h4 class="text-2xl font-black text-gray-800">{{ $stats['package_bookings_total'] }}</h4>
                </div>
                <i class="fas fa-suitcase text-3xl text-green-500"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Note Moyenne</p>
                    <h4 class="text-2xl font-black text-gray-800">{{ $stats['average_rating'] ?? '0.0' }}/5</h4>
                </div>
                <i class="fas fa-star text-3xl text-amber-500"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Annulations</p>
                    <h4 class="text-2xl font-black text-gray-800">{{ $stats['cancelled_bookings'] }}</h4>
                </div>
                <i class="fas fa-times-circle text-3xl text-red-500"></i>
            </div>
        </div>
    </div>

    <!-- Alertes Importantes -->
    @if($alerts['low_stock_events'] > 0 || $alerts['low_stock_packages'] > 0 || $alerts['failed_payments'] > 0)
    <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-xl">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-4 mt-1"></i>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-red-800 mb-3">Alertes Importantes</h3>
                <div class="space-y-2">
                    @if($alerts['low_stock_events'] > 0)
                    <p class="text-red-700"><i class="fas fa-circle text-xs mr-2"></i>{{ $alerts['low_stock_events'] }} événement(s) avec stock faible</p>
                    @endif
                    @if($alerts['low_stock_packages'] > 0)
                    <p class="text-red-700"><i class="fas fa-circle text-xs mr-2"></i>{{ $alerts['low_stock_packages'] }} package(s) avec stock faible</p>
                    @endif
                    @if($alerts['failed_payments'] > 0)
                    <p class="text-red-700"><i class="fas fa-circle text-xs mr-2"></i>{{ $alerts['failed_payments'] }} paiement(s) échoué(s) cette semaine</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Graphique Revenus -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-gray-800">
                    <i class="fas fa-chart-line text-purple-600 mr-2"></i>
                    Évolution des Revenus
                </h3>
                <span class="text-sm text-gray-500">12 derniers mois</span>
            </div>
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Graphique Réservations -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-gray-800">
                    <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                    Évolution des Réservations
                </h3>
                <span class="text-sm text-gray-500">12 derniers mois</span>
            </div>
            <div class="h-80">
                <canvas id="bookingsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Graphiques Circulaires -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Réservations par Type -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h3 class="text-lg font-black text-gray-800 mb-6">
                <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                Par Type
            </h3>
            <div class="h-64">
                <canvas id="typeChart"></canvas>
            </div>
        </div>

        <!-- Réservations par Statut -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h3 class="text-lg font-black text-gray-800 mb-6">
                <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                Par Statut
            </h3>
            <div class="h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Top Destinations -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h3 class="text-lg font-black text-gray-800 mb-6">
                <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                Top Destinations
            </h3>
            <div class="space-y-3">
                @foreach($topDestinations as $dest)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-600 rounded-xl flex items-center justify-center text-white font-black text-sm">
                            {{ $dest->destination }}
                        </div>
                        <span class="font-semibold text-gray-700">{{ $dest->destination }}</span>
                    </div>
                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-bold">{{ $dest->count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Statistiques 7 Derniers Jours -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h3 class="text-xl font-black text-gray-800 mb-6">
            <i class="fas fa-calendar-week text-purple-600 mr-2"></i>
            Activité des 7 Derniers Jours
        </h3>
        <div class="h-80">
            <canvas id="dailyStatsChart"></canvas>
        </div>
    </div>

    <!-- Top Produits -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Événements -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h3 class="text-lg font-black text-gray-800 mb-6">
                <i class="fas fa-trophy text-amber-600 mr-2"></i>
                Top Événements
            </h3>
            <div class="space-y-4">
                @foreach($topEvents as $event)
                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-xl hover:bg-purple-50 transition-colors">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-amber-600 rounded-xl flex items-center justify-center text-white font-black">
                        {{ $loop->iteration }}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-800 text-sm">{{ Str::limit($event->title, 30) }}</h4>
                        <p class="text-xs text-gray-500">{{ $event->tickets_count ?? 0 }} billets vendus</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Packages -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h3 class="text-lg font-black text-gray-800 mb-6">
                <i class="fas fa-star text-green-600 mr-2"></i>
                Top Packages
            </h3>
            <div class="space-y-4">
                @foreach($topPackages as $package)
                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-xl hover:bg-green-50 transition-colors">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-600 to-blue-600 rounded-xl flex items-center justify-center text-white font-black">
                        {{ $loop->iteration }}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-800 text-sm">{{ Str::limit($package->title, 30) }}</h4>
                        <p class="text-xs text-gray-500">{{ $package->bookings_count ?? 0 }} réservations</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Utilisateurs Récents -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h3 class="text-lg font-black text-gray-800 mb-6">
                <i class="fas fa-users text-blue-600 mr-2"></i>
                Nouveaux Membres
            </h3>
            <div class="space-y-4">
                @foreach($recentUsers as $user)
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl hover:bg-blue-50 transition-colors">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-black text-lg">
                        {{ substr($user->first_name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-800 text-sm">{{ $user->first_name }} {{ $user->last_name }}</h4>
                        <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Réservations Récentes -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-6">
            <h3 class="text-xl font-black text-white">
                <i class="fas fa-list text-white mr-2"></i>
                Dernières Réservations
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">N° Réservation</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">Client</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">Montant</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">Statut</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentBookings as $booking)
                    <tr class="hover:bg-purple-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm font-bold text-purple-600">{{ $booking->booking_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-600 rounded-full flex items-center justify-center text-white font-black">
                                    {{ substr($booking->user->first_name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $booking->user->first_name ?? 'N/A' }} {{ $booking->user->last_name ?? '' }}</p>
                                    <p class="text-xs text-gray-500">{{ $booking->user->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-bold rounded-full
                                @if($booking->booking_type === 'flight') bg-blue-100 text-blue-800
                                @elseif($booking->booking_type === 'event') bg-purple-100 text-purple-800
                                @else bg-green-100 text-green-800
                                @endif">
                                <i class="fas 
                                    @if($booking->booking_type === 'flight') fa-plane
                                    @elseif($booking->booking_type === 'event') fa-calendar
                                    @else fa-suitcase
                                    @endif mr-1"></i>
                                {{ ucfirst($booking->booking_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-black text-gray-800">{{ number_format($booking->final_amount, 0, ',', ' ') }}</span>
                            <span class="text-xs text-gray-500">XOF</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-bold rounded-full
                                @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                @elseif($booking->status === 'pending') bg-amber-100 text-amber-800
                                @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $booking->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-bold rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="fas fa-eye mr-2"></i>Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 font-semibold">Aucune réservation récente</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="{{ route('admin.events.create') }}" class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white hover:scale-105 transition-transform">
            <i class="fas fa-plus-circle text-4xl mb-3"></i>
            <h4 class="text-lg font-black">Créer un Événement</h4>
            <p class="text-purple-100 text-sm mt-2">Ajouter un nouvel événement</p>
        </a>

        <a href="{{ route('admin.packages.create') }}" class="bg-gradient-to-br from-green-600 to-green-700 rounded-2xl shadow-xl p-6 text-white hover:scale-105 transition-transform">
            <i class="fas fa-plus-circle text-4xl mb-3"></i>
            <h4 class="text-lg font-black">Créer un Package</h4>
            <p class="text-green-100 text-sm mt-2">Ajouter un nouveau package</p>
        </a>

        <a href="{{ route('admin.bookings.index') }}" class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-xl p-6 text-white hover:scale-105 transition-transform">
            <i class="fas fa-list text-4xl mb-3"></i>
            <h4 class="text-lg font-black">Voir Réservations</h4>
            <p class="text-blue-100 text-sm mt-2">Gérer toutes les réservations</p>
        </a>

        <a href="{{ route('admin.users.index') }}" class="bg-gradient-to-br from-amber-600 to-amber-700 rounded-2xl shadow-xl p-6 text-white hover:scale-105 transition-transform">
            <i class="fas fa-users text-4xl mb-3"></i>
            <h4 class="text-lg font-black">Gérer Utilisateurs</h4>
            <p class="text-amber-100 text-sm mt-2">Voir tous les utilisateurs</p>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Configuration globale Chart.js
Chart.defaults.font.family = 'Montserrat, sans-serif';
Chart.defaults.font.weight = '600';

// Graphique Revenus
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($revenueData->pluck('month')) !!},
        datasets: [{
            label: 'Revenus (XOF)',
            data: {!! json_encode($revenueData->pluck('total')) !!},
            borderColor: '#9333EA',
            backgroundColor: 'rgba(147, 51, 234, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointRadius: 6,
            pointHoverRadius: 8,
            pointBackgroundColor: '#9333EA',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1F2937',
                titleColor: '#fff',
                bodyColor: '#fff',
                padding: 12,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        return context.parsed.y.toLocaleString() + ' XOF';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#F3F4F6' },
                ticks: {
                    callback: function(value) {
                        return (value / 1000).toFixed(0) + 'K';
                    }
                }
            },
            x: { grid: { display: false } }
        }
    }
});

// Graphique Réservations
const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
new Chart(bookingsCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($bookingsData->pluck('month')) !!},
        datasets: [{
            label: 'Réservations',
            data: {!! json_encode($bookingsData->pluck('total')) !!},
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: '#3B82F6',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#F3F4F6' }
            },
            x: { grid: { display: false } }
        }
    }
});

// Graphique Par Type
const typeCtx = document.getElementById('typeChart').getContext('2d');
new Chart(typeCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($bookingsByType->pluck('booking_type')->map(function($type) { return ucfirst($type); })) !!},
        datasets: [{
            data: {!! json_encode($bookingsByType->pluck('count')) !!},
            backgroundColor: ['#3B82F6', '#9333EA', '#10B981'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 15, font: { size: 12, weight: 'bold' } }
            }
        }
    }
});

// Graphique Par Statut
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($bookingsByStatus->pluck('status')->map(function($status) { return ucfirst($status); })) !!},
        datasets: [{
            data: {!! json_encode($bookingsByStatus->pluck('count')) !!},
            backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#6B7280'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position:
                position: 'bottom',
                labels: { padding: 15, font: { size: 12, weight: 'bold' } }
            }
        }
    }
});

// Graphique Activité 7 Jours
const dailyCtx = document.getElementById('dailyStatsChart').getContext('2d');
new Chart(dailyCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($dailyStats, 'date')) !!},
        datasets: [
            {
                label: 'Réservations',
                data: {!! json_encode(array_column($dailyStats, 'bookings')) !!},
                borderColor: '#9333EA',
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                yAxisID: 'y'
            },
            {
                label: 'Nouveaux Utilisateurs',
                data: {!! json_encode(array_column($dailyStats, 'users')) !!},
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                yAxisID: 'y'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                position: 'top',
                labels: { padding: 20, font: { size: 13, weight: 'bold' } }
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                beginAtZero: true,
                grid: { color: '#F3F4F6' }
            }
        }
    }
});

// Mise à jour en temps réel toutes les 30 secondes
setInterval(function() {
    fetch('{{ route("admin.dashboard.realtime") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('bookings-today').textContent = data.bookings_today;
            document.getElementById('revenue-today').textContent = data.revenue_today.toLocaleString();
            document.getElementById('pending-bookings').textContent = data.pending_bookings;
        })
        .catch(error => console.error('Erreur mise à jour:', error));
}, 30000);
</script>
@endpush
