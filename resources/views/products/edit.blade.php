@extends('components.layouts.app')

@section('title', $product->name . ' Bewerken - Barroc Intens')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">{{ $product->name }} Bewerken</h1>
    <p class="text-gray-600">Wijzig de productgegevens</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Product Naam -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Product Naam *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-barroc-yellow focus:ring focus:ring-barroc-yellow focus:ring-opacity-50">
            </div>

            <!-- Prijs -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Prijs *</label>
                <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-barroc-yellow focus:ring focus:ring-barroc-yellow focus:ring-opacity-50">
            </div>

            <!-- Voorraad -->
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700">Voorraad *</label>
                <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', $product->stock) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-barroc-yellow focus:ring focus:ring-barroc-yellow focus:ring-opacity-50">
            </div>

            <!-- Beschrijving -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Beschrijving *</label>
                <textarea name="description" id="description" rows="4" required
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-barroc-yellow focus:ring focus:ring-barroc-yellow focus:ring-opacity-50">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('products.show', $product) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
                Annuleren
            </a>
            <button type="submit" class="bg-barroc-yellow text-black px-6 py-2 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
                Product Bijwerken
            </button>
        </div>
    </form>
</div>
@endsection
