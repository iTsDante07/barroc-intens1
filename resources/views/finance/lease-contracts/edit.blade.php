@extends('components.layouts.app')

@section('title', $contract->contract_number . ' bewerken - Barroc Intens')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Leasecontract {{ $contract->contract_number }} bewerken</h1>
    </div>

    <form action="{{ route('lease-contracts.update', $contract) }}" method="POST" id="leaseContractForm">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Contractgegevens</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Klant selectie -->
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Klant *</label>
                    <select name="customer_id" id="customer_id" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
                        <option value="">Selecteer een klant</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $contract->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->company_name }} - {{ $customer->contact_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Billing frequency -->
                <div>
                    <label for="billing_frequency" class="block text-sm font-medium text-gray-700 mb-2">Facturatie frequentie *</label>
                    <select name="billing_frequency" id="billing_frequency" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
                        <option value="">Selecteer frequentie</option>
                        <option value="monthly" {{ old('billing_frequency', $contract->billing_frequency) == 'monthly' ? 'selected' : '' }}>Maandelijks</option>
                        <option value="quarterly" {{ old('billing_frequency', $contract->billing_frequency) == 'quarterly' ? 'selected' : '' }}>Per kwartaal</option>
                        <option value="yearly" {{ old('billing_frequency', $contract->billing_frequency) == 'yearly' ? 'selected' : '' }}>Jaarlijks</option>
                    </select>
                    @error('billing_frequency')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start datum -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Startdatum *</label>
                    <input type="date" name="start_date" id="start_date"
                           value="{{ old('start_date', $contract->start_date->format('Y-m-d')) }}" required
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Eind datum -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Einddatum (optioneel)</label>
                    <input type="date" name="end_date" id="end_date"
                           value="{{ old('end_date', $contract->end_date ? $contract->end_date->format('Y-m-d') : '') }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
                        <option value="active" {{ old('status', $contract->status) == 'active' ? 'selected' : '' }}>Actief</option>
                        <option value="ended" {{ old('status', $contract->status) == 'ended' ? 'selected' : '' }}>Beëindigd</option>
                        <option value="cancelled" {{ old('status', $contract->status) == 'cancelled' ? 'selected' : '' }}>Geannuleerd</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Terms -->
            <div class="mt-6">
                <label for="terms" class="block text-sm font-medium text-gray-700 mb-2">Contractvoorwaarden (optioneel)</label>
                <textarea name="terms" id="terms" rows="3"
                          class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">{{ old('terms', $contract->terms) }}</textarea>
                @error('terms')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Opmerkingen (optioneel)</label>
                <textarea name="notes" id="notes" rows="2"
                          class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">{{ old('notes', $contract->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Items Section -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Contract Items</h2>
                <button type="button" onclick="addItem()"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                    + Item toevoegen
                </button>
            </div>

            <div id="items-container">
                <!-- Items from existing contract -->
                @foreach($contract->items as $index => $item)
                <div class="item-group mb-6 p-4 border border-gray-200 rounded-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-medium text-gray-700">Item #{{ $index + 1 }}</h3>
                        <button type="button" onclick="removeItem(this)"
                                class="text-red-600 hover:text-red-800">
                            Verwijder
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                            <select name="items[{{ $index }}][type]" onchange="updateItemFields(this)" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
                                <option value="">Selecteer type</option>
                                <option value="machine" {{ old('items.'.$index.'.type', $item->type) == 'machine' ? 'selected' : '' }}>Machine</option>
                                <option value="coffee" {{ old('items.'.$index.'.type', $item->type) == 'coffee' ? 'selected' : '' }}>Koffie</option>
                                <option value="service" {{ old('items.'.$index.'.type', $item->type) == 'service' ? 'selected' : '' }}>Service</option>
                            </select>
                        </div>

                        <!-- Product (optional) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product (optioneel)</label>
                            <select name="items[{{ $index }}][product_id]"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
                                <option value="">Selecteer product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('items.'.$index.'.product_id', $item->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Omschrijving *</label>
                            <input type="text" name="items[{{ $index }}][description]" required
                                   value="{{ old('items.'.$index.'.description', $item->description) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow"
                                   placeholder="Bijv. Espresso Machine, Koffiebonen, Onderhoud">
                        </div>

                        <!-- Quantity / Coffee bags -->
                        <div class="quantity-field {{ $item->type == 'coffee' ? 'hidden' : '' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Aantal *</label>
                            <input type="number" name="items[{{ $index }}][quantity]"
                                   value="{{ old('items.'.$index.'.quantity', $item->quantity) }}"
                                   min="1" {{ $item->type != 'coffee' ? 'required' : '' }}
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
                        </div>

                        <div class="coffee-field {{ $item->type != 'coffee' ? 'hidden' : '' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Zakken per maand *</label>
                            <input type="number" name="items[{{ $index }}][coffee_bags_per_month]"
                                   value="{{ old('items.'.$index.'.coffee_bags_per_month', $item->coffee_bags_per_month) }}"
                                   min="0" {{ $item->type == 'coffee' ? 'required' : '' }}
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
                        </div>

                        <!-- Monthly price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Maandelijkse prijs per stuk (€) *</label>
                            <input type="number" name="items[{{ $index }}][monthly_price]" required
                                   value="{{ old('items.'.$index.'.monthly_price', $item->monthly_price) }}"
                                   min="0" step="0.01"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow"
                                   placeholder="0.00">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Total calculation -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-800">Totaal maandelijks bedrag:</span>
                    <span id="total-monthly-amount" class="text-2xl font-bold text-gray-900">
                        €{{ number_format($contract->monthly_amount, 2, ',', '.') }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 mt-2">Berekend op basis van alle items</p>
            </div>
        </div>

        <!-- Form actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('lease-contracts.show', $contract) }}"
               class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
                Annuleren
            </a>
            <button type="submit"
                    class="bg-barroc-yellow text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
                Wijzigingen opslaan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
let itemCount = {{ $contract->items->count() }};

function addItem() {
    itemCount++;
    const template = `
        <div class="item-group mb-6 p-4 border border-gray-200 rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-medium text-gray-700">Item #${itemCount}</h3>
                <button type="button" onclick="removeItem(this)"
                        class="text-red-600 hover:text-red-800">
                    Verwijder
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                    <select name="items[${itemCount-1}][type]" onchange="updateItemFields(this)" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
                        <option value="">Selecteer type</option>
                        <option value="machine">Machine</option>
                        <option value="coffee">Koffie</option>
                        <option value="service">Service</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product (optioneel)</label>
                    <select name="items[${itemCount-1}][product_id]"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
                        <option value="">Selecteer product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Omschrijving *</label>
                    <input type="text" name="items[${itemCount-1}][description]" required
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow"
                           placeholder="Bijv. Espresso Machine, Koffiebonen, Onderhoud">
                </div>

                <div class="quantity-field">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aantal *</label>
                    <input type="number" name="items[${itemCount-1}][quantity]" required min="1" value="1"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
                </div>

                <div class="coffee-field hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Zakken per maand *</label>
                    <input type="number" name="items[${itemCount-1}][coffee_bags_per_month]" min="0" value="0"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maandelijkse prijs per stuk (€) *</label>
                    <input type="number" name="items[${itemCount-1}][monthly_price]" required min="0" step="0.01"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow"
                           placeholder="0.00">
                </div>
            </div>
        </div>
    `;

    document.getElementById('items-container').insertAdjacentHTML('beforeend', template);
    attachEventListeners();
    calculateTotal();
}

function removeItem(button) {
    if (document.querySelectorAll('.item-group').length <= 1) {
        alert('Er moet minimaal één item zijn');
        return;
    }

    button.closest('.item-group').remove();
    updateItemNumbers();
    calculateTotal();
}

function updateItemNumbers() {
    const itemGroups = document.querySelectorAll('.item-group');
    itemGroups.forEach((group, index) => {
        const title = group.querySelector('h3');
        title.textContent = `Item #${index + 1}`;

        // Update input names
        const inputs = group.querySelectorAll('input, select');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                const newName = name.replace(/items\[\d+\]/, `items[${index}]`);
                input.setAttribute('name', newName);
            }
        });
    });
}

