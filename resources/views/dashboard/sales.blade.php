@extends('components.layouts.app')

@section('title', 'Sales Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Sales Dashboard</h1>
    <p class="text-gray-600">Welkom, {{ auth()->user()->name }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-barroc-yellow text-black">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Producten</p>
                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Product::count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-barroc-yellow text-black">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Klanten</p>
                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Customer::count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Snelle Acties</h2>
        <div class="space-y-3">
            <a href="{{ route('products.create') }}" class="block w-full bg-barroc-yellow text-black text-center px-4 py-3 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
                Nieuw Product Toevoegen
            </a>
            <a href="{{ route('customers.create') }}" class="block w-full bg-black text-white text-center px-4 py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors">
                Nieuwe Klant Toevoegen
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Recente Activiteiten</h2>
        <p class="text-gray-600">Er zijn nog geen recente activiteiten.</p>
    </div>
</div>
@endsection
