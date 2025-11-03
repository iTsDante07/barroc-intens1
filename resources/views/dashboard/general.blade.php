@extends('components.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Welkom bij Barroc Intens</h1>
    <p class="text-gray-600">Welkom, {{ auth()->user()->name }}</p>
    @if(!auth()->user()->department)
        <div class="mt-2 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            <strong>Let op:</strong> Je account heeft nog geen afdeling. Neem contact op met een beheerder.
        </div>
    @endif
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Algemeen Overzicht</h2>
        <p class="text-gray-600">Je hebt toegang tot de basis functionaliteiten van Barroc Intens.</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Profiel</h2>
        <p class="text-gray-600">Beheer je account instellingen.</p>
        <a href="{{ route('profile.edit') }}" class="inline-block mt-3 bg-barroc-yellow text-black px-4 py-2 rounded font-semibold">
            Profiel Bewerken
        </a>
    </div>
</div>
@endsection
