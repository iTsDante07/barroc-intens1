@extends('components.layouts.app')

@section('title', 'Management Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Management Dashboard</h1>
    <p class="text-gray-600">Welkom, {{ auth()->user()->name }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Totaal Gebruikers</p>
        <p class="text-2xl font-semibold text-gray-900">{{ $users->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Totaal Producten</p>
        <p class="text-2xl font-semibold text-gray-900">{{ $products->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Totaal Klanten</p>
        <p class="text-2xl font-semibold text-gray-900">{{ $customers->count() }}</p>
    </div>
</div>
@endsection
