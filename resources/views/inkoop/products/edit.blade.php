@extends('components.layouts.app')

@section('title', 'Product Bewerken - ' . $product->name)

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Product Bewerken</h1>
        <p class="text-gray-600">{{ $product->name }}</p>
    </div>

    <form action="{{ route('inkoop.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow p-6">
            <!-- Basis Informatie -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Productnaam *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Prijs *</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500" required>
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Huidige voorraad *</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500" required>
                    @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-1">Minimum voorraad *</label>
                    <input type="number" name="min_stock" id="min_stock" value="{{ old('min_stock', $product->min_stock) }}" min="0"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500" required>
                    @error('min_stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Beschrijving -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Beschrijving *</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full border border-gray-300 rounded px-3 py-2 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500" required>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>


            <!-- Afbeelding -->
            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Product Afbeelding</label>
                @if($product->image)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded">
                    <a href="{{ route('products.deleteImage', $product) }}" class="text-red-600 text-sm ml-2" onclick="return confirm('Afbeelding verwijderen?')">
                        Verwijderen
                    </a>
                </div>
                @endif
                <input type="file" name="image" id="image"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500">
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Acties -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                <a href="{{ route('inkoop.products.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 transition">
                    Annuleren
                </a>
                <div class="space-x-3">
                    <button type="submit" class="bg-yellow-500 text-black px-6 py-2 rounded font-semibold hover:bg-yellow-600 transition">
                        Product Bijwerken
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Debug JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');

    form.addEventListener('submit', function(e) {
        console.log('Form submitted');
        console.log('Form data:', new FormData(form));

        setTimeout(() => {
            const errors = document.querySelectorAll('.text-red-500');
            if (errors.length > 0) {
                console.log('Validation errors found:', errors.length);
                errors.forEach(error => console.log(error.textContent));
            }
        }, 100);
    });
});
</script>
@endsection
