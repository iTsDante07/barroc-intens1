@extends('components.layouts.app')

@section('title', 'Nieuwe Offerte')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nieuwe Offerte</h1>
                <p class="text-gray-600">Selecteer een klant en voeg producten toe</p>
            </div>
            <a href="{{ route('quotes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                ← Terug naar Overzicht
            </a>
        </div>
    </div>

    <form action="{{ route('quotes.store') }}" method="POST" id="quoteForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Klant Selectie -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Klant Selectie</h2>

                    <div class="mb-6">
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Klant *
                        </label>
                        <select name="customer_id" id="customer_id" required
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500">
                            <option value="">Selecteer een klant</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"
                                        data-company="{{ $customer->company_name }}"
                                        data-contact="{{ $customer->contact_name }}"
                                        data-email="{{ $customer->email }}"
                                        data-phone="{{ $customer->phone }}"
                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->company_name }} - {{ $customer->contact_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Klant Info (dynamisch) -->
                    <div id="customer-info" class="hidden">
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h3 class="font-medium text-gray-900 mb-2">Klantgegevens</h3>
                            <div class="space-y-2 text-sm">
                                <div>
                                    <span class="font-medium" id="customer-company"></span>
                                    <p class="text-gray-600" id="customer-contact"></p>
                                </div>
                                <div>
                                    <p id="customer-email" class="text-gray-600"></p>
                                    <p id="customer-phone" class="text-gray-600"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Offerte Details -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <h3 class="font-medium text-gray-900 mb-2">Offerte Details</h3>

                        <div class="mb-4">
                            <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-1">
                                Geldig tot *
                            </label>
                            <input type="date" name="valid_until" id="valid_until"
                                   value="{{ old('valid_until', now()->addDays(30)->format('Y-m-d')) }}"
                                   required
                                   class="w-full border border-gray-300 rounded px-3 py-2 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500">
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
                </div>
            </div>

            <!-- Producten -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold">Producten</h2>
                        <span class="text-sm text-gray-500">{{ $products->count() }} beschikbaar</span>
                    </div>

                    @if($products->count() > 0)
                    <div class="space-y-3" id="products-container">
                        @foreach($products as $product)
                        <div class="border border-gray-200 rounded p-4 product-item" data-product-id="{{ $product->id }}">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-3 flex-1">
                                    <input type="checkbox"
                                           id="product_{{ $product->id }}"
                                           class="h-5 w-5 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded mt-1 product-checkbox">

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
                                               id="quantity_{{ $product->id }}"
                                               value="1"
                                               min="1"
                                               max="{{ $product->stock }}"
                                               class="w-20 border border-gray-300 rounded px-2 py-1 text-center quantity-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden inputs for selected products only -->
                            <input type="hidden" name="products[{{ $product->id }}][selected]" value="0" class="selected-input">
                            <input type="hidden" name="products[{{ $product->id }}][product_id]" value="{{ $product->id }}" class="product-id-input">
                            <input type="hidden" name="products[{{ $product->id }}][quantity]" value="0" class="quantity-hidden-input">
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

                    <!-- Acties -->
                    @if($products->count() > 0)
                    <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end space-x-3">
                        <a href="{{ route('quotes.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 transition">
                            Annuleren
                        </a>
                        <button type="submit" class="bg-yellow-500 text-black px-6 py-2 rounded font-semibold hover:bg-yellow-600 transition disabled:opacity-50 disabled:cursor-not-allowed" id="submitBtn" disabled>
                            Offerte Maken
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

@if($products->count() > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerSelect = document.getElementById('customer_id');
    const customerInfo = document.getElementById('customer-info');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('quoteForm');

    // Toon klantgegevens bij selectie
    if (customerSelect) {
        customerSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption.value) {
                customerInfo.classList.remove('hidden');
                document.getElementById('customer-company').textContent = selectedOption.dataset.company;
                document.getElementById('customer-contact').textContent = selectedOption.dataset.contact;
                document.getElementById('customer-email').textContent = selectedOption.dataset.email;
                document.getElementById('customer-phone').textContent = selectedOption.dataset.phone;
            } else {
                customerInfo.classList.add('hidden');
            }
            updateSubmitButton();
        });

        // Toon klantgegevens als er al een geselecteerd is
        if (customerSelect.value) {
            customerSelect.dispatchEvent(new Event('change'));
        }
    }

    // Toon/verberg quantity controls en update hidden inputs
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const productId = this.id.replace('product_', '');
            const productItem = this.closest('.product-item');
            const quantityControl = productItem.querySelector('.quantity-control');
            const selectedInput = productItem.querySelector('.selected-input');
            const quantityHiddenInput = productItem.querySelector('.quantity-hidden-input');
            const quantityInput = productItem.querySelector('.quantity-input');

            if (this.checked) {
                quantityControl.classList.remove('hidden');
                productItem.classList.add('bg-yellow-50', 'border-yellow-300');
                selectedInput.value = '1';
                quantityHiddenInput.value = quantityInput.value || 1;
            } else {
                quantityControl.classList.add('hidden');
                productItem.classList.remove('bg-yellow-50', 'border-yellow-300');
                selectedInput.value = '0';
                quantityHiddenInput.value = '0';
                // Reset quantity naar 1
                quantityInput.value = 1;
            }

            updateTotals();
            updateSubmitButton();
        });
    });

    // Update hidden inputs when quantity changes
    quantityInputs.forEach(input => {
        input.addEventListener('input', function() {
            const productId = this.id.replace('quantity_', '');
            const productItem = this.closest('.product-item');
            const checkbox = productItem.querySelector('.product-checkbox');
            const quantityHiddenInput = productItem.querySelector('.quantity-hidden-input');

            if (checkbox.checked) {
                quantityHiddenInput.value = this.value;
                updateTotals();
            }
        });

        input.addEventListener('change', function() {
            const productItem = this.closest('.product-item');
            const checkbox = productItem.querySelector('.product-checkbox');
            const quantityHiddenInput = productItem.querySelector('.quantity-hidden-input');

            if (checkbox.checked) {
                quantityHiddenInput.value = this.value;
                updateTotals();
            }
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

                const price = parseFloat(priceElement.dataset.price);
                const quantity = parseInt(quantityInput.value) || 0;

                subtotal += price * quantity;
            }
        });

        const vat = subtotal * 0.21;
        const total = subtotal + vat;

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
        const hasCustomer = customerSelect ? customerSelect.value : false;
        const hasSelectedProducts = Array.from(checkboxes).some(checkbox => checkbox.checked);

        submitBtn.disabled = !hasCustomer || !hasSelectedProducts;
        if (!submitBtn.disabled) {
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    // Form validatie
    form.addEventListener('submit', function(e) {
        // Valideer klant
        if (!customerSelect.value) {
            e.preventDefault();
            alert('Selecteer een klant voor de offerte.');
            return;
        }

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
@endif
@endsection
