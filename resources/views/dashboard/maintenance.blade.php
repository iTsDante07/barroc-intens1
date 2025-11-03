@extends('components.layouts.app')

@section('title', 'Maintenance Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Maintenance Dashboard</h1>
    <p class="text-gray-600">Welkom, {{ auth()->user()->name }}</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Onderhouds Overzicht</h2>
    <p class="text-gray-600">Onderhoud functionaliteit komt hier binnenkort...</p>
</div>
@endsection
