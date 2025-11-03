@extends('components.layouts.app')

@section('title', 'Nieuwe Offerte - Barroc Intens')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Nieuwe Offerte</h1>
    <p class="text-gray-600">Maak een nieuwe offerte voor een klant</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('quotes.store') }}" method="POST" id="quoteForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Klant Selectie -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Klant Selectie</h2>

                <div class="mb-6">
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Klant *</label>
                    <select name="customer_id" id="customer_id" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                        <option value="">Selecteer een klant</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" data-address="{{ $customer->address }}, {{ $customer->postal_code }} {{ $customer->city }}">
                                {{ $customer->company_name }} - {{ $customer->contact_name }}
                            </option>
                        @endforeach
                    </select>
                    <div id="customer-address" class="mt-2 text-sm text-gray-600 hidden"></div>
                </div>

                <!-- Geldig tot -->
                <div class="mb-6">
                    <label for="valid_until" class="block text-sm font-medium text-gray-700">Geldig tot *</label>
                    <input type="date" name="valid_until" id="valid_until" required
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                </div>

                <!-- Notities -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notities</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"
                              placeholder="Optionele notities voor de offerte..."></textarea>
                </div>

                <!-- Algemene Voorwaarden -->
                <div class="mb-6">
                    <label for="terms" class="block text-sm font-medium text-gray-700">Algemene Voorwaarden</label>
                    <textarea name="terms" id="terms" rows="4"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"
                              placeholder="Standaard betalingsvoorwaarden: 30 dagen netto.">Standaard betalingsvoorwaarden: 30 dagen netto.</textarea>
                </div>
            </div>

            <!-- Producten -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Producten</h2>

                <div id="products-container">
                    <!-- Producten worden hier dynamisch toegevoegd -->
                </div>

                <!-- Product Toevoegen -->
                <div class="mb-6">
                    <label for="product_select" class="block text-sm font-medium text-gray-700">Product toevoegen</label>
                    <div class="flex space-x-2 mt-1">
                        <select id="product_select"
                                class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                            <option value="">Selecteer een product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-name="{{ $product->name }}">
                                    {{ $product->name }} - €{{ number_format($product->price, 2, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" id="add-product"
                                class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition-colors">
                            Toevoegen
                        </button>
                    </div>
                </div>

                <!-- Totaal Overzicht -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Totaal Overzicht</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Subtotaal:</span>
                            <span id="subtotal">€0,00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>BTW (21%):</span>
                            <span id="vat">€0,00</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-2 font-semibold">
                            <span>Totaal:</span>
                            <span id="total">€0,00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-8 flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('quotes.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
                Annuleren
            </a>
            <button type="submit" class="bg-yellow-500 text-black px-6 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
                Offerte Opslaan
            </button>
        </div>
    </form>
</div>

<!-- Product Template (Hidden) -->
<template id="product-template">
    <div class="product-item border border-gray-200 rounded-lg p-4 mb-4">
        <div class="flex justify-between items-start mb-2">
            <div>
                <h4 class="font-semibold text-gray-800 product-name"></h4>
                <p class="text-sm text-gray-600 product-price"></p>
            </div>
            <button type="button" class="remove-product text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
        <div class="flex items-center space-x-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Aantal</label>
                <input type="number" name="products[][quantity]" min="1" value="1"
                       class="quantity-input mt-1 w-20 border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Prijs per stuk</label>
                <input type="text" class="unit-price-input mt-1 w-full bg-gray-50 border-gray-300 rounded-md shadow-sm" readonly>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Totaal</label>
                <input type="text" class="total-price-input mt-1 w-full bg-gray-50 border-gray-300 rounded-md shadow-sm" readonly>
            </div>
        </div>
        <input type="hidden" name="products[][product_id]" class="product-id">
    </div>
</template>

{{-- ... bestaande HTML code ... --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsContainer = document.getElementById('products-container');
    const productSelect = document.getElementById('product_select');
    const addProductBtn = document.getElementById('add-product');
    const productTemplate = document.getElementById('product-template');
    const customerSelect = document.getElementById('customer_id');
    const customerAddress = document.getElementById('customer-address');

    let productCounter = 0;
    const addedProducts = new Set();

    // Toon klant adres
    if (customerSelect && customerAddress) {
        customerSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const address = selectedOption.getAttribute('data-address');
            if (address) {
                customerAddress.textContent = address;
                customerAddress.classList.remove('hidden');
            } else {
                customerAddress.classList.add('hidden');
            }
        });
    }

    // Voeg product toe
    if (addProductBtn && productSelect) {
        addProductBtn.addEventListener('click', function() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const productId = selectedOption.value;
            const productName = selectedOption.getAttribute('data-name');
            const productPrice = selectedOption.getAttribute('data-price');

            if (!productId) {
                alert('Selecteer eerst een product');
                return;
            }

            if (addedProducts.has(parseInt(productId))) {
                alert('Dit product is al toegevoegd aan de offerte');
                return;
            }

            const productClone = productTemplate.content.cloneNode(true);
            const productElement = productClone.querySelector('.product-item');

            // Vul de template
            productElement.querySelector('.product-name').textContent = productName;
            productElement.querySelector('.product-price').textContent = `€${parseFloat(productPrice).toFixed(2).replace('.', ',')} per stuk`;
            productElement.querySelector('.unit-price-input').value = `€${parseFloat(productPrice).toFixed(2).replace('.', ',')}`;
            productElement.querySelector('.product-id').value = productId;
            productElement.querySelector('.product-id').name = `products[${productCounter}][product_id]`;
            productElement.querySelector('.quantity-input').name = `products[${productCounter}][quantity]`;

            // Verwijder knop
            productElement.querySelector('.remove-product').addEventListener('click', function() {
                addedProducts.delete(parseInt(productId));
                productElement.remove();
                calculateTotals();
            });

            // Quantity change
            const quantityInput = productElement.querySelector('.quantity-input');
            const totalPriceInput = productElement.querySelector('.total-price-input');
            const unitPrice = parseFloat(productPrice);

            function updateProductTotal() {
                const quantity = parseInt(quantityInput.value) || 1;
                const total = quantity * unitPrice;
                totalPriceInput.value = `€${total.toFixed(2).replace('.', ',')}`;
                calculateTotals();
            }

            quantityInput.addEventListener('input', updateProductTotal);
            quantityInput.addEventListener('change', updateProductTotal);

            // Initial calculation
            updateProductTotal();

            productsContainer.appendChild(productElement);
            addedProducts.add(parseInt(productId));
            productCounter++;

            // Reset select
            productSelect.selectedIndex = 0;

            // Update form validation
            updateFormValidation();
        });
    }

    // Bereken totalen
    function calculateTotals() {
        let subtotal = 0;

        document.querySelectorAll('.product-item').forEach(item => {
            const totalPriceText = item.querySelector('.total-price-input').value;
            const totalPrice = parseFloat(totalPriceText.replace('€', '').replace(',', '.')) || 0;
            subtotal += totalPrice;
        });

        const vat = subtotal * 0.21;
        const total = subtotal + vat;

        const subtotalElement = document.getElementById('subtotal');
        const vatElement = document.getElementById('vat');
        const totalElement = document.getElementById('total');

        if (subtotalElement) subtotalElement.textContent = `€${subtotal.toFixed(2).replace('.', ',')}`;
        if (vatElement) vatElement.textContent = `€${vat.toFixed(2).replace('.', ',')}`;
        if (totalElement) totalElement.textContent = `€${total.toFixed(2).replace('.', ',')}`;
    }

    // Form validation
    function updateFormValidation() {
        const submitButton = document.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = addedProducts.size === 0;
        }
    }

    const quoteForm = document.getElementById('quoteForm');
    if (quoteForm) {
        quoteForm.addEventListener('submit', function(e) {
            if (addedProducts.size === 0) {
                e.preventDefault();
                alert('Voeg minimaal één product toe aan de offerte');
                return;
            }

            if (customerSelect && !customerSelect.value) {
                e.preventDefault();
                alert('Selecteer een klant');
                return;
            }
        });
    }

    // Initial form validation
    updateFormValidation();
});
</script>
@endsection
