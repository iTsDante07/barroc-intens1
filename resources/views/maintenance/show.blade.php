@extends('components.layouts.app')

@section('title', $maintenance->title . ' - Barroc Intens')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">{{ $maintenance->title }}</h1>
        <p class="text-gray-600">Onderhoudstaak details</p>
    </div>
    <div class="flex space-x-4">
        <a href="{{ route('maintenance.edit', $maintenance) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition-colors">
            Bewerken
        </a>

        @if($maintenance->status === 'gepland')
            <form action="{{ route('maintenance.start', $maintenance) }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Start Uitvoering
                </button>
            </form>
        @endif

        @if($maintenance->status === 'in_uitvoering')
            <form action="{{ route('maintenance.complete', $maintenance) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                    Voltooien
                </button>
            </form>
        @endif

        @if(in_array($maintenance->status, ['gepland', 'in_uitvoering']))
            <form action="{{ route('maintenance.cancel', $maintenance) }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                    Annuleren
                </button>
            </form>
        @endif

        <a href="{{ route('maintenance.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
            Terug
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Taak Details -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Taak Details</h2>

        <!-- Status -->
        <div class="mb-6">
            <span class="text-sm font-medium text-gray-600">Status:</span>
            @if($maintenance->status === 'voltooid')
                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    ✅ Voltooid
                </span>
            @elseif($maintenance->status === 'in_uitvoering')
                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    🔧 In Uitvoering
                </span>
            @elseif($maintenance->status === 'geannuleerd')
                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    ❌ Geannuleerd
                </span>
            @else
                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    📅 Gepland
                </span>
                @if($maintenance->isOverdue())
                    <span class="ml-2 px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                        OVERDUE
                    </span>
                @endif
            @endif
        </div>

        <!-- Klant Informatie -->
        <div class="mb-6">
            <h3 class="font-semibold text-gray-800 mb-2">Klant:</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-lg font-medium text-gray-900">{{ $maintenance->customer->company_name }}</p>
                <p class="text-gray-700">{{ $maintenance->customer->contact_name }}</p>
                <p class="text-gray-600">{{ $maintenance->customer->email }}</p>
                <p class="text-gray-600">{{ $maintenance->customer->phone }}</p>
                <p class="text-gray-600 mt-2">{{ $maintenance->customer->address }}</p>
                <p class="text-gray-600">{{ $maintenance->customer->postal_code }}, {{ $maintenance->customer->city }}</p>
            </div>
        </div>

        <!-- Beschrijving -->
        <div class="mb-6">
            <h3 class="font-semibold text-gray-800 mb-2">Beschrijving:</h3>
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <p class="text-gray-700 whitespace-pre-line">{{ $maintenance->description }}</p>
            </div>
        </div>

        <!-- Technician Notities -->
        @if($maintenance->technician_notes)
        <div class="mb-6">
            <h3 class="font-semibold text-gray-800 mb-2">Technician Notities:</h3>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-gray-700 whitespace-pre-line">{{ $maintenance->technician_notes }}</p>
            </div>
        </div>
        @endif

        <!-- Notities -->
        @if($maintenance->notes)
        <div class="mb-6">
            <h3 class="font-semibold text-gray-800 mb-2">Notities:</h3>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-gray-700 whitespace-pre-line">{{ $maintenance->notes }}</p>
            </div>
        </div>
        @endif

        <!-- Technician Notities Form -->
        @if(auth()->user()->department && auth()->user()->department->name === 'Maintenance')
        <div class="mt-6 bg-gray-50 rounded-lg p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Technician Notities Toevoegen</h3>
            <form action="{{ route('maintenance.add-technician-notes', $maintenance) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="technician_notes" class="block text-sm font-medium text-gray-700">Notities</label>
                        <textarea name="technician_notes" id="technician_notes" rows="4"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"
                                  placeholder="Voeg uw notities toe over de uitvoering...">{{ old('technician_notes', $maintenance->technician_notes) }}</textarea>
                    </div>
                    <div>
                        <label for="costs" class="block text-sm font-medium text-gray-700">Kosten (€)</label>
                        <input type="number" name="costs" id="costs" step="0.01" min="0"
                               value="{{ old('costs', $maintenance->costs) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-yellow-500 text-black px-6 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
                            Notities Opslaan
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif
    </div>

    <!-- Taak Informatie Sidebar -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Taak Informatie</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-600">Type</label>
                <p class="mt-1 text-gray-900 capitalize">{{ $maintenance->type }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Prioriteit</label>
                <p class="mt-1">
                    @if($maintenance->priority === 'urgent')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Urgent</span>
                    @elseif($maintenance->priority === 'hoog')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Hoog</span>
                    @elseif($maintenance->priority === 'normaal')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Normaal</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Laag</span>
                    @endif
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Geplande Datum</label>
                <p class="mt-1 text-gray-900">{{ $maintenance->scheduled_date->format('d-m-Y') }}</p>
            </div>

            @if($maintenance->completed_date)
            <div>
                <label class="block text-sm font-medium text-gray-600">Voltooid op</label>
                <p class="mt-1 text-gray-900">{{ $maintenance->completed_date->format('d-m-Y') }}</p>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-600">Toegewezen aan</label>
                <p class="mt-1 text-gray-900">{{ $maintenance->assignedTechnician->name }}</p>
                <p class="text-sm text-gray-600">{{ $maintenance->assignedTechnician->email }}</p>
            </div>

            @if($maintenance->costs)
            <div>
                <label class="block text-sm font-medium text-gray-600">Kosten</label>
                <p class="mt-1 text-lg font-semibold text-green-600">€{{ number_format($maintenance->costs, 2, ',', '.') }}</p>
            </div>
            @endif

            <!-- Dagen tot planning -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Dagen tot uitvoering</label>
                <p class="mt-1 text-gray-900">
                    @php
                        $daysLeft = $maintenance->scheduled_date->diffInDays(now(), false) * -1;
                    @endphp
                    @if($daysLeft > 0)
                        <span class="text-green-600">{{ $daysLeft }} dagen</span>
                    @elseif($daysLeft === 0)
                        <span class="text-yellow-600">Vandaag</span>
                    @else
                        <span class="text-red-600">{{ abs($daysLeft) }} dagen geleden</span>
                    @endif
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Aangemaakt op</label>
                <p class="mt-1 text-gray-900">{{ $maintenance->created_at->format('d-m-Y H:i') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Laatst bijgewerkt</label>
                <p class="mt-1 text-gray-900">{{ $maintenance->updated_at->format('d-m-Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
