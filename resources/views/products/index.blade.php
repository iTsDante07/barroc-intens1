@extends('components.layouts.app')

@section('title', 'Producten - Barroc Intens')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Productcatalogus</h1>
                    <p class="text-gray-600 mt-1">Koffiemachines en accessoires</p>
                </div>
                <div class="flex space-x-3">
                    <!-- Snelle acties voor sales -->
                    <a href="{{ route('customers.create') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-600 transition-colors text-sm">
                        Nieuwe Klant
                    </a>
                    <a href="{{ route('quotes.create') }}" class="bg-yellow-500 text-black px-4 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors text-sm">
                        Nieuwe Offerte
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter en Zoekbalk -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4">
                <form method="GET" action="{{ route('products.index') }}" class="flex flex-col sm:flex-row gap-4">
                    <!-- Zoekbalk -->
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text"
                                   name="search"
                                   placeholder="Zoek producten..."
                                   value="{{ request('search') }}"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Categorie Filter -->
                    <div class="flex gap-2">
                        <select name="category" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">Alle categorieën</option>
                            @foreach(App\Models\Product::CATEGORIES as $key => $category)
                                <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>

                        <select name="stock_status" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">Alle voorraad</option>
                            <option value="op_voorraad" {{ request('stock_status') == 'op_voorraad' ? 'selected' : '' }}>Op voorraad</option>
                            <option value="lage_voorraad" {{ request('stock_status') == 'lage_voorraad' ? 'selected' : '' }}>Lage voorraad</option>
                            <option value="uit_voorraad" {{ request('stock_status') == 'uit_voorraad' ? 'selected' : '' }}>Uit voorraad</option>
                        </select>

                        <button type="submit" class="bg-yellow-500 text-black px-4 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
                            Filter
                        </button>

                        @if(request()->hasAny(['search', 'category', 'stock_status']))
                            <a href="{{ route('products.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors flex items-center">
                                Wis filters
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Resultaat info -->
                @if(request()->hasAny(['search', 'category', 'stock_status']))
                    <div class="mt-3 text-sm text-gray-600">
                        @php
                            $resultCount = $products->count();
                            $totalCount = App\Models\Product::count();
                        @endphp
                        {{ $resultCount }} van de {{ $totalCount }} producten getoond

                        @if(request('search'))
                            voor "{{ request('search') }}"
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($products as $product)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200">
                <!-- Product Image -->
                <div class="h-48 bg-gray-100 rounded-t-xl flex items-center justify-center overflow-hidden">
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                         class="h-full w-full object-cover hover:scale-105 transition-transform duration-200">
                    @else
                    <div class="text-gray-400 text-center p-4">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm block">Geen afbeelding</span>
                    </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-semibold text-gray-900 text-lg leading-tight flex-1">{{ $product->name }}</h3>
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full ml-2 whitespace-nowrap">
                            {{ App\Models\Product::CATEGORIES[$product->category] ?? $product->category }}
                        </span>
                    </div>

                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>

                    <div class="flex items-center justify-between mb-3">
                        <span class="text-2xl font-bold text-yellow-600">€{{ number_format($product->price, 2, ',', '.') }}</span>
                        <span class="text-sm px-2 py-1 rounded-full
                            @if($product->stock > 10) bg-green-100 text-green-800
                            @elseif($product->stock > 0) bg-orange-100 text-orange-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $product->stock }} op voorraad
                        </span>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex space-x-2">
                        <button class="flex-1 bg-yellow-500 text-black py-2 px-3 rounded-lg font-semibold hover:bg-yellow-600 transition-colors text-sm text-center"
                                onclick="addToQuote({{ $product->id }})">
                            Toevoegen
                        </button>
                        <a href="{{ route('products.show', $product) }}"
                           class="bg-gray-100 text-gray-700 py-2 px-3 rounded-lg font-medium hover:bg-gray-200 transition-colors text-sm flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="mt-6">
            {{ $products->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-8V4a1 1 0 00-1-1h-2a1 1 0 00-1 1v1M9 7h6"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Geen producten gevonden</h3>
            <p class="text-gray-600 mb-6">
                @if(request()->hasAny(['search', 'category', 'stock_status']))
                    Probeer uw zoekcriteria aan te passen.
                @else
                    Er zijn momenteel geen producten in de catalogus.
                @endif
            </p>
            @if(request()->hasAny(['search', 'category', 'stock_status']))
                <a href="{{ route('products.index') }}" class="bg-yellow-500 text-black px-6 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
                    Toon alle producten
                </a>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Floating Quote Button -->
<div class="fixed bottom-6 right-6">
    <div class="bg-yellow-500 text-black rounded-full p-4 shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-110">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
    </div>
</div>

<script>
function addToQuote(productId) {
    alert('Product toegevoegd aan offerte!');

    fetch('/quotes/add-product', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    }).then(response => response.json())
      .then(data => {
          if(data.success) {
              updateQuoteCounter();
          }
      });
}

function updateQuoteCounter() {
    console.log('Quote updated');
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@media (max-width: 768px) {
    .grid-cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }
}

button, a {
    -webkit-tap-highlight-color: transparent;
}
</style>
@endsection
