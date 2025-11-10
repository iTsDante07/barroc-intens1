@extends('components.layouts.app')

@section('title', 'Onderhoud - Barroc Intens')

@section('content')
@php
    $maintenances = $maintenances ?? collect();
    $upcomingCount = $upcomingCount ?? 0;
    $overdueCount = $overdueCount ?? 0;
    $completedCount = $completedCount ?? 0;
@endphp

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Onderhoudstaken</h1>
        <p class="text-gray-600">Beheer alle onderhoudstaken</p>
    </div>
    <a href="{{ route('maintenance.create') }}" class="bg-yellow-500 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
        Nieuwe Taak
    </a>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h3 class="text-lg font-semibold text-gray-800">Filters</h3>

        <form method="GET" action="{{ route('maintenance.index') }}" class="flex flex-col md:flex-row gap-4">
            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full md:w-40 rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                    <option value="">Alle statussen</option>
                    <option value="gepland" {{ request('status') == 'gepland' ? 'selected' : '' }}>Gepland</option>
                    <option value="in_uitvoering" {{ request('status') == 'in_uitvoering' ? 'selected' : '' }}>In Uitvoering</option>
                    <option value="voltooid" {{ request('status') == 'voltooid' ? 'selected' : '' }}>Voltooid</option>
                    <option value="geannuleerd" {{ request('status') == 'geannuleerd' ? 'selected' : '' }}>Geannuleerd</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" id="type" class="w-full md:w-40 rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                    <option value="">Alle types</option>
                    <option value="periodiek" {{ request('type') == 'periodiek' ? 'selected' : '' }}>Periodiek</option>
                    <option value="reparatie" {{ request('type') == 'reparatie' ? 'selected' : '' }}>Reparatie</option>
                    <option value="installatie" {{ request('type') == 'installatie' ? 'selected' : '' }}>Installatie</option>
                </select>
            </div>

            <!-- Priority Filter -->
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioriteit</label>
                <select name="priority" id="priority" class="w-full md:w-40 rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                    <option value="">Alle prioriteiten</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="hoog" {{ request('priority') == 'hoog' ? 'selected' : '' }}>Hoog</option>
                    <option value="normaal" {{ request('priority') == 'normaal' ? 'selected' : '' }}>Normaal</option>
                    <option value="laag" {{ request('priority') == 'laag' ? 'selected' : '' }}>Laag</option>
                </select>
            </div>

            <!-- Date Filter -->
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Datum</label>
                <select name="date" id="date" class="w-full md:w-40 rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                    <option value="">Alle data</option>
                    <option value="vandaag" {{ request('date') == 'vandaag' ? 'selected' : '' }}>Vandaag</option>
                    <option value="deze_week" {{ request('date') == 'deze_week' ? 'selected' : '' }}>Deze week</option>
                    <option value="deze_maand" {{ request('date') == 'deze_maand' ? 'selected' : '' }}>Deze maand</option>
                    <option value="aankomende_week" {{ request('date') == 'aankomende_week' ? 'selected' : '' }}>Aankomende week</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-yellow-500 text-black px-4 py-2 rounded-md font-semibold hover:bg-yellow-600 transition-colors">
                    Filteren
                </button>
                <a href="{{ route('maintenance.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md font-semibold hover:bg-gray-600 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Totaal</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $totalCount }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Voltooid</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $completedCount }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-500 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Gepland</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $upcomingCount }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-500 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Overdue</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $overdueCount }}</p>
            </div>
        </div>
    </div>
</div>

@if($maintenances->count() > 0)
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Onderhoud Overzicht</h2>
        <span class="text-sm text-gray-500">{{ $maintenances->count() }} taken gevonden</span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taak</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monteur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioriteit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gepland</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($maintenances as $maintenance)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $maintenance->title }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($maintenance->description, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $maintenance->customer->company_name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $maintenance->assignedTechnician->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($maintenance->type === 'periodiek')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Periodiek</span>
                        @elseif($maintenance->type === 'reparatie')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Reparatie</span>
                        @elseif($maintenance->type === 'installatie')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Installatie</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($maintenance->priority === 'urgent')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Urgent</span>
                        @elseif($maintenance->priority === 'hoog')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Hoog</span>
                        @elseif($maintenance->priority === 'normaal')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Normaal</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Laag</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($maintenance->status === 'voltooid')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Voltooid</span>
                        @elseif($maintenance->status === 'in_uitvoering')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">In Uitvoering</span>
                        @elseif($maintenance->status === 'geannuleerd')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Geannuleerd</span>
                        @elseif($maintenance->status === 'gepland' && $maintenance->scheduled_date->isPast())
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Overdue</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Gepland</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $maintenance->scheduled_date->format('d-m-Y') }}
                        @if($maintenance->status === 'gepland' && $maintenance->scheduled_date->isPast())
                            <span class="ml-1 text-red-600" title="Overdue">⚠️</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('maintenance.show', $maintenance) }}" class="text-blue-600 hover:text-blue-900">Bekijken</a>
                        <a href="{{ route('maintenance.edit', $maintenance) }}" class="text-green-600 hover:text-green-900">Bewerken</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="text-center py-12">
    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
    </svg>
    <h3 class="text-lg font-medium text-gray-900 mb-2">Geen onderhoudstaken gevonden</h3>
    <p class="text-gray-500 mb-4">Probeer andere filterinstellingen of plan een nieuwe onderhoudstaak.</p>
    <div class="space-x-2">
        <a href="{{ route('maintenance.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
            Filters resetten
        </a>
        <a href="{{ route('maintenance.create') }}" class="bg-yellow-500 text-black px-6 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
            Nieuwe Taak
        </a>
    </div>
</div>
@endif
@endsection