function updateItemFields(select) {
    const itemGroup = select.closest('.item-group');
    const quantityField = itemGroup.querySelector('.quantity-field');
    const coffeeField = itemGroup.querySelector('.coffee-field');

    if (select.value === 'coffee') {
        quantityField.classList.add('hidden');
        coffeeField.classList.remove('hidden');
        coffeeField.querySelector('input').required = true;
        quantityField.querySelector('input').required = false;
    } else {
        quantityField.classList.remove('hidden');
        coffeeField.classList.add('hidden');
        quantityField.querySelector('input').required = true;
        coffeeField.querySelector('input').required = false;
    }
    calculateTotal();
}

function calculateTotal() {
    let total = 0;

    document.querySelectorAll('.item-group').forEach(group => {
        const type = group.querySelector('select[name*="[type]"]').value;
        const quantityInput = type === 'coffee'
            ? group.querySelector('input[name*="[coffee_bags_per_month]"]')
            : group.querySelector('input[name*="[quantity]"]');
        const priceInput = group.querySelector('input[name*="[monthly_price]"]');

        if (quantityInput && priceInput) {
            const quantity = parseFloat(quantityInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            total += quantity * price;
        }
    });

    document.getElementById('total-monthly-amount').textContent =
        '€' + total.toLocaleString('nl-NL', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

function attachEventListeners() {
    document.querySelectorAll('input[name*="[quantity]"], input[name*="[coffee_bags_per_month]"], input[name*="[monthly_price]"]').forEach(input => {
        input.addEventListener('input', calculateTotal);
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    attachEventListeners();
    calculateTotal();

    // Update initial item fields
    document.querySelectorAll('select[name*="[type]"]').forEach(select => {
        updateItemFields(select);
    });
});
</script>
@endpush
@endsection
