@extends('components.layouts.app')

@section('title', 'Offerte Maken - ' . $customer->company_name)

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nieuwe Offerte</h1>
                <p class="text-gray-600">Voor {{ $customer->company_name }}</p>
            </div>
            <a href="{{ route('customers.show', $customer) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                ← Terug
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Klant Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Klant</h2>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="font-medium">{{ $customer->company_name }}</p>
                        <p class="text-gray-600">{{ $customer->contact_name }}</p>
                    </div>
                    <div>
                        <p>{{ $customer->email }}</p>
                        <p>{{ $customer->phone }}</p>
                    </div>
                    <div>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Goedgekeurd</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Offerte Formulier -->
        <div class="lg:col-span-2">
            <form action="{{ route('quotes.store.for.customer', $customer) }}" method="POST" id="quoteForm">
                @csrf

                <!-- Basisgegevens -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4">Offerte Details</h2>

                    <div class="mb-4">
                        <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-1">
                            Geldig tot *
                        </label>
                        <input type="date" name="valid_until" id="valid_until"
                               value="{{ old('valid_until', now()->addDays(30)->format('Y-m-d')) }}"
                               required
                               class="w-full md:w-64 border border-gray-300 rounded px-3 py-2 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                            Notities
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="w-full border border-gray-300 rounded px-3 py-2 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500"
                                  placeholder="Optionele notities...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Producten -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold">Producten</h2>
                        <span class="text-sm text-gray-500">{{ $products->count() }} beschikbaar</span>
                    </div>

                    @if($products->count() > 0)
                    <div class="space-y-3" id="products-container">
                        @foreach($products as $product)
                        <div class="border border-gray-200 rounded p-4 product-item">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-3 flex-1">
                                    <input type="checkbox"
                                           name="products[{{ $product->id }}][selected]"
                                           value="1"
                                           data-product-id="{{ $product->id }}"
                                           class="h-5 w-5 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded mt-1 product-checkbox">

                                    <input type="hidden" name="products[{{ $product->id }}][product_id]" value="{{ $product->id }}">

                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-600 mb-2">{{ $product->description }}</p>
                                        <div class="flex items-center space-x-4">
                                            <span class="text-yellow-600 font-semibold product-price" data-price="{{ $product->price }}">
                                                €{{ number_format($product->price, 2, ',', '.') }}
                                            </span>
                                            <span class="text-xs text-gray-500">Voorraad: {{ $product->stock }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="quantity-control hidden ml-4">
                                    <div class="flex items-center space-x-2">
                                        <label class="text-sm text-gray-700">Aantal:</label>
                                        <input type="number"
                                               name="products[{{ $product->id }}][quantity]"
                                               value="1"
                                               min="1"
                                               max="{{ $product->stock }}"
                                               class="w-20 border border-gray-300 rounded px-2 py-1 text-center quantity-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Totaal Overzicht -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotaal:</span>
                                <span class="font-medium" id="subtotal">€0,00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">BTW (21%):</span>
                                <span class="font-medium" id="vat">€0,00</span>
                            </div>
                            <div class="flex justify-between text-lg border-t border-gray-200 pt-2">
                                <span class="font-semibold">Totaal:</span>
                                <span class="font-bold text-yellow-600" id="total">€0,00</span>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <p class="mb-4">Geen producten beschikbaar</p>
                        <a href="{{ route('products.create') }}" class="bg-yellow-500 text-black px-4 py-2 rounded text-sm hover:bg-yellow-600 transition">
                            Product Toevoegen
                        </a>
                    </div>
                    @endif
                </div>

                @if($products->count() > 0)
                <!-- Acties -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('customers.show', $customer) }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 transition">
                        Annuleren
                    </a>
                    <button type="submit" class="bg-yellow-500 text-black px-6 py-2 rounded font-semibold hover:bg-yellow-600 transition disabled:opacity-50 disabled:cursor-not-allowed" id="submitBtn" disabled>
                        Offerte Maken
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

@if($products->count() > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const submitBtn = document.getElementById('submitBtn');

    // Toon/verberg quantity controls
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const productItem = this.closest('.product-item');
            const quantityControl = productItem.querySelector('.quantity-control');

            if (this.checked) {
                quantityControl.classList.remove('hidden');
                productItem.classList.add('bg-yellow-50', 'border-yellow-300');
            } else {
                quantityControl.classList.add('hidden');
                productItem.classList.remove('bg-yellow-50', 'border-yellow-300');
                // Reset quantity naar 1
                const quantityInput = productItem.querySelector('.quantity-input');
                quantityInput.value = 1;
            }

            updateTotals();
            updateSubmitButton();
        });
    });

    // Update totalen
    function updateTotals() {
        let subtotal = 0;

        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const productItem = checkbox.closest('.product-item');
                const quantityInput = productItem.querySelector('.quantity-input');
                const priceElement = productItem.querySelector('.product-price');

                // Gebruik de data-price attribute in plaats van tekst te parsen
                const price = parseFloat(priceElement.dataset.price);
                const quantity = parseInt(quantityInput.value) || 0;

                subtotal += price * quantity;
            }
        });

        const vat = subtotal * 0.21;
        const total = subtotal + vat;

        // Formatteer bedragen
        document.getElementById('subtotal').textContent = formatPrice(subtotal);
        document.getElementById('vat').textContent = formatPrice(vat);
        document.getElementById('total').textContent = formatPrice(total);
    }

    // Formatteer prijs naar Nederlands formaat
    function formatPrice(amount) {
        if (isNaN(amount)) return '€0,00';

        return '€' + amount.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Update submit button state
    function updateSubmitButton() {
        const hasSelectedProducts = Array.from(checkboxes).some(checkbox => checkbox.checked);

        submitBtn.disabled = !hasSelectedProducts;
        if (hasSelectedProducts) {
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    // Event listeners voor quantity inputs
    quantityInputs.forEach(input => {
        input.addEventListener('input', updateTotals);
        input.addEventListener('change', updateTotals);
    });

    // Form validatie
    document.getElementById('quoteForm').addEventListener('submit', function(e) {
        const selectedProducts = Array.from(checkboxes).filter(cb => cb.checked);

        if (selectedProducts.length === 0) {
            e.preventDefault();
            alert('Selecteer minimaal één product voor de offerte.');
            return;
        }

        // Valideer hoeveelheden
        let isValid = true;
        let errorMessage = '';

        selectedProducts.forEach(checkbox => {
            const productItem = checkbox.closest('.product-item');
            const quantityInput = productItem.querySelector('.quantity-input');
            const productName = productItem.querySelector('h3').textContent;
            const quantity = parseInt(quantityInput.value);
            const maxStock = parseInt(quantityInput.getAttribute('max'));

            if (quantity < 1) {
                isValid = false;
                errorMessage = `Hoeveelheid voor "${productName}" moet minimaal 1 zijn.`;
            } else if (quantity > maxStock) {
                isValid = false;
                errorMessage = `Hoeveelheid voor "${productName}" mag niet meer zijn dan ${maxStock} (beschikbare voorraad).`;
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
            return;
        }

        // Loading state
        submitBtn.disabled = true;
        submitBtn.textContent = 'Aanmaken...';
    });

    // Initialiseer
    updateSubmitButton();
});
</script>
@endif

<style>
.quantity-control {
    transition: all 0.2s ease-in-out;
}

.product-item {
    transition: all 0.2s ease-in-out;
}

input[type="number"] {
    -moz-appearance: textfield;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
@endsection
