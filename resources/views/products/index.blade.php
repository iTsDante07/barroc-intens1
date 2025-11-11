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

    <!-- Categorie Filter -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4">
                <div class="flex space-x-2 overflow-x-auto pb-2">
                    <button class="px-4 py-2 bg-yellow-500 text-black rounded-full font-semibold text-sm whitespace-nowrap">
                        Alle Producten
                    </button>
                    <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full font-medium text-sm hover:bg-gray-200 transition-colors whitespace-nowrap">
                        Koffiemachines
                    </button>
                    <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full font-medium text-sm hover:bg-gray-200 transition-colors whitespace-nowrap">
                        Koffiebonen
                    </button>
                    <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full font-medium text-sm hover:bg-gray-200 transition-colors whitespace-nowrap">
                        Accessoires
                    </button>
                </div>
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
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 leading-tight">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>

                    <div class="flex items-center justify-between mb-3">
                        <span class="text-2xl font-bold text-yellow-600">€{{ number_format($product->price, 2, ',', '.') }}</span>
                        <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
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
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-8V4a1 1 0 00-1-1h-2a1 1 0 00-1 1v1M9 7h6"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Geen producten beschikbaar</h3>
            <p class="text-gray-600 mb-6">Er zijn momenteel geen producten in de catalogus.</p>
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
