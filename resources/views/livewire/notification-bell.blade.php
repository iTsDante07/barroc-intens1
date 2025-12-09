<div x-data="{ open: false }" class="relative" wire:poll.1s="loadNotifications">
    <button @click="open = !open" class="relative">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl overflow-hidden z-20" style="display: none;">
        <div class="py-2 px-4 text-sm font-semibold text-gray-700 border-b">
            Notificaties
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <p class="text-sm text-gray-800">{!! $notification->data['message'] !!}</p>
                        @if(is_null($notification->read_at))
                            <button wire:click="markAsRead('{{ $notification->id }}')" class="text-blue-500 hover:text-blue-700 text-xs">
                                Markeer als gelezen
                            </button>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                    </p>
                </div>
            @empty
                <div class="p-4 text-sm text-gray-500">
                    Geen nieuwe notificaties.
                </div>
            @endforelse
        </div>
    </div>
</div>
