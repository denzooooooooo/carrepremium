@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Utilisateurs</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_users']) }}</h3>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl text-blue-500"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                <span class="text-green-500 font-semibold">12%</span>
                <span class="text-gray-500 ml-2">ce mois</span>
            </div>
        </div>
        
        <!-- Total Bookings -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Réservations</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_bookings']) }}</h3>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-2xl text-primary"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-yellow-500 font-semibold">{{ $stats['pending_bookings'] }}</span>
                <span class="text-gray-500 ml-2">en attente</span>
            </div>
        </div>
        
        <!-- Total Revenue -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Revenu Total</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_revenue'], 0, ',', ' ') }}</h3>
                    <p class="text-xs text-gray-500">XOF</p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-2xl text-green-500"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-gray-700 font-semibold">{{ number_format($stats['monthly_revenue'], 0, ',', ' ') }}</span>
                <span class="text-gray-500 ml-2">ce mois</span>
            </div>
        </div>
        
        <!-- Pending Reviews -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-secondary hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Avis en attente</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($stats['pending_reviews']) }}</h3>
                </div>
                <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-star text-2xl text-secondary"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-sm text-primary hover:text-purple-700 font-medium">
                    Voir les avis <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Products Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold">Vols</h4>
                <i class="fas fa-plane text-3xl opacity-50"></i>
            </div>
            <p class="text-4xl font-bold mb-2">{{ number_format($stats['total_flights']) }}</p>
            <p class="text-blue-100 text-sm">Total des vols disponibles</p>
        </div>
        
        <div class="bg-gradient-to-br from-primary to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold">Événements</h4>
                <i class="fas fa-calendar-alt text-3xl opacity-50"></i>
            </div>
            <p class="text-4xl font-bold mb-2">{{ number_format($stats['total_events']) }}</p>
            <p class="text-purple-100 text-sm">Événements sportifs & culturels</p>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold">Packages</h4>
                <i class="fas fa-suitcase text-3xl opacity-50"></i>
            </div>
            <p class="text-4xl font-bold mb-2">{{ number_format($stats['total_packages']) }}</p>
            <p class="text-green-100 text-sm">Packages touristiques</p>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-montserrat">
                <i class="fas fa-chart-line text-primary mr-2"></i>
                Évolution du Chiffre d'Affaires
            </h3>
            <div class="h-64 flex items-center justify-center">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        
        <!-- Bookings by Type -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-montserrat">
                <i class="fas fa-chart-pie text-primary mr-2"></i>
                Réservations par Type
            </h3>
            <div class="h-64 flex items-center justify-center">
                <canvas id="bookingsChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Bookings -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 font-montserrat">
                <i class="fas fa-clock text-primary mr-2"></i>
                Réservations Récentes
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">N° Réservation</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentBookings as $booking)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm font-semibold text-primary">{{ $booking->booking_number }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-primary to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                    {{ substr($booking->user->first_name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $booking->user->first_name ?? 'N/A' }} {{ $booking->user->last_name ?? '' }}</p>
                                    <p class="text-xs text-gray-500">{{ $booking->user->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
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
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-800">{{ number_format($booking->final_amount, 0, ',', ' ') }} XOF</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $booking->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-primary hover:text-purple-700 font-medium">
                                <i class="fas fa-eye mr-1"></i>Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Aucune réservation récente</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueData->pluck('month')) !!},
            datasets: [{
                label: 'Chiffre d\'Affaires (XOF)',
                data: {!! json_encode($revenueData->pluck('total')) !!},
                borderColor: '#9333EA',
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' XOF';
                        }
                    }
                }
            }
        }
    });
    
    // Bookings Chart
    const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
    new Chart(bookingsCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($bookingsByType->pluck('booking_type')) !!},
            datasets: [{
                data: {!! json_encode($bookingsByType->pluck('count')) !!},
                backgroundColor: [
                    '#3B82F6',
                    '#9333EA',
                    '#10B981'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
