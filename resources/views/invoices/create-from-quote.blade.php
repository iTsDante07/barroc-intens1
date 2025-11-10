@extends('components.layouts.app')

@section('title', 'Factuur Maken - Barroc Intens')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Factuur Maken</h1>
    <p class="text-gray-600">Maak een factuur van offerte {{ $quote->quote_number }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Factuur Gegevens</h2>

            <form action="{{ route('invoices.store.from.quote', $quote) }}" method="POST">
                @csrf

            <div class="space-y-4">
                <div>
                    <label for="invoice_date" class="block text-sm font-medium text-gray-700">Factuur Datum *</label>
                    <input type="date" name="invoice_date" id="invoice_date"
                           value="{{ old('invoice_date', now()->format('Y-m-d')) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700">Vervaldatum *</label>
                    <input type="date" name="due_date" id="due_date"
                           value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notities</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"
                              placeholder="Optionele notities voor de factuur...">{{ old('notes', $quote->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('quotes.show', $quote) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
                    Annuleren
                </a>
                <button type="submit" class="bg-yellow-500 text-black px-6 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
                    Factuur Aanmaken
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Offerte Details</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-600">Offerte Nummer</label>
                <p class="text-gray-900">{{ $quote->quote_number }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Klant</label>
                <p class="text-gray-900">{{ $quote->customer->company_name }}</p>
                <p class="text-gray-600 text-sm">{{ $quote->customer->contact_name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Totaal Bedrag</label>
                <p class="text-2xl font-bold text-yellow-600">€{{ number_format($quote->total_amount, 2, ',', '.') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Producten</label>
                <div class="space-y-2">
                    @foreach($quote->products as $product)
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $product->product->name }}</p>
                            <p class="text-xs text-gray-600">{{ $product->quantity }} x €{{ number_format($product->unit_price, 2, ',', '.') }}</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">€{{ number_format($product->total_price, 2, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
