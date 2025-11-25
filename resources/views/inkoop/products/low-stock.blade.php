@extends('components.layouts.app')

@section('title', 'Lage Voorraad - Inkoop')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Lage Voorraad</h1>
        <div class="space-x-3">
            <a href="{{ route('inkoop.products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                Terug naar Producten
            </a>
        </div>
    </div>

    <!-- Producten Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($products->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-red-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Huidige Voorraad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Minimum Voorraad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Tekort</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Acties</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $product)
                <tr class="hover:bg-red-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                        <div class="text-sm text-gray-500">{{ $product->brand }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->sku }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">
                        {{ $product->stock }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $product->min_stock }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">
                        {{ max(0, $product->min_stock - $product->stock) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('inkoop.products.edit', $product) }}" class="text-blue-600 hover:text-blue-900 mr-3">Bewerken</a>
                        <form action="{{ route('inkoop.products.update-stock', $product) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="quantity" value="{{ max(1, $product->min_stock - $product->stock + 5) }}">
                            <input type="hidden" name="type" value="in">
                            <input type="hidden" name="reason" value="Aanvullen lage voorraad">
                            <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Voorraad aanvullen?')">
                                Voorraad Aanvullen
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Geen producten met lage voorraad</h3>
            <p class="text-gray-500">Alle producten hebben voldoende voorraad.</p>
        </div>
        @endif
    </div>
</div>
@endsection
