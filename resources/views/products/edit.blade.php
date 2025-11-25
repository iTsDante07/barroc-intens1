@extends('components.layouts.app')

@section('title', $product->name . ' Bewerken - Barroc Intens')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">{{ $product->name }} Bewerken</h1>
    <p class="text-gray-600">Wijzig de productgegevens</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Product Naam -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Product Naam *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prijs -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Prijs *</label>
                <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Voorraad -->
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700">Voorraad *</label>
                <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', $product->stock) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                @error('stock')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Afbeelding -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Product Afbeelding</label>

                <!-- Huidige afbeelding -->
                @if($product->image)
                    <div class="mb-3">
                        <p class="text-sm text-gray-600 mb-2">Huidige afbeelding:</p>
                        <div class="w-32 h-32 border rounded-lg overflow-hidden">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('products.deleteImage', $product) }}"
                               class="text-red-600 hover:text-red-800 text-sm font-medium"
                               onclick="return confirm('Weet je zeker dat je deze afbeelding wilt verwijderen?')">
                                Afbeelding verwijderen
                            </a>
                        </div>
                    </div>
                @else
                    <div class="mb-3 text-sm text-gray-500">
                        Geen afbeelding geüpload
                    </div>
                @endif

                <input type="file" name="image" id="image" accept="image/*"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Maximaal 2MB. Toegestane formaten: JPG, PNG, GIF</p>
            </div>

            <!-- Image Preview -->
            <div class="md:col-span-2 hidden" id="imagePreviewContainer">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nieuwe afbeelding voorbeeld:</label>
                <div class="w-48 h-48 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden">
                    <img id="imagePreview" src="#" alt="Voorbeeld" class="w-full h-full object-cover hidden">
                </div>
            </div>

            <!-- Beschrijving -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Beschrijving *</label>
                <textarea name="description" id="description" rows="4" required
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('products.show', $product) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
                Annuleren
            </a>
            <button type="submit" class="bg-yellow-500 text-black px-6 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
                Product Bijwerken
            </button>
        </div>
    </form>
</div>

<!-- Success Message -->
@if(session('success'))
    <div class="mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<script>
    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('imagePreviewContainer');

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                container.classList.remove('hidden');
            }

            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
            container.classList.add('hidden');
        }
    });
</script>
@endsection
