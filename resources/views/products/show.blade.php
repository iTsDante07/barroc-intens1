@extends('components.layouts.app')

@section('title', $product->name . ' - Barroc Intens')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">{{ $product->name }}</h1>
        <p class="text-gray-600">Product details</p>
    </div>
    <div class="flex space-x-4">
        <a href="{{ route('products.edit', $product) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition-colors">
            Bewerken
        </a>
        <a href="{{ route('products.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
            Terug
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Product Afbeelding -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="h-80 bg-gray-100 rounded-lg flex items-center justify-center">
            @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover rounded-lg">
            @else
            <div class="text-gray-400 text-center">
                <svg class="w-20 h-20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>Geen afbeelding beschikbaar</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Product Details -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Product Informatie</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-600">Naam</label>
                <p class="mt-1 text-lg text-gray-900">{{ $product->name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Beschrijving</label>
                <p class="mt-1 text-gray-700">{{ $product->description }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Prijs</label>
                <p class="mt-1 text-2xl font-bold text-barroc-yellow">€{{ number_format($product->price, 2, ',', '.') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Voorraad</label>
                <p class="mt-1 text-lg {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $product->stock }} stuks
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Aangemaakt op</label>
                <p class="mt-1 text-gray-700">{{ $product->created_at->format('d-m-Y H:i') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Laatst bijgewerkt</label>
                <p class="mt-1 text-gray-700">{{ $product->updated_at->format('d-m-Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
