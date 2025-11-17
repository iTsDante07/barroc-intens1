@extends('components.layouts.app')

@section('title', 'Producten Beheer')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Producten Beheer</h1>
        <a href="{{ route('products.create') }}" class="bg-yellow-500 text-black px-4 py-2 rounded hover:bg-yellow-600 transition">
            Nieuw Product
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form action="{{ route('products.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Zoeken</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Naam, beschrijving, merk..."
                       class="border border-gray-300 rounded px-3 py-2 w-64">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Voorraadstatus</label>
                <select name="stock_status" class="border border-gray-300 rounded px-3 py-2">
                    <option value="">Alle</option>
                    <option value="available" {{ request('stock_status') === 'available' ? 'selected' : '' }}>Leverbaar</option>
                    <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Lage voorraad</option>
                    <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Uit voorraad</option>
                </select>
            </div>

            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Filteren
                </button>
                <a href="{{ route('products.index') }}" class="text-gray-600 ml-2 hover:text-gray-800">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Producten Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voorraad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prijs</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                        <div class="text-sm text-gray-500">{{ $product->brand }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->sku }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $product->stock }}</div>
                        <div class="text-xs text-gray-500">Min: {{ $product->min_stock }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€{{ number_format($product->price, 2, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->stock <= 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Uit voorraad</span>
                        @elseif($product->isLowStock)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Lage voorraad</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Leverbaar</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:text-blue-900 mr-3">Bewerken</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je dit product wilt verwijderen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Verwijderen</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
