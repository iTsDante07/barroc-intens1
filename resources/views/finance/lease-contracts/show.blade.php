@extends('components.layouts.app')

@section('title', $contract->contract_number . ' - Barroc Intens')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Leasecontract {{ $contract->contract_number }}</h1>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('lease-contracts.edit', $contract) }}"
               class="bg-yellow-500 text-black px-4 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
                Bewerken
            </a>
            <a href="{{ route('lease-contracts.index') }}"
               class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                Terug
            </a>
        </div>
    </div>

    <!-- Contract Status -->
    <div class="mb-6">
        <span class="px-4 py-2 rounded-full text-sm font-semibold
            @if($contract->status === 'active')
            @elseif($contract->status === 'ended')
            @else bg-red-100 text-red-800 @endif">
            {{ $contract->status === 'active' ? 'Actief' : ($contract->status === 'ended' ? 'Beëindigd' : 'Geannuleerd') }}
        </span>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Contract Details -->
        <div class="lg:col-span-2">
            <!-- Contract Information -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Contractinformatie</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Klant</h3>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $contract->customer->company_name }}</p>
                        <p class="text-gray-600">{{ $contract->customer->contact_name }}</p>
                        <p class="text-gray-600">{{ $contract->customer->email }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Periode</h3>
                        <p class="mt-1 text-lg font-medium text-gray-900">
                            {{ $contract->start_date->format('d-m-Y') }}
                            @if($contract->end_date)
                            tot {{ $contract->end_date->format('d-m-Y') }}
                            @else
                            (Geen einddatum)
                            @endif
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Facturatie</h3>
                        <p class="mt-1 text-lg font-medium text-gray-900">
                            @if($contract->billing_frequency === 'monthly')
                            Maandelijks
                            @elseif($contract->billing_frequency === 'quarterly')
                            Per kwartaal
                            @else
                            Jaarlijks
                            @endif
                        </p>
                        <p class="text-gray-600">€{{ number_format($contract->monthly_amount, 2, ',', '.') }} per maand</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Contractnummer</h3>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $contract->contract_number }}</p>
                    </div>
                </div>

                @if($contract->terms)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Contractvoorwaarden</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $contract->terms }}</p>
                </div>
                @endif

                @if($contract->notes)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Opmerkingen</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $contract->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Items -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Contract Items</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Omschrijving</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aantal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prijs p/st</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Totaal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($contract->items as $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($item->type === 'machine')
                                        @elseif($item->type === 'coffee')
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $item->type === 'machine' ? 'Machine' : ($item->type === 'coffee' ? 'Koffie' : 'Service') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    {{ $item->product ? $item->product->name : '-' }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $item->description }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($item->type === 'coffee')
                                        {{ $item->coffee_bags_per_month }} zakken/maand
                                    @else
                                        {{ $item->quantity }} stuks
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    €{{ number_format($item->monthly_price, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 font-semibold">
                                    €{{ number_format(($item->type === 'coffee' ? $item->coffee_bags_per_month : $item->quantity) * $item->monthly_price, 2, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                            <tr class="bg-gray-50">
                                <td colspan="5" class="px-4 py-3 text-right font-semibold text-gray-900">Totaal maandelijks:</td>
                                <td class="px-4 py-3 font-bold text-lg text-gray-900">
                                    €{{ number_format($contract->monthly_amount, 2, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Invoices & Actions -->
        <div>
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Acties</h2>
                <div class="space-y-3">

                    @if($contract->status === 'active')
                    <form action="{{ route('lease-contracts.destroy', $contract) }}" method="POST"
                          onsubmit="return confirm('Weet je zeker dat je dit contract wilt verwijderen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                            Contract verwijderen
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Recent Invoices -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Recente facturen</h2>

                @if($contract->invoices->count() > 0)
                <div class="space-y-4">
                    @foreach($contract->invoices->take(5) as $invoice)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <a href="{{ route('invoices.show', $invoice) }}"
                                   class="font-medium text-gray-900 hover:text-barroc-yellow">
                                    Factuur {{ $invoice->invoice_number }}
                                </a>
                                <p class="text-sm text-gray-600">{{ $invoice->invoice_date->format('d-m-Y') }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($invoice->status === 'paid')
                                @elseif($invoice->status === 'overdue')
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $invoice->status === 'paid' ? 'Betaald' : ($invoice->status === 'overdue' ? 'Vervallen' : 'Open') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">€{{ number_format($invoice->total_amount, 2, ',', '.') }}</span>
                            <a href="{{ route('invoices.download.pdf', $invoice) }}"
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                Download PDF
                            </a>
                        </div>
                    </div>
                    @endforeach

                    @if($contract->invoices->count() > 5)
                    <a href="{{ route('invoices.index') }}?search={{ $contract->contract_number }}"
                       class="block text-center text-barroc-yellow hover:text-yellow-600 font-medium">
                        Alle facturen bekijken ({{ $contract->invoices->count() }})
                    </a>
                    @endif
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="mt-2 text-gray-500">Nog geen facturen aangemaakt</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
