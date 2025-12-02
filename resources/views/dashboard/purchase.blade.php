@extends('components.layouts.app')

@section('title', 'Inkoop Dashboard - Barroc Intens')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Inkoop Dashboard</h1>
    <p class="text-gray-600">Welkom bij de inkoopafdeling, {{ auth()->user()->name }}</p>
</div>

<!-- Inkoop Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Laag voorraad -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-500 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Laag Voorraad</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $lowStockCount }}</p>
                <p class="text-xs text-gray-500">Producten onder {{ $lowStockThreshold }}</p>
            </div>
        </div>
    </div>

    <!-- Kritieke voorraad -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-orange-500 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Kritieke Voorraad</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $criticalStockCount }}</p>
                <p class="text-xs text-gray-500">Producten onder {{ $minimumStockThreshold }}</p>
            </div>
        </div>
    </div>

    <!-- Uitverkocht -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-gray-500 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Uitverkocht</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $outOfStockCount }}</p>
                <p class="text-xs text-gray-500">Geen voorraad beschikbaar</p>
            </div>
        </div>
    </div>

    <!-- Voorraad waarde -->
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
                <p class="text-xs text-gray-500">Totale waarde voorraad</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Laag voorraad producten -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Laag Voorraad Producten</h2>
                <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">
                    {{ $lowStockCount }} producten
                </span>
            </div>
        </div>
        <div class="p-6">
            @if($lowStockCount > 0)
                <div class="space-y-4">
                    @foreach($lowStockProducts as $product)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-red-50 transition-colors">
                        <div class="flex items-center space-x-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                     class="w-12 h-12 object-cover rounded-lg">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $product->name }}</h3>
                                <div class="flex items-center space-x-4 mt-1">
                                    <span class="text-sm text-gray-600">Huidig: {{ $product->stock }}</span>
                                    <span class="text-sm text-gray-600">Min: {{ $product->min_stock ?? $minimumStockThreshold }}</span>
                                    <span class="text-sm text-blue-600">
                                        @if($product->category && isset($categories[$product->category]))
                                            {{ $categories[$product->category] }}
                                        @elseif($product->category)
                                            {{ $product->category }}
                                        @else
                                            Geen categorie
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-end space-y-1">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $product->stock < $minimumStockThreshold ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $product->stock < $minimumStockThreshold ? 'Kritiek' : 'Laag' }}
                            </span>
                            <span class="text-xs text-gray-500">€{{ number_format($product->price, 2, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-600">Geen producten met lage voorraad</p>
                    <p class="text-sm text-gray-500 mt-1">Alle voorraden zijn op niveau</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recente producten -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Recente Producten</h2>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                    {{ $recentProducts->count() }} producten
                </span>
            </div>
        </div>
        <div class="p-6">
            @if($recentProducts->count() > 0)
                <div class="space-y-4">
                    @foreach($recentProducts as $product)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center space-x-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                     class="w-12 h-12 object-cover rounded-lg">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $product->name }}</h3>
                                <div class="flex items-center space-x-4 mt-1">
                                    <span class="text-sm text-gray-600">Voorraad: {{ $product->stock }}</span>
                                    <span class="text-sm text-gray-600">€{{ number_format($product->price, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-sm text-gray-500">
                                {{ $product->created_at->diffForHumans() }}
                            </span>
                            @if($product->category)
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded">
                                    @if(isset($categories[$product->category]))
                                        {{ $categories[$product->category] }}
                                    @else
                                        {{ $product->category }}
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p class="text-gray-600">Nog geen producten toegevoegd</p>
                    <p class="text-sm text-gray-500 mt-1">Producten worden hier getoond zodra ze zijn toegevoegd</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Voorraad analyse -->
<div class="bg-white rounded-lg shadow mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Voorraad Analyse</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Categorie distributie -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-700 mb-3">Categorieën</h3>
                <div class="space-y-3">
                    @foreach($categoryStats as $category)
                        @if($category['count'] > 0)
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-600">{{ $category['name'] }}</span>
                                <span class="text-sm font-medium">{{ $category['count'] }} producten</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    $percentage = $totalProducts > 0 ? ($category['count'] / $totalProducts) * 100 : 0;
                                @endphp
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="flex justify-between mt-1 text-xs text-gray-500">
                                <span>Laag: {{ $category['low_stock_count'] }}</span>
                                <span>Uit: {{ $category['out_of_stock_count'] }}</span>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Recente producten -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-700 mb-3">Recente Toevoegingen</h3>
                <div class="space-y-3">
                    @foreach($topSellingProducts as $product)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="w-8 h-8 object-cover rounded">
                            @else
                                <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <span class="text-sm text-gray-700 truncate">{{ Str::limit($product->name, 20) }}</span>
                                <p class="text-xs text-gray-500">
                                    @if($product->category && isset($categories[$product->category]))
                                        {{ $categories[$product->category] }}
                                    @elseif($product->category)
                                        {{ $product->category }}
                                    @else
                                        Geen categorie
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-medium">€{{ number_format($product->price, 2, ',', '.') }}</span>
                            <p class="text-xs text-gray-500">Voorraad: {{ $product->stock }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Voorraad status -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-700 mb-3">Voorraad Status</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Normale voorraad</span>
                        </div>
                        <span class="text-sm font-medium">{{ $totalProducts - $lowStockCount - $outOfStockCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Laag voorraad</span>
                        </div>
                        <span class="text-sm font-medium">{{ $lowStockCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Kritieke voorraad</span>
                        </div>
                        <span class="text-sm font-medium">{{ $criticalStockCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Uitverkocht</span>
                        </div>
                        <span class="text-sm font-medium">{{ $outOfStockCount }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
/* Aanvullende styling voor het dashboard */
.hover\:bg-red-50:hover, .hover\:bg-gray-50:hover {
    transition: all 0.3s ease;
}

.text-2xl.font-semibold {
    transition: color 0.3s ease;
}

.bg-red-100, .bg-yellow-100, .bg-green-100 {
    transition: background-color 0.3s ease;
}

/* Progress bar styling */
.w-full.bg-gray-200 {
    overflow: hidden;
}

.bg-blue-600.h-2.rounded-full {
    transition: width 0.5s ease-in-out;
}

/* Product card styling */
.object-cover {
    object-fit: cover;
}

/* Category badge styling */
.bg-gray-100.text-gray-800 {
    transition: all 0.2s ease;
}

.bg-gray-100.text-gray-800:hover {
    background-color: #e5e7eb;
    transform: scale(1.05);
}

/* Truncate text for long names */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 150px;
}
</style>
@endsection
