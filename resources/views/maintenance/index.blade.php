@extends('components.layouts.app')

@section('title', 'Onderhoud - Barroc Intens')

@section('content')
@php
    // Fallback voor als de variabelen niet bestaan
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

<!-- Statistieken -->
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
                <p class="text-2xl font-semibold text-gray-900">{{ $maintenances->count() }}</p>
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
<!-- Filters -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('maintenance.index') }}" class="px-4 py-2 rounded-lg {{ !request()->has('filter') ? 'bg-yellow-500 text-black' : 'bg-gray-200 text-gray-700' }}">
            Alle
        </a>
        <a href="{{ route('maintenance.index', ['filter' => 'gepland']) }}" class="px-4 py-2 rounded-lg {{ request('filter') == 'gepland' ? 'bg-yellow-500 text-black' : 'bg-gray-200 text-gray-700' }}">
            Gepland
        </a>
        <a href="{{ route('maintenance.index', ['filter' => 'in_uitvoering']) }}" class="px-4 py-2 rounded-lg {{ request('filter') == 'in_uitvoering' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
            In Uitvoering
        </a>
        <a href="{{ route('maintenance.index', ['filter' => 'voltooid']) }}" class="px-4 py-2 rounded-lg {{ request('filter') == 'voltooid' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }}">
            Voltooid
        </a>
        <a href="{{ route('maintenance.index', ['filter' => 'overdue']) }}" class="px-4 py-2 rounded-lg {{ request('filter') == 'overdue' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700' }}">
            Overdue
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Onderhoud Overzicht</h2>
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
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Periodiek
                            </span>
                        @elseif($maintenance->type === 'reparatie')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Reparatie
                            </span>
                        @elseif($maintenance->type === 'installatie')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Installatie
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Onderhoud
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($maintenance->priority === 'urgent')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Urgent
                            </span>
                        @elseif($maintenance->priority === 'hoog')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                Hoog
                            </span>
                        @elseif($maintenance->priority === 'normaal')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Normaal
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Laag
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($maintenance->status === 'voltooid')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Voltooid
                            </span>
                        @elseif($maintenance->status === 'in_uitvoering')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                In Uitvoering
                            </span>
                        @elseif($maintenance->status === 'geannuleerd')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Geannuleerd
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Gepland
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $maintenance->scheduled_date->format('d-m-Y') }}
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
    <h3 class="text-lg font-medium text-gray-900 mb-2">Nog geen onderhoudstaken</h3>
    <p class="text-gray-500 mb-4">Plan je eerste onderhoudstaak in om te beginnen.</p>
    <a href="{{ route('maintenance.create') }}" class="bg-yellow-500 text-black px-6 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
        Eerste Taak Plannen
    </a>
</div>

@endif
@endsection
