@extends('components.layouts.app')

@section('title', 'Offerte ' . $quote->quote_number . ' - Barroc Intens')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Offerte {{ $quote->quote_number }}</h1>
        <p class="text-gray-600">Offerte details</p>
    </div>
    <div class="flex space-x-4">
        @if($quote->status === 'concept')
            <form action="{{ route('quotes.send', $quote) }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Verzenden
                </button>
            </form>
        @endif

        @if($quote->status === 'verzonden')
            <form action="{{ route('quotes.accept', $quote) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                    Accepteren
                </button>
            </form>
            <form action="{{ route('quotes.reject', $quote) }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                    Afwijzen
                </button>
            </form>
        @endif


        <form action="{{ route('quotes.duplicate', $quote) }}" method="POST">
            @csrf
            <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-purple-700 transition-colors">
                Dupliceren
            </button>
        </form>

        <a href="{{ route('quotes.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
            Terug
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Offerte Details -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Offerte Details</h2>

        <!-- Status -->
        <div class="mb-6">
            <span class="text-sm font-medium text-gray-600">Status:</span>
            @if($quote->status === 'geaccepteerd')
                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    Geaccepteerd
                </span>
            @elseif($quote->status === 'verzonden')
                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    Verzonden
                </span>
            @elseif($quote->status === 'afgewezen')
                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    Afgewezen
                </span>
            @else
                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    Concept
                </span>
            @endif
        </div>

        <!-- Klant Informatie -->
        <div class="mb-6">
            <h3 class="font-semibold text-gray-800 mb-2">Klant:</h3>
            <p class="text-gray-700">{{ $quote->customer->company_name }}</p>
            <p class="text-gray-600">{{ $quote->customer->contact_name }}</p>
            <p class="text-gray-600">{{ $quote->customer->email }}</p>
            <p class="text-gray-600">{{ $quote->customer->phone }}</p>
        </div>

        <!-- Producten -->
        <div class="mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">Producten:</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aantal</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Prijs</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Totaal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($quote->products as $product)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $product->product->name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $product->quantity }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">€{{ number_format($product->unit_price, 2, ',', '.') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">€{{ number_format($product->total_price, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totaal Overzicht -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="font-semibold text-gray-800 mb-3">Totaal Overzicht</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span>Subtotaal:</span>
                    <span>€{{ number_format($quote->subtotal, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>BTW (21%):</span>
                    <span>€{{ number_format($quote->vat_amount, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between border-t border-gray-200 pt-2 font-semibold text-lg">
                    <span>Totaal:</span>
                    <span>€{{ number_format($quote->total_amount, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Notities -->
        @if($quote->notes)
        <div class="mt-6">
            <h3 class="font-semibold text-gray-800 mb-2">Notities:</h3>
            <p class="text-gray-700">{{ $quote->notes }}</p>
        </div>
        @endif

        <!-- Voorwaarden -->
        <div class="mt-6">
            <h3 class="font-semibold text-gray-800 mb-2">Algemene Voorwaarden:</h3>
            <p class="text-gray-700 text-sm">{{ $quote->terms }}</p>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Offerte Informatie</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-600">Offerte Nummer</label>
                <p class="mt-1 text-gray-900">{{ $quote->quote_number }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Aangemaakt op</label>
                <p class="mt-1 text-gray-900">{{ $quote->created_at->format('d-m-Y H:i') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Geldig tot</label>
                <p class="mt-1 text-gray-900">{{ $quote->valid_until->format('d-m-Y') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Aangemaakt door</label>
                <p class="mt-1 text-gray-900">{{ $quote->user->name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Dagen geldig</label>
                <p class="mt-1 text-gray-900">
                    @php
                        $daysLeft = $quote->valid_until->diffInDays(now());
                    @endphp
                    @if($daysLeft > 0)
                        <span class="text-green-600">{{ $daysLeft }} dagen</span>
                    @else
                        <span class="text-red-600">Verlopen</span>
                    @endif
                </p>
                {{-- ... bestaande code ... --}}

                <div class="flex space-x-4">
                    @if($quote->status === 'concept')
                        <form action="{{ route('quotes.send', $quote) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                Verzenden
                            </button>
                        </form>
                    @endif

                    @if($quote->status === 'verzonden')
                        <form action="{{ route('quotes.accept', $quote) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                                Accepteren
                            </button>
                        </form>
                        <form action="{{ route('quotes.reject', $quote) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                                Afwijzen
                            </button>
                        </form>
                    @endif

                    {{-- Factuur Knop --}}
                    @if($quote->canCreateInvoice())
                        <a href="{{ route('invoices.create.from.quote', $quote) }}" class="bg-yellow-500 text-black px-4 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
                            📄 Factuur Maken
                        </a>
                    @endif

                    @if($quote->invoices->count() > 0)
                        <a href="{{ route('invoices.show', $quote->invoices->first()) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-600 transition-colors">
                            🧾 Bekijk Factuur
                        </a>
                    @endif

                </div>

                {{-- ... rest van de code ... --}}
            </div>
        </div>
    </div>
</div>
@endsection
