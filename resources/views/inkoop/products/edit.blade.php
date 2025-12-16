@extends('components.layouts.app')

@section('title', 'Product Bewerken - Inkoop')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <a href="{{ route('inkoop.products.index') }}" class="text-yellow-500 hover:text-yellow-600 mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Product Bewerken</h1>
            </div>

            <!-- Verwijder knop -->
            <form action="{{ route('inkoop.products.destroy', $product) }}" method="POST"
                  onsubmit="return confirm('Weet je zeker dat je dit product wilt verwijderen? Dit kan niet ongedaan gemaakt worden.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="text-red-600 hover:text-red-900 px-4 py-2 rounded-lg border border-red-200 hover:bg-red-50 transition font-medium flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Verwijderen
                </button>
            </form>
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

                    <!-- Categorie -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Categorie</label>
                        <select name="category"
                                id="category"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">Selecteer categorie</option>
                            @foreach(\App\Models\Product::CATEGORIES as $key => $label)
                                <option value="{{ $key }}" {{ old('category', $product->category) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Huidige Afbeelding -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Huidige Afbeelding</label>
                        <div class="flex items-start space-x-4">
                            @if($product->image)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="h-32 w-32 rounded-lg object-cover border border-gray-300">
                                    <a href="{{ route('inkoop.products.deleteImage', $product) }}"
                                       onclick="return confirm('Weet je zeker dat je deze afbeelding wilt verwijderen?')"
                                       class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </a>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <p>Huidige afbeelding</p>
                                    <p class="mt-1">Upload een nieuwe afbeelding om deze te vervangen.</p>
                                </div>
                            @else
                                <div class="h-32 w-32 bg-gray-200 rounded-lg flex items-center justify-center border border-gray-300">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <p>Geen afbeelding geüpload</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Nieuwe Afbeelding -->
                    <div class="md:col-span-2">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Nieuwe Afbeelding</label>
                        <input type="file"
                               name="image"
                               id="image"
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Maximaal 2MB. Toegestane formaten: JPG, PNG, GIF</p>
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
                        Opslaan
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Voorraad Update (optioneel) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Snelle Voorraad Aanpassing</h3>
            <form action="{{ route('inkoop.products.update-stock', $product) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" id="type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="in">Voorraad Toevoegen</option>
                            <option value="out">Voorraad Afvoeren</option>
                            <option value="adjustment">Aanpassing</option>
                        </select>
                    </div>
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Aantal</label>
                        <input type="number"
                               name="quantity"
                               id="quantity"
                               min="1"
                               value="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reden</label>
                        <input type="text"
                               name="reason"
                               id="reason"
                               placeholder="Bijv. nieuwe levering, verkoop, etc."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-semibold">
                        Voorraad Bijwerken
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Preview Script -->
<script>
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Optioneel: preview tonen
            const preview = document.getElementById('image-preview');
            if (!preview) {
                const previewDiv = document.createElement('div');
                previewDiv.id = 'image-preview';
                previewDiv.className = 'mt-2';
                previewDiv.innerHTML = `<img src="${e.target.result}" class="h-32 rounded-lg border border-gray-300">`;
                document.querySelector('[for="image"]').after(previewDiv);
            } else {
                preview.innerHTML = `<img src="${e.target.result}" class="h-32 rounded-lg border border-gray-300">`;
            }
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
