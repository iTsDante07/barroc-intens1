@extends('components.layouts.app')

@section('title', 'Product Bewerken - Inkoop')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('inkoop.products.index') }}" class="text-yellow-500 hover:text-yellow-600 mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Product Bewerken</h1>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('inkoop.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Naam -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Productnaam *</label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $product->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Beschrijving -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Beschrijving *</label>
                        <textarea name="description"
                                  id="description"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                  required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prijs -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Prijs *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">€</span>
                            </div>
                            <input type="number"
                                   name="price"
                                   id="price"
                                   step="0.01"
                                   min="0"
                                   value="{{ old('price', $product->price) }}"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                   required>
                        </div>
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Huidige Voorraad -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Huidige Voorraad *</label>
                        <input type="number"
                               name="stock"
                               id="stock"
                               min="0"
                               value="{{ old('stock', $product->stock) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                               required>
                        @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Minimale Voorraad -->
                    <div>
                        <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-2">Minimale Voorraad *</label>
                        <input type="number"
                               name="min_stock"
                               id="min_stock"
                               min="0"
                               value="{{ old('min_stock', $product->min_stock) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                               required>
                        @error('min_stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Afbeelding -->
                    <div class="md:col-span-2">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Productafbeelding</label>
                        <input type="file"
                               name="image"
                               id="image"
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Maximaal 2MB. Toegestane formaten: JPG, PNG, GIF</p>

                        @if($product->image)
                            <div class="mt-4 flex items-center space-x-4">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-24 h-24 object-cover rounded-lg border">
                                <p class="text-sm text-gray-600">Huidige afbeelding</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('inkoop.products.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium">
                        Annuleren
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-yellow-500 text-black rounded-lg hover:bg-yellow-600 transition font-semibold">
                        Wijzigingen Opslaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
