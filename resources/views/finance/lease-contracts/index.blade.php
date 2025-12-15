@extends('components.layouts.app')

@section('title', 'Lease Contracten - Barroc Intens')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Lease Contracten</h1>
        <p class="text-gray-600">Beheer alle lease contracten</p>
    </div>
    <div class="flex space-x-4">
        <form action="{{ route('lease-contracts.generate-all-invoices') }}" method="POST">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                Genereer Maandelijkse Facturen
            </button>
        </form>
        <a href="{{ route('lease-contracts.create') }}" class="bg-barroc-yellow text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
            + Nieuw Contract
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('lease-contracts.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
                <option value="">Alle status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actief</option>
                <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Beëindigd</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Geannuleerd</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Klant</label>
            <select name="customer_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
                <option value="">Alle klanten</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Zoeken</label>
            <input type="text" name="search" placeholder="Contractnummer, klant..."
                   value="{{ request('search') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-barroc-yellow focus:ring-barroc-yellow">
        </div>

        <div class="flex items-end">
            <button type="submit" class="bg-black text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors w-full">
                Filteren
            </button>
        </div>
    </form>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Totaal Contracten</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $contracts->total() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Actief</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $activeCount }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Beëindigd</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $endedCount }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Contracts Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contract Nr</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Startdatum</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Maandelijks</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($contracts as $contract)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $contract->contract_number }}</div>
                        <div class="text-sm text-gray-500">{{ $contract->billing_frequency }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $contract->customer->company_name }}</div>
                        <div class="text-sm text-gray-500">{{ $contract->customer->contact_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $contract->start_date->format('d-m-Y') }}</div>
                        @if($contract->end_date)
                        <div class="text-sm text-gray-500">t/m {{ $contract->end_date->format('d-m-Y') }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">€{{ number_format($contract->monthly_amount, 2, ',', '.') }}</div>
                        <div class="text-sm text-gray-500">per maand</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($contract->status === 'active') 
                            @elseif($contract->status === 'ended')
                            @else bg-red-100 text-red-800 @endif">
                            {{ $contract->status === 'active' ? 'Actief' : ($contract->status === 'ended' ? 'Beëindigd' : 'Geannuleerd') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('lease-contracts.show', $contract) }}" class="text-blue-600 hover:text-blue-900 mr-4">Bekijken</a>
                        <a href="{{ route('lease-contracts.edit', $contract) }}" class="text-yellow-600 hover:text-yellow-900 mr-4">Bewerken</a>
                        @if($contract->status === 'active')
                        <form action="{{ route('lease-contracts.create-invoice', $contract) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-900">Factuur</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-4">Nog geen lease contracten</p>
                        <a href="{{ route('lease-contracts.create') }}" class="mt-2 inline-block text-barroc-yellow hover:text-yellow-600">
                            Maak je eerste contract aan
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($contracts->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $contracts->links() }}
    </div>
    @endif
</div>
@endsection
