@extends('components.layouts.app')

@section('title', 'Producten Beheer - Inkoop')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Producten Beheer</h1>
            <p class="text-gray-600 mt-1">Beheer alle producten en voorraad</p>
        </div>
        <a href="{{ route('inkoop.products.create') }}" class="bg-yellow-500 text-black px-4 py-2 rounded-lg hover:bg-yellow-600 transition font-semibold">
            Nieuw Product
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('inkoop.products.index') }}" class="flex flex-col sm:flex-row gap-4">
            <!-- Zoeken -->
            <div class="flex-1">
                <input type="text"
                       name="search"
                       placeholder="Zoek producten..."
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
            </div>

            <!-- Voorraad Filter -->
            <select name="stock_status" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                <option value="">Alle voorraad</option>
                <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Op voorraad</option>
                <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Lage voorraad</option>
                <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Uit voorraad</option>
            </select>

            <button type="submit" class="bg-yellow-500 text-black px-6 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition">
                Filter
            </button>

            @if(request()->anyFilled(['search', 'stock_status']))
                <a href="{{ route('inkoop.products.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition flex items-center">
                    Wis Filters
                </a>
            @endif
        </form>
    </div>

    <!-- Producten Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voorraad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min. Voorraad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prijs</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-lg object-cover mr-3">
                            @else
                                <div class="h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($product->description, 50) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->stock }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->min_stock }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€{{ number_format($product->price, 2, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->stock <= 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Uit voorraad</span>
                        @elseif($product->stock <= $product->min_stock)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Lage voorraad</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Op voorraad</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('inkoop.products.edit', $product) }}"
                               class="text-blue-600 hover:text-blue-900 px-3 py-1 rounded border border-blue-200 hover:bg-blue-50 transition">
                                Bewerken
                            </a>
                            <form action="{{ route('inkoop.products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('Weet je zeker dat je dit product wilt verwijderen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-900 px-3 py-1 rounded border border-red-200 hover:bg-red-50 transition">
                                    Verwijderen
                                </button>
                            </form>
                        </div>
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

    <!-- Quick Actions -->
    <div class="mt-6 flex space-x-4">
        <a href="{{ route('inkoop.products.low-stock') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition font-semibold">
            Lage Voorraad ({{ \App\Models\Product::where('stock', '<=', \DB::raw('min_stock'))->count() }})
        </a>
    </div>
</div>
@endsection
