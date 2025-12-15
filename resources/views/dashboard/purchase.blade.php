@extends('components.layouts.app')

@section('title', 'Inkoop Dashboard - Barroc Intens')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Inkoop Dashboard</h1>
    <p class="text-gray-600">Welkom terug, {{ auth()->user()->name }}</p>
</div>

<!-- Purchase Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Totaal Producten</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $totalProducts }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-500 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.7 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Lage Voorraad</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $lowStockCount }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-500 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Uit Voorraad</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $outOfStockCount }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Voorraad Waarde</p>
                <p class="text-2xl font-semibold text-gray-900">€{{ number_format($totalStockValue, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Low Stock Products -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Lage Voorraad</h2>
            <a href="{{ route('inkoop.products.low-stock') }}" class="text-sm text-blue-600 hover:text-blue-800">
                Bekijk alles →
            </a>
        </div>
        <div class="p-6">
            @if($lowStockProducts->count() > 0)
                <div class="space-y-4">
                    @foreach($lowStockProducts->take(5) as $product)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-lg mr-4">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600">Voorraad: {{ $product->stock }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Laag
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>Geen producten met lage voorraad</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Products -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Recente Producten</h2>
            <a href="{{ route('inkoop.products.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                Bekijk alles →
            </a>
        </div>
        <div class="p-6">
            @if($recentProducts->count() > 0)
                <div class="space-y-4">
                    @foreach($recentProducts as $product)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-lg mr-4">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600">€{{ number_format($product->price, 2, ',', '.') }} | Voorraad: {{ $product->stock }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            {{ $product->stock <= 0 ? 'bg-red-100 text-red-800' :
                               ($product->stock < 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                            {{ $product->stock <= 0 ? 'Uit' : ($product->stock < 10 ? 'Laag' : 'OK') }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>Nog geen producten</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Out of Stock Products -->
@if($outOfStockProducts->count() > 0)
<div class="mt-8 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Uit Voorraad</h2>
        <a href="{{ route('inkoop.products.low-stock') }}" class="text-sm text-red-600 hover:text-red-800">
            Dringend aanvullen →
        </a>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($outOfStockProducts->take(6) as $product)
            <div class="p-4 border border-red-200 rounded-lg bg-red-50">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-800">{{ $product->name }}</h3>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                        Uitverkocht
                    </span>
                </div>
                <p class="text-sm text-gray-600 mb-2">Artikelnummer: {{ $product->sku ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600 mb-3">Prijs: €{{ number_format($product->price, 2, ',', '.') }}</p>
                <a href="{{ route('inkoop.products.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Bekijken
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="mt-8 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Snelle Acties</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Producten Beheren -->
            <a href="{{ route('inkoop.products.index') }}" class="flex items-center p-6 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Producten Beheren</h3>
                    <p class="text-sm text-gray-600">Alle producten bekijken en bewerken</p>
                </div>
            </a>


            <!-- Lage Voorraad -->
            <a href="{{ route('inkoop.products.low-stock') }}" class="flex items-center p-6 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="p-3 bg-yellow-100 text-yellow-600 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.7 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Lage Voorraad</h3>
                    <p class="text-sm text-gray-600">Producten die bijna op zijn</p>
                </div>
            </a>

            <!-- Dashboard Terug -->
            <a href="{{ route('dashboard') }}" class="flex items-center p-6 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="p-3 bg-green-100 text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Dashboard</h3>
                    <p class="text-sm text-gray-600">Terug naar dashboard overzicht</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
