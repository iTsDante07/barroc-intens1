@extends('components.layouts.app')

@section('title', 'Management Dashboard - Barroc Intens')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Management Dashboard</h1>
        <p class="text-gray-600 mt-2">Welkom terug, {{ auth()->user()->name }}</p>
        <div class="mt-2 flex items-center text-sm text-gray-500">
            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">
                Management
            </span>
            <span class="mx-2">•</span>
            <span>Overzicht van alle bedrijfsactiviteiten</span>
        </div>
    </div>

    <!-- Main Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
        <!-- Users Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-purple-50">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Gebruikers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalUsers ?? $users->count() }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500">Actieve systeemgebruikers</p>
            </div>
        </div>

        <!-- Products Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-blue-50">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Producten</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalProducts ?? $products->count() }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500">Totaal producten in catalogus</p>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-green-50">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Klanten</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalCustomers ?? $customers->count() }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500">Geregistreerde klanten</p>
            </div>
        </div>
    </div>

    <!-- Secondary Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Quotes Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Offertes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalQuotes ?? 0 }}</p>
                </div>
                <div class="p-3 rounded-lg bg-orange-50">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs text-gray-500">Totaal aantal offertes</p>
            </div>
        </div>

        <!-- Conversion Rate Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Conversie Ratio</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($quoteConversionRate ?? 0, 1) }}%</p>
                </div>
                <div class="p-3 rounded-lg bg-cyan-50">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs text-gray-500">Offerte naar factuur ratio</p>
            </div>
        </div>

        <!-- Maintenance Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Onderhoudstaken</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalMaintenanceTasks ?? 0 }}</p>
                </div>
                <div class="p-3 rounded-lg bg-yellow-50">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs text-gray-500">Totaal onderhoudstaken</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-xl border border-purple-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Snelle Acties</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('users.index') }}" class="bg-white rounded-lg p-4 border border-gray-200 hover:border-purple-300 hover:shadow-md transition-all flex items-center space-x-3">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <span class="font-medium text-gray-700">Gebruikers</span>
            </a>

            <a href="{{ route('products.index') }}" class="bg-white rounded-lg p-4 border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all flex items-center space-x-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <span class="font-medium text-gray-700">Producten</span>
            </a>

            <a href="{{ route('customers.index') }}" class="bg-white rounded-lg p-4 border border-gray-200 hover:border-green-300 hover:shadow-md transition-all flex items-center space-x-3">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="font-medium text-gray-700">Klanten</span>
            </a>

            <a href="{{ route('invoices.index') }}" class="bg-white rounded-lg p-4 border border-gray-200 hover:border-emerald-300 hover:shadow-md transition-all flex items-center space-x-3">
                <div class="p-2 bg-emerald-100 rounded-lg">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                    </svg>
                </div>
                <span class="font-medium text-gray-700">Financieel</span>
            </a>
        </div>
    </div>

    <!-- Department Overview -->
    @if(isset($departmentStats) && !empty($departmentStats))
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Afdeling Overzicht</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($departmentStats as $deptName => $stats)
            <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-semibold text-gray-900 capitalize">{{ $deptName }}</h4>
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                        <span class="text-sm font-semibold text-gray-600">{{ $stats['users'] }}</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Gebruikers:</span>
                        <span class="font-medium text-gray-900">{{ $stats['users'] }}</span>
                    </div>
                    @if(isset($stats['quotes']))
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Offertes:</span>
                        <span class="font-medium text-gray-900">{{ $stats['quotes'] }}</span>
                    </div>
                    @endif
                    @if(isset($stats['invoices']))
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Facturen:</span>
                        <span class="font-medium text-gray-900">{{ $stats['invoices'] }}</span>
                    </div>
                    @endif
                    @if(isset($stats['tasks']))
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Taken:</span>
                        <span class="font-medium text-gray-900">{{ $stats['tasks'] }}</span>
                    </div>
                    @endif
                    @if(isset($stats['products']))
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Producten:</span>
                        <span class="font-medium text-gray-900">{{ $stats['products'] }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- JavaScript voor interactiviteit -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Voeg hover effecten toe aan cards
    const cards = document.querySelectorAll('.hover\\:shadow-md');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection
