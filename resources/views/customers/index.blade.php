@extends('components.layouts.app')

@section('title', 'Klanten - Barroc Intens')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Klanten</h1>
                    <p class="text-gray-600 mt-1">Beheer klantrelaties</p>
                </div>
                <a href="{{ route('customers.create') }}"
                   class="bg-green-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-600 transition-colors text-sm flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Nieuwe Klant</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-gray-900">{{ $customers->count() }}</div>
                <div class="text-sm text-gray-600">Totaal</div>
            </div>
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $customers->where('bkr_approved', true)->count() }}</div>
                <div class="text-sm text-gray-600">BKR Goedgekeurd</div>
            </div>
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $customers->where('bkr_checked', false)->count() }}</div>
                <div class="text-sm text-gray-600">Te Controleren</div>
            </div>
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $customers->where('bkr_checked', true)->where('bkr_approved', false)->count() }}</div>
                <div class="text-sm text-gray-600">BKR Afgekeurd</div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="bg-white rounded-lg p-4 shadow-sm">
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                <div class="flex-1">
                    <input type="text"
                           placeholder="Zoek klant op naam, email of telefoon..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="">Alle BKR statussen</option>
                    <option value="approved">Goedgekeurd</option>
                    <option value="pending">In afwachting</option>
                    <option value="rejected">Afgekeurd</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Customers List -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if($customers->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($customers as $customer)
                <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <!-- Customer Avatar -->
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                {{ substr($customer->company_name, 0, 2) }}
                            </div>

                            <!-- Customer Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $customer->company_name }}</h3>
                                    @if($customer->bkr_checked)
                                        @if($customer->bkr_approved)
                                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">BKR ✓</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">BKR ✗</span>
                                        @endif
                                    @else
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">BKR ?</span>
                                    @endif
                                </div>
                                <p class="text-gray-600 text-sm">{{ $customer->contact_name }}</p>
                                <div class="flex items-center space-x-4 mt-1">
                                    <span class="text-gray-500 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $customer->email }}
                                    </span>
                                    <span class="text-gray-500 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        {{ $customer->phone }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('customers.show', $customer) }}"
                               class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('quotes.create.for.customer', $customer) }}"
                               class="bg-yellow-500 text-black p-2 rounded-lg hover:bg-yellow-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </a>
                            @if(!$customer->bkr_checked)
                            <form action="{{ route('customers.check-bkr', $customer) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Nog geen klanten</h3>
                <p class="text-gray-600 mb-6">Voeg je eerste klant toe om te beginnen.</p>
                <a href="{{ route('customers.create') }}" class="bg-green-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-600 transition-colors">
                    Voeg Klant Toe
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Tablet optimizations */
@media (max-width: 768px) {
    .text-lg {
        font-size: 1rem;
    }

    .p-4 {
        padding: 0.75rem;
    }
}

/* Touch-friendly */
button, a {
    -webkit-tap-highlight-color: transparent;
    min-height: 44px;
    min-width: 44px;
}
</style>
@endsection
