@extends('components.layouts.app')

@section('title', 'Inkoop Dashboard')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-semibold text-gray-800">Inkoop Dashboard</h1>
        <p class="text-gray-600">Overzicht van producten en voorraad.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
            <!-- Totaal Producten -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Totaal Producten</h2>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalProducts }}</p>
            </div>

            <!-- Lage Voorraad -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Lage Voorraad</h2>
                <p class="text-3xl font-bold text-yellow-500 mt-2">{{ $lowStockCount }}</p>
            </div>

            <!-- Geen Voorraad -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Geen Voorraad</h2>
                <p class="text-3xl font-bold text-red-500 mt-2">{{ $outOfStockCount }}</p>
            </div>

            <!-- Totale Voorraadwaarde -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Totale Voorraadwaarde</h2>
                <p class="text-3xl font-bold text-green-500 mt-2">€{{ number_format($totalStockValue, 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Producten met Lage Voorraad -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Producten met Lage Voorraad</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Product</th>
                                <th class="py-2 px-4 border-b">Voorraad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $product)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $product->name }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $product->stock }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-4 px-4 text-center text-gray-500">Geen producten met lage voorraad.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Producten Zonder Voorraad -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Producten Zonder Voorraad</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Product</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($outOfStockProducts as $product)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $product->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-4 px-4 text-center text-gray-500">Geen producten zonder voorraad.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
