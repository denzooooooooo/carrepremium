@extends('admin.layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Notifications</h1>
            <p class="text-gray-600 mt-2">Gérez vos notifications et alertes</p>
        </div>
        @if($notifications->where('is_read', false)->count() > 0)
        <form action="{{ route('admin.notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg flex items-center gap-2">
                <i class="fas fa-check-double"></i>
                Tout marquer comme lu
            </button>
        </form>
        @endif
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total</p>
                    <p class="text-3xl font-bold mt-2">{{ $notifications->count() }}</p>
                </div>
                <i class="fas fa-bell text-4xl text-blue-200"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">Non lues</p>
                    <p class="text-3xl font-bold mt-2">{{ $notifications->where('is_read', false)->count() }}</p>
                </div>
                <i class="fas fa-envelope text-4xl text-orange-200"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Lues</p>
                    <p class="text-3xl font-bold mt-2">{{ $notifications->where('is_read', true)->count() }}</p>
                </div>
                <i class="fas fa-envelope-open text-4xl text-green-200"></i>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <button onclick="filterNotifications('all')" class="filter-btn active px-4 py-2 rounded-lg bg-purple-100 text-purple-800 hover:bg-purple-200">
                Toutes
            </button>
            <button onclick="filterNotifications('unread')" class="filter-btn px-4 py-2 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200">
                Non lues
            </button>
            <button onclick="filterNotifications('read')" class="filter-btn px-4 py-2 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200">
                Lues
            </button>
        </div>
    </div>

    <!-- Liste des notifications -->
    <div class="space-y-4">
        @forelse($notifications as $notification)
        <div class="notification-item bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow {{ $notification->is_read ? 'read' : 'unread' }}" 
             data-status="{{ $notification->is_read ? 'read' : 'unread' }}">
            <div class="flex items-start gap-4">
                <!-- Icône -->
                <div class="flex-shrink-0">
                    @if(!$notification->is_read)
                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-bell text-purple-600 text-xl"></i>
                    </div>
                    @else
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-bell text-gray-400 text-xl"></i>
                    </div>
                    @endif
                </div>

                <!-- Contenu -->
                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 {{ !$notification->is_read ? 'font-bold' : '' }}">
                                {{ $notification->title_fr }}
                            </h3>
                            <p class="text-gray-600 mt-1">{{ $notification->message_fr }}</p>
                            <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
                                <span><i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}</span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                                    {{ ucfirst($notification->type) }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center gap-2">
                            @if(!$notification->is_read)
                            <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-purple-600 hover:text-purple-800" title="Marquer comme lu">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Aucune notification</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

<script>
function filterNotifications(status) {
    const items = document.querySelectorAll('.notification-item');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update button styles
    buttons.forEach(btn => {
        btn.classList.remove('active', 'bg-purple-100', 'text-purple-800');
        btn.classList.add('bg-gray-100', 'text-gray-800');
    });
    event.target.classList.remove('bg-gray-100', 'text-gray-800');
    event.target.classList.add('active', 'bg-purple-100', 'text-purple-800');
    
    // Filter items
    items.forEach(item => {
        if (status === 'all') {
            item.style.display = 'block';
        } else {
            if (item.dataset.status === status) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        }
    });
}
</script>
@endsection
