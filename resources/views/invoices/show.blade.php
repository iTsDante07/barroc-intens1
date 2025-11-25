@extends('components.layouts.app')

@section('title', 'Factuur ' . $invoice->invoice_number . ' - Barroc Intens')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Factuur {{ $invoice->invoice_number }}</h1>
        <p class="text-gray-600">Factuur details</p>
    </div>
    <div class="flex space-x-4">
        @if($invoice->status === 'concept')
            <form action="{{ route('invoices.send', $invoice) }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Verzenden
                </button>
            </form>
        @endif

        @if($invoice->status === 'verzonden')
            <form action="{{ route('invoices.mark.paid', $invoice) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                    Markeer als Betaald
                </button>
            </form>
        @endif

        <a href="{{ route('invoices.download.pdf', $invoice) }}" class="bg-yellow-500 text-black px-4 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
            📄 PDF Downloaden
        </a>

        @if($invoice->status === 'concept')
            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze factuur wilt verwijderen?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                    Verwijderen
                </button>
            </form>
        @endif

        <a href="{{ route('invoices.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
            Terug
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Factuur Details -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Factuur Details</h2>

        <!-- Status -->
        <div class="mb-6">
            <span class="text-sm font-medium text-gray-600">Status:</span>
            @if($invoice->status === 'betaald')
                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    ✅ Betaald
                </span>
            @elseif($invoice->status === 'verzonden')
                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    📧 Verzonden
                </span>
            @elseif($invoice->status === 'overdue')
                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    ⚠️ Overdue
                </span>
            @else
                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    📝 Concept
                </span>
            @endif
        </div>

        <!-- Klant Informatie -->
        <div class="mb-6">
            <h3 class="font-semibold text-gray-800 mb-2">Factuur naar:</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-lg font-medium text-gray-900">{{ $invoice->customer->company_name }}</p>
                <p class="text-gray-700">{{ $invoice->customer->contact_name }}</p>
                <p class="text-gray-600">{{ $invoice->customer->email }}</p>
                <p class="text-gray-600">{{ $invoice->customer->phone }}</p>
                <p class="text-gray-600 mt-2">{{ $invoice->customer->address }}</p>
                <p class="text-gray-600">{{ $invoice->customer->postal_code }}, {{ $invoice->customer->city }}</p>
            </div>
        </div>

        <!-- Factuur Items -->
        <div class="mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">Factuur Items:</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Omschrijving</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aantal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prijs</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Totaal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($invoice->items as $item)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->description }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">€{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">€{{ number_format($item->total_price, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totaal Overzicht -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Totaal Overzicht</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotaal:</span>
                    <span class="text-gray-900">€{{ number_format($invoice->subtotal, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">BTW (21%):</span>
                    <span class="text-gray-900">€{{ number_format($invoice->vat_amount, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between border-t border-gray-200 pt-3 text-lg font-semibold">
                    <span class="text-gray-800">Totaal:</span>
                    <span class="text-yellow-600">€{{ number_format($invoice->total_amount, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Notities -->
        @if($invoice->notes)
        <div class="mt-6">
            <h3 class="font-semibold text-gray-800 mb-2">Notities:</h3>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-gray-700">{{ $invoice->notes }}</p>
            </div>
        </div>
        @endif

        <!-- Gekoppelde Offerte -->
        @if($invoice->quote)
        <div class="mt-6">
            <h3 class="font-semibold text-gray-800 mb-2">Gekoppelde Offerte:</h3>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-green-700">
                    Deze factuur is gebaseerd op offerte
                    <a href="{{ route('quotes.show', $invoice->quote) }}" class="font-semibold underline hover:text-green-800">
                        {{ $invoice->quote->quote_number }}
                    </a>
                </p>
            </div>
        </div>
        @endif
    </div>

    <!-- Factuur Informatie Sidebar -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Factuur Informatie</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-600">Factuur Nummer</label>
                <p class="mt-1 text-gray-900 font-mono">{{ $invoice->invoice_number }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Factuur Datum</label>
                <p class="mt-1 text-gray-900">{{ $invoice->invoice_date->format('d-m-Y') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Vervaldatum</label>
                <p class="mt-1 text-gray-900 {{ $invoice->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                    {{ $invoice->due_date->format('d-m-Y') }}
                    @if($invoice->isOverdue())
                        <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-1 rounded">OVERDUE</span>
                    @endif
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Dagen tot vervaldatum</label>
                <p class="mt-1 text-gray-900">
                    @php
                        $daysLeft = $invoice->due_date->diffInDays(now(), false) * -1;
                    @endphp
                    @if($daysLeft > 0)
                        <span class="text-green-600">{{ $daysLeft }} dagen</span>
                    @elseif($daysLeft === 0)
                        <span class="text-yellow-600">Vandaag</span>
                    @else
                        <span class="text-red-600">{{ abs($daysLeft) }} dagen te laat</span>
                    @endif
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Aangemaakt door</label>
                <p class="mt-1 text-gray-900">{{ $invoice->user->name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Aangemaakt op</label>
                <p class="mt-1 text-gray-900">{{ $invoice->created_at->format('d-m-Y H:i') }}</p>
            </div>

            <!-- Betalingsinstructies -->
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <h4 class="font-semibold text-yellow-800 mb-2">Betalingsinstructies</h4>
                <p class="text-sm text-yellow-700">
                    Gelieve het totaalbedrag over te maken naar:<br>
                    <strong>IBAN: NL91 ABNA 0417 1643 00</strong><br>
                    t.n.v. Barroc Intens B.V.<br>
                    Onder vermelding van: {{ $invoice->invoice_number }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
