@extends('components.layouts.app')

@section('title', 'Producten - Barroc Intens')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Producten</h1>
        <p class="text-gray-600">Beheer alle koffiemachines en producten</p>
    </div>
    <a href="{{ route('products.create') }}" class="bg-barroc-yellow text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
        Nieuw Product
    </a>
</div>

<!-- Producten Grid -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Productcatalogus</h2>
    </div>

    <div class="p-6">
        @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="h-48 bg-gray-100 rounded-lg mb-4 flex items-center justify-center">
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover rounded-lg">
                    @else
                    <div class="text-gray-400 text-center">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm">Geen afbeelding</span>
                    </div>
                    @endif
                </div>

                <h3 class="font-semibold text-lg text-gray-800">{{ $product->name }}</h3>
                <p class="text-gray-600 text-sm mt-1">{{ Str::limit($product->description, 100) }}</p>

                <div class="mt-4 flex justify-between items-center">
                    <div>
                        <span class="text-barroc-yellow font-bold text-lg">€{{ number_format($product->price, 2, ',', '.') }}</span>
                        <span class="ml-2 text-sm text-gray-500">Voorraad: {{ $product->stock }}</span>
                    </div>

                    <div class="flex space-x-2">
                        <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:text-blue-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('products.edit', $product) }}" class="text-green-600 hover:text-green-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-8V4a1 1 0 00-1-1h-2a1 1 0 00-1 1v1M9 7h6"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Geen producten gevonden</h3>
            <p class="text-gray-500 mb-4">Er zijn nog geen producten toegevoegd aan het systeem.</p>
            <a href="{{ route('products.create') }}" class="bg-barroc-yellow text-black px-6 py-2 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
                Voeg eerste product toe
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
