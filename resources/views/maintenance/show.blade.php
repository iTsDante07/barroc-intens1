@extends('components.layouts.app')

@section('title', 'Onderhoudstaak - Barroc Intens')

@section('content')
@php
    // Bepaal overdue status
    $isOverdue = $maintenance->status === 'gepland' && $maintenance->scheduled_date->isPast();
@endphp

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Onderhoudstaak Details</h1>
        <p class="text-gray-600">Bekijk en beheer onderhoudstaak</p>
    </div>
    <div class="flex space-x-4">
        <a href="{{ route('maintenance.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
            Terug naar Overzicht
        </a>
        @if($isOverdue)
            <form action="{{ route('maintenance.complete', $maintenance) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-600 transition-colors">
                    Markeer als Overdue & Voltooid
                </button>
            </form>
        @endif
    </div>
</div>

<!-- Alerts -->
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
    {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Hoofd informatie -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $maintenance->title }}</h2>
                    <p class="text-gray-600 mt-2">{{ $maintenance->description }}</p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($maintenance->status === 'voltooid') bg-green-100 text-green-800
                        @elseif($maintenance->status === 'in_uitvoering') bg-blue-100 text-blue-800
                        @elseif($maintenance->status === 'geannuleerd') bg-red-100 text-red-800
                        @elseif($isOverdue) bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        @if($isOverdue) OVERDUE
                        @else
                            {{ ucfirst($maintenance->status) }}
                        @endif
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Taak Informatie</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Type</dt>
                            <dd class="mt-1">
                                @if($maintenance->type === 'periodiek')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Periodiek
                                    </span>
                                @elseif($maintenance->type === 'reparatie')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Reparatie
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Installatie
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Prioriteit</dt>
                            <dd class="mt-1">
                                @if($maintenance->priority === 'urgent')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Urgent
                                    </span>
                                @elseif($maintenance->priority === 'hoog')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                        Hoog
                                    </span>
                                @elseif($maintenance->priority === 'normaal')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Normaal
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Laag
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Geplande Datum</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $maintenance->scheduled_date->format('d-m-Y') }}
                                @if($isOverdue)
                                    <span class="ml-2 text-red-600 font-semibold">(Overdue!)</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Geplande Tijd</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $maintenance->scheduled_date->format('H:i') }}
                            </dd>
                        </div>
                        @if($maintenance->completed_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Voltooid op</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $maintenance->completed_date->format('d-m-Y') }}
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Contact Informatie</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Klant</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $maintenance->customer->company_name ?? 'N/A' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Toegewezen Monteur</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $maintenance->assignedTechnician->name ?? 'N/A' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Aangemaakt op</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $maintenance->created_at->format('d-m-Y H:i') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Laatst bijgewerkt</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $maintenance->updated_at->format('d-m-Y H:i') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Notities -->
        @if($maintenance->notes || $maintenance->technician_notes)
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Notities</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($maintenance->notes)
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Interne Notities</h4>
                    <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ $maintenance->notes }}</p>
                </div>
                @endif
                @if($maintenance->technician_notes)
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Technician Notities</h4>
                    <p class="text-sm text-gray-900 bg-blue-50 p-3 rounded">{{ $maintenance->technician_notes }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Acties Sidebar -->
    <div class="space-y-6">
        <!-- Status Acties -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Taak Acties</h3>
            <div class="space-y-3">
                @if($maintenance->status === 'gepland')
                    <form action="{{ route('maintenance.start', $maintenance) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-600 transition-colors">
                            Start Uitvoering
                        </button>
                    </form>
                @endif

                @if($maintenance->status === 'in_uitvoering')
                    <form action="{{ route('maintenance.complete', $maintenance) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-600 transition-colors">
                            Markeer als Voltooid
                        </button>
                    </form>
                @endif

                @if(in_array($maintenance->status, ['gepland', 'in_uitvoering']))
                    <form action="{{ route('maintenance.cancel', $maintenance) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-red-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-600 transition-colors">
                            Annuleren
                        </button>
                    </form>
                @endif

                <a href="{{ route('maintenance.edit', $maintenance) }}" class="block w-full bg-yellow-500 text-black px-4 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors text-center">
                    Bewerken
                </a>
            </div>
        </div>

        <!-- Kosten Overzicht -->
        @if($maintenance->costs)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Kosten</h3>
            <div class="text-2xl font-bold text-gray-800">
                € {{ number_format($maintenance->costs, 2, ',', '.') }}
            </div>
        </div>
        @endif

        <!-- Snelle Overdue Actie -->
        @if($isOverdue)
        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-red-700 mb-2">Overdue Actie</h3>
            <p class="text-sm text-red-600 mb-4">Deze taak is overdue. Markeer als voltooid of pas de datum aan.</p>
            <form action="{{ route('maintenance.complete', $maintenance) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-red-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-600 transition-colors">
                    Markeer als Overdue & Voltooid
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
