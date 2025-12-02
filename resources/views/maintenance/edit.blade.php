@extends('components.layouts.app')

@section('title', 'Onderhoudstaak Bewerken - Barroc Intens')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Onderhoudstaak Bewerken</h1>
        <p class="text-gray-600">Wijzig de details van deze onderhoudstaak</p>
    </div>
    <div class="flex space-x-4">
        <a href="{{ route('maintenance.show', $maintenance) }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
            Terug naar Details
        </a>
        <a href="{{ route('maintenance.index') }}" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
            Overzicht
        </a>
    </div>
</div>

<!-- Alerts -->
@if($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('maintenance.update', $maintenance) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basis Informatie -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Basis Informatie</h3>
            </div>

            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titel *</label>
                <input type="text" name="title" id="title" required
                    value="{{ old('title', $maintenance->title) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Beschrijving *</label>
                <textarea name="description" id="description" required rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">{{ old('description', $maintenance->description) }}</textarea>
            </div>

            <!-- Klant en Monteur -->
            <div>
                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Klant *</label>
                <select name="customer_id" id="customer_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="">Selecteer klant</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}"
                            {{ old('customer_id', $maintenance->customer_id) == $customer->id ? 'selected' : '' }}>
                            {{ $customer->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Toegewezen Monteur *</label>
                <select name="assigned_to" id="assigned_to" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="">Selecteer monteur</option>
                    @foreach($technicians as $technician)
                        <option value="{{ $technician->id }}"
                            {{ old('assigned_to', $maintenance->assigned_to) == $technician->id ? 'selected' : '' }}>
                            {{ $technician->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Type en Prioriteit -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                <select name="type" id="type" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="periodiek" {{ old('type', $maintenance->type) == 'periodiek' ? 'selected' : '' }}>Periodiek</option>
                    <option value="reparatie" {{ old('type', $maintenance->type) == 'reparatie' ? 'selected' : '' }}>Reparatie</option>
                    <option value="installatie" {{ old('type', $maintenance->type) == 'installatie' ? 'selected' : '' }}>Installatie</option>
                </select>
            </div>

            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Prioriteit *</label>
                <select name="priority" id="priority" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="laag" {{ old('priority', $maintenance->priority) == 'laag' ? 'selected' : '' }}>Laag</option>
                    <option value="normaal" {{ old('priority', $maintenance->priority) == 'normaal' ? 'selected' : '' }}>Normaal</option>
                    <option value="hoog" {{ old('priority', $maintenance->priority) == 'hoog' ? 'selected' : '' }}>Hoog</option>
                    <option value="urgent" {{ old('priority', $maintenance->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <!-- Datums -->
            <div>
                <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-2">Gepland Moment *</label>
                <input type="datetime-local" name="scheduled_date" id="scheduled_date" required
                    value="{{ old('scheduled_date', optional($maintenance->scheduled_date)->format('Y-m-d\TH:i')) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" id="status" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="gepland" {{ old('status', $maintenance->status) == 'gepland' ? 'selected' : '' }}>Gepland</option>
                    <option value="in_uitvoering" {{ old('status', $maintenance->status) == 'in_uitvoering' ? 'selected' : '' }}>In Uitvoering</option>
                    <option value="voltooid" {{ old('status', $maintenance->status) == 'voltooid' ? 'selected' : '' }}>Voltooid</option>
                    <option value="geannuleerd" {{ old('status', $maintenance->status) == 'geannuleerd' ? 'selected' : '' }}>Geannuleerd</option>
                </select>
            </div>

            <!-- Completed Date (alleen tonen bij voltooid status) -->
            <div id="completed_date_field" style="display: {{ old('status', $maintenance->status) == 'voltooid' ? 'block' : 'none' }};">
                <label for="completed_date" class="block text-sm font-medium text-gray-700 mb-2">Voltooid op</label>
                <input type="date" name="completed_date" id="completed_date"
                    value="{{ old('completed_date', $maintenance->completed_date ? $maintenance->completed_date->format('Y-m-d') : '') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
            </div>

            <!-- Kosten -->
            <div>
                <label for="costs" class="block text-sm font-medium text-gray-700 mb-2">Kosten (€)</label>
                <input type="number" name="costs" id="costs" step="0.01" min="0"
                    value="{{ old('costs', $maintenance->costs) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
            </div>

            <!-- Notities -->
            <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Interne Notities</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">{{ old('notes', $maintenance->notes) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label for="technician_notes" class="block text-sm font-medium text-gray-700 mb-2">Technician Notities</label>
                <textarea name="technician_notes" id="technician_notes" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">{{ old('technician_notes', $maintenance->technician_notes) }}</textarea>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('maintenance.show', $maintenance) }}" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
                Annuleren
            </a>
            <button type="submit" class="bg-yellow-500 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
                Opslaan
            </button>
        </div>
    </form>
</div>

<!-- JavaScript voor conditionele velden -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusField = document.getElementById('status');
    const completedDateField = document.getElementById('completed_date_field');

    function toggleCompletedDate() {
        if (statusField.value === 'voltooid') {
            completedDateField.style.display = 'block';
            // Zet default completed date als leeg
            if (!document.getElementById('completed_date').value) {
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('completed_date').value = today;
            }
        } else {
            completedDateField.style.display = 'none';
        }
    }

    statusField.addEventListener('change', toggleCompletedDate);
    toggleCompletedDate(); // Initial call
});
</script>
@endsection
