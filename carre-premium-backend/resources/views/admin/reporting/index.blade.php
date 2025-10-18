@extends('admin.layouts.app')

@section('title', 'Reporting Financier')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📊 Reporting Financier</h1>
            <p class="text-gray-600 mt-2">Analyse complète du chiffre d'affaires et des performances</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.reporting.export', 'pdf') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" 
               class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                <i class="fas fa-file-pdf"></i>
                Export PDF
            </a>
            <a href="{{ route('admin.reporting.export', 'excel') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </a>
        </div>
    </div>

    <!-- Filtres de période -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.reporting.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Période rapide</label>
                <select id="quickPeriod" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Personnalisé</option>
                    <option value="today">Aujourd'hui</option>
                    <option value="yesterday">Hier</option>
                    <option value="week">Cette semaine</option>
                    <option value="month">Ce mois</option>
                    <option value="year">Cette année</option>
                    <option value="last30">30 derniers jours</option>
                    <option value="last90">90 derniers jours</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- KPIs Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- CA Total -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Chiffre d'Affaires</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($kpis['total_revenue'], 0, ',', ' ') }} <span class="text-lg">XOF</span></h3>
                    @if(isset($trendAnalysis['change_percentage']))
                    <p class="text-sm mt-2">
                        @if($trendAnalysis['change_percentage'] > 0)
                            <i class="fas fa-arrow-up"></i> +{{ $trendAnalysis['change_percentage'] }}%
                        @elseif($trendAnalysis['change_percentage'] < 0)
                            <i class="fas fa-arrow-down"></i> {{ $trendAnalysis['change_percentage'] }}%
                        @else
                            <i class="fas fa-minus"></i> 0%
                        @endif
                        vs période précédente
                    </p>
                    @endif
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-dollar-sign text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Transactions -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Transactions</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($kpis['total_transactions']) }}</h3>
                    <p class="text-sm mt-2">{{ number_format($kpis['total_bookings']) }} réservations</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-receipt text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Panier Moyen -->
        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Panier Moyen</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($kpis['average_basket'], 0, ',', ' ') }} <span class="text-lg">XOF</span></h3>
                    <p class="text-sm mt-2">Par transaction</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-shopping-cart text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Taux de Conversion -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Taux de Conversion</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($kpis['conversion_rate'], 1) }}%</h3>
                    <p class="text-sm mt-2">{{ number_format($kpis['total_customers']) }} clients</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Évolution du CA -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📈 Évolution du Chiffre d'Affaires</h3>
            <canvas id="revenueChart" height="300"></canvas>
        </div>

        <!-- CA par Service -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">🎯 Répartition par Service</h3>
            <canvas id="serviceChart" height="300"></canvas>
        </div>
    </div>

    <!-- Top Clients -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">👥 Top 10 Clients</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CA Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Panier Moyen</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($topClients as $index => $client)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $client['name'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client['email'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-purple-600">
                            {{ number_format($client['total_spent'], 0, ',', ' ') }} XOF
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client['transaction_count'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($client['average_transaction'], 0, ',', ' ') }} XOF
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Statistiques par Service -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($revenueByService as $service)
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-bold text-gray-800">{{ $service['service'] }}</h4>
                <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">
                    {{ $service['percentage'] }}%
                </span>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">CA:</span>
                    <span class="font-bold text-purple-600">{{ number_format($service['total_revenue'], 0, ',', ' ') }} XOF</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Transactions:</span>
                    <span class="font-semibold">{{ number_format($service['transaction_count']) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Panier moyen:</span>
                    <span class="font-semibold">{{ number_format($service['average_amount'], 0, ',', ' ') }} XOF</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Données pour les graphiques
const revenueByPeriod = @json($revenueByPeriod);
const revenueByService = @json($revenueByService);

// Configuration des couleurs
const colors = {
    purple: 'rgb(147, 51, 234)',
    blue: 'rgb(59, 130, 246)',
    green: 'rgb(34, 197, 94)',
    yellow: 'rgb(234, 179, 8)',
    red: 'rgb(239, 68, 68)',
    gold: 'rgb(212, 175, 55)'
};

// Graphique d'évolution du CA
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: revenueByPeriod.map(item => item.period_label),
        datasets: [{
            label: 'Chiffre d\'Affaires (XOF)',
            data: revenueByPeriod.map(item => item.revenue),
            borderColor: colors.purple,
            backgroundColor: 'rgba(147, 51, 234, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'CA: ' + context.parsed.y.toLocaleString('fr-FR') + ' XOF';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('fr-FR') + ' XOF';
                    }
                }
            }
        }
    }
});

// Graphique par service (Camembert)
const serviceCtx = document.getElementById('serviceChart').getContext('2d');
new Chart(serviceCtx, {
    type: 'doughnut',
    data: {
        labels: revenueByService.map(item => item.service),
        datasets: [{
            data: revenueByService.map(item => item.total_revenue),
            backgroundColor: [
                colors.purple,
                colors.blue,
                colors.green,
                colors.yellow,
                colors.red,
                colors.gold
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed.toLocaleString('fr-FR');
                        const percentage = revenueByService[context.dataIndex].percentage;
                        return label + ': ' + value + ' XOF (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

// Gestion des périodes rapides
document.getElementById('quickPeriod').addEventListener('change', function() {
    const value = this.value;
    const today = new Date();
    let startDate, endDate;

    switch(value) {
        case 'today':
            startDate = endDate = today.toISOString().split('T')[0];
            break;
        case 'yesterday':
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            startDate = endDate = yesterday.toISOString().split('T')[0];
            break;
        case 'week':
            const weekStart = new Date(today);
            weekStart.setDate(today.getDate() - today.getDay());
            startDate = weekStart.toISOString().split('T')[0];
            endDate = today.toISOString().split('T')[0];
            break;
        case 'month':
            startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
            endDate = today.toISOString().split('T')[0];
            break;
        case 'year':
            startDate = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
            endDate = today.toISOString().split('T')[0];
            break;
        case 'last30':
            const last30 = new Date(today);
            last30.setDate(today.getDate() - 30);
            startDate = last30.toISOString().split('T')[0];
            endDate = today.toISOString().split('T')[0];
            break;
        case 'last90':
            const last90 = new Date(today);
            last90.setDate(today.getDate() - 90);
            startDate = last90.toISOString().split('T')[0];
            endDate = today.toISOString().split('T')[0];
            break;
        default:
            return;
    }

    document.querySelector('input[name="start_date"]').value = startDate;
    document.querySelector('input[name="end_date"]').value = endDate;
});
</script>
@endsection
