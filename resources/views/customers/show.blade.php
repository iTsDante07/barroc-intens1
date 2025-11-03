@extends('components.layouts.app')

@section('title', $customer->company_name . ' - Barroc Intens')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">{{ $customer->company_name }}</h1>
        <p class="text-gray-600">Klant details</p>
    </div>
    <div class="flex space-x-4">
        <a href="{{ route('customers.edit', $customer) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition-colors">
            Bewerken
        </a>
        <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze klant wilt verwijderen?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                Verwijderen
            </button>
        </form>
        <a href="{{ route('customers.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
            Terug
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Klant Details -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Klant Informatie</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-600">Bedrijfsnaam</label>
                <p class="mt-1 text-lg text-gray-900">{{ $customer->company_name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Contactpersoon</label>
                <p class="mt-1 text-gray-700">{{ $customer->contact_name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">E-mail</label>
                <p class="mt-1 text-gray-700">{{ $customer->email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Telefoon</label>
                <p class="mt-1 text-gray-700">{{ $customer->phone }}</p>
            </div>
        </div>
    </div>

    <!-- Adres Informatie -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Adres Informatie</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-600">Adres</label>
                <p class="mt-1 text-gray-700">{{ $customer->address }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Postcode</label>
                    <p class="mt-1 text-gray-700">{{ $customer->postal_code }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600">Stad</label>
                    <p class="mt-1 text-gray-700">{{ $customer->city }}</p>
                </div>
            </div>

            <div class="mt-4">
                <a href="https://maps.google.com/?q={{ urlencode($customer->address . ', ' . $customer->postal_code . ' ' . $customer->city) }}"
                   target="_blank"
                   class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Bekijk op Google Maps
                </a>
            </div>
        </div>
    </div>
</div>

<!-- BKR Status Card -->
<div class="mt-8 bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">BKR Status</h2>

    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            @if($customer->bkr_checked)
                @if($customer->bkr_approved)
                    <div class="flex items-center text-green-600">
                        <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold">Goedgekeurd</p>
                            <p class="text-sm text-gray-600">Klant is BKR goedgekeurd</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center text-red-600">
                        <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold">Afgekeurd</p>
                            <p class="text-sm text-gray-600">Klant is BKR afgekeurd</p>
                        </div>
                    </div>
                @endif
            @else
                <div class="flex items-center text-yellow-600">
                    <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold">Niet gecontroleerd</p>
                        <p class="text-sm text-gray-600">BKR check is nog niet uitgevoerd</p>
                    </div>
                </div>
            @endif
        </div>

        @if(!$customer->bkr_checked)
        <form action="{{ route('customers.check-bkr', $customer) }}" method="POST">
            @csrf
            <button type="submit" class="bg-yellow-500 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-600 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                BKR Check Uitvoeren
            </button>
        </form>
        @endif
    </div>

    @if($customer->bkr_notes)
    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
        <p class="text-sm text-gray-700">{{ $customer->bkr_notes }}</p>
    </div>
    @endif
</div>

<<<<<<< Updated upstream
{{-- ... bestaande code ... --}}

<!-- Snelle Acties -->
<div class="mt-8 bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Snelle Acties</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @if($customer->bkr_checked && $customer->bkr_approved)
        <a href="{{ route('quotes.create.for.customer', $customer) }}" class="bg-green-500 text-white px-4 py-3 rounded-lg font-semibold hover:bg-green-600 transition-colors text-center flex flex-col items-center justify-center">
            <span class="text-2xl mb-2">📄</span>
            <span>Offerte Maken</span>
        </a>
        <a href="{{ route('maintenance.create.for.customer', $customer) }}" class="bg-blue-500 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-600 transition-colors text-center flex flex-col items-center justify-center">
            <span class="text-2xl mb-2">🔧</span>
            <span>Onderhoud Inplannen</span>
        </a>
        <a href="mailto:{{ $customer->email }}?subject=Barroc Intens Contact" class="bg-purple-500 text-white px-4 py-3 rounded-lg font-semibold hover:bg-purple-600 transition-colors text-center flex flex-col items-center justify-center">
            <span class="text-2xl mb-2">📞</span>
            <span>Contact Opnemen</span>
        </a>
        @elseif($customer->bkr_checked && !$customer->bkr_approved)
        <div class="md:col-span-3 bg-red-50 border border-red-200 rounded-lg p-4 text-center">
            <p class="text-red-700 font-semibold">⚠️ Deze klant is BKR afgekeurd. Offertes maken is niet mogelijk.</p>
        </div>
        @else
        <div class="md:col-span-3 bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
            <p class="text-yellow-700 font-semibold">⏳ Voer eerst een BKR check uit om acties beschikbaar te maken.</p>
            <a href="{{ route('customers.bkr-confirm', $customer) }}" class="inline-block mt-2 bg-yellow-500 text-black px-4 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
                BKR Check Uitvoeren
            </a>
        </div>
        @endif
    </div>
=======

>>>>>>> Stashed changes
</div>
@endsection
