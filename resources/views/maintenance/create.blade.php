@extends('components.layouts.app')

@section('title', 'Nieuwe Onderhoudstaak - Barroc Intens')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Nieuwe Onderhoudstaak</h1>
    <p class="text-gray-600">Plan een nieuwe onderhoudstaak in</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('maintenance.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Klant Selectie -->
            <div>
                <label for="customer_id" class="block text-sm font-medium text-gray-700">Klant *</label>
                <select name="customer_id" id="customer_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                    <option value="">Selecteer een klant</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->company_name }} - {{ $customer->contact_name ?? $customer->email }}
                        </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Toegewezen Monteur -->
            <div>
                <label for="assigned_to" class="block text-sm font-medium text-gray-700">Toegewezen aan *</label>
                <select name="assigned_to" id="assigned_to" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                    <option value="">Selecteer een monteur</option>
                    @foreach($technicians as $technician)
                        <option value="{{ $technician->id }}" {{ old('assigned_to') == $technician->id ? 'selected' : '' }}>
                            {{ $technician->name }} - {{ $technician->email }}
                        </option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Taak Titel -->
            <div class="lg:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700">Taak Titel *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"
                       placeholder="Bijv.: Periodiek onderhoud koffiemachine">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Beschrijving -->
            <div class="lg:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Beschrijving *</label>
                <textarea name="description" id="description" rows="4" required
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"
                          placeholder="Gedetailleerde beschrijving van de onderhoudstaak...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type en Prioriteit -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Type *</label>
                <select name="type" id="type" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                    <option value="">Selecteer type</option>
                    <option value="periodiek" {{ old('type') == 'periodiek' ? 'selected' : '' }}>Periodiek Onderhoud</option>
                    <option value="reparatie" {{ old('type') == 'reparatie' ? 'selected' : '' }}>Reparatie</option>
                    <option value="installatie" {{ old('type') == 'installatie' ? 'selected' : '' }}>Installatie</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700">Prioriteit *</label>
                <select name="priority" id="priority" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                    <option value="">Selecteer prioriteit</option>
                    <option value="laag" {{ old('priority') == 'laag' ? 'selected' : '' }}>Laag</option>
                    <option value="normaal" {{ old('priority', 'normaal') == 'normaal' ? 'selected' : '' }}>Normaal</option>
                    <option value="hoog" {{ old('priority') == 'hoog' ? 'selected' : '' }}>Hoog</option>
                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
                @error('priority')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Geplande Datum -->
            <div>
                <label for="scheduled_date" class="block text-sm font-medium text-gray-700">Geplande Datum *</label>
                <input type="date" name="scheduled_date" id="scheduled_date"
                       value="{{ old('scheduled_date', now()->format('Y-m-d')) }}" required
                       min="{{ now()->format('Y-m-d') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                @error('scheduled_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notities -->
            <div class="lg:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700">Notities</label>
                <textarea name="notes" id="notes" rows="3"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"
                          placeholder="Optionele notities...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-8 flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('maintenance.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
                Annuleren
            </a>
            <button type="submit" class="bg-yellow-500 text-black px-6 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
                Taak Aanmaken
            </button>
        </div>
    </form>
</div>

<!-- Toon validatie errors bovenaan -->
@if($errors->any())
<div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
    <strong class="font-bold">Whoops!</strong>
    <span class="block sm:inline">Er zijn problemen met je invoer.</span>
    <ul class="mt-2 list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@endsection
