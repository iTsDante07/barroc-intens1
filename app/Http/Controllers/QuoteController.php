<?php
namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Customer;
use App\Models\Product;
use App\Models\QuoteProduct;
use Illuminate\Http\Request;
use PDF;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with(['customer', 'user'])->latest()->get();
        return view('quotes.index', compact('quotes'));
    }

    public function create()
    {
        $customers = Customer::where('bkr_checked', true)
                           ->where('bkr_approved', true)
                           ->get();
        $products = Product::all();

        return view('quotes.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'valid_until' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);

        // Check if customer is BKR approved
        $customer = Customer::findOrFail($validated['customer_id']);
        if (!$customer->bkr_approved) {
            return redirect()->back()
                ->with('error', 'Offerte kan niet worden gemaakt voor een BKR afgekeurde klant.')
                ->withInput();
        }

        // Check if at least one product is selected
        $hasProducts = false;
        $productsData = [];
        $initialSubtotal = 0;

        foreach ($request->products as $productId => $data) {
            if (isset($data['quantity']) && $data['quantity'] > 0) {
                $hasProducts = true;
                $product = Product::find($data['product_id']);
                $quantity = $data['quantity'];
                $totalPrice = $product->price * $quantity;
                $initialSubtotal += $totalPrice;

                $productsData[] = [
                    'product_id' => $data['product_id'],
                    'quantity' => $quantity
                ];
            }
        }

        if (!$hasProducts) {
            return redirect()->back()
                ->with('error', 'Selecteer minimaal één product.')
                ->withInput();
        }

        $initialVat = $initialSubtotal * 0.21;
        $initialTotal = $initialSubtotal + $initialVat;

        // Create quote with initial totals
        $quote = Quote::create([
            'customer_id' => $validated['customer_id'],
            'user_id' => auth()->id(),
            'quote_number' => Quote::generateQuoteNumber(),
            'subtotal' => $initialSubtotal,
            'vat_amount' => $initialVat,
            'total_amount' => $initialTotal,
            'valid_until' => $validated['valid_until'],
            'notes' => $validated['notes'],
            'terms' => 'Standaard betalingsvoorwaarden: 30 dagen netto.',
            'status' => 'concept'
        ]);

        // Add products to quote
        foreach ($productsData as $productData) {
            $product = Product::find($productData['product_id']);
            $unitPrice = $product->price;
            $totalPrice = $unitPrice * $productData['quantity'];

            QuoteProduct::create([
                'quote_id' => $quote->id,
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice
            ]);
        }


        // Calculate totals
        $quote->calculateTotals();

        return redirect()->route('quotes.show', $quote)
            ->with('success', 'Offerte succesvol aangemaakt!');
    }

    public function show(Quote $quote)
    {
        $quote->load(['customer', 'user', 'products.product']);
        return view('quotes.show', compact('quote'));
    }

    public function edit(Quote $quote)
    {
        if ($quote->status !== 'concept') {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Alleen concept offertes kunnen worden bewerkt.');
        }

        $customers = Customer::where('bkr_checked', true)
                           ->where('bkr_approved', true)
                           ->get();
        $products = Product::all();

        $quote->load('products');

        return view('quotes.edit', compact('quote', 'customers', 'products'));
    }

    public function update(Request $request, Quote $quote)
    {
        if ($quote->status !== 'concept') {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Alleen concept offertes kunnen worden bewerkt.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'valid_until' => 'required|date|after:today',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $quote->update([
            'customer_id' => $validated['customer_id'],
            'valid_until' => $validated['valid_until'],
            'notes' => $validated['notes'],
            'terms' => $validated['terms'] ?? $quote->terms,
        ]);

        // Remove existing products
        $quote->products()->delete();

        // Add updated products
        foreach ($validated['products'] as $productData) {
            $product = Product::find($productData['product_id']);
            $unitPrice = $product->price;
            $totalPrice = $unitPrice * $productData['quantity'];

            QuoteProduct::create([
                'quote_id' => $quote->id,
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice
            ]);
        }

        // Recalculate totals
        $quote->calculateTotals();

        return redirect()->route('quotes.show', $quote)
            ->with('success', 'Offerte succesvol bijgewerkt!');
    }

    public function destroy(Quote $quote)
    {
        if ($quote->status !== 'concept') {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Alleen concept offertes kunnen worden verwijderd.');
        }

        $quote->delete();

        return redirect()->route('quotes.index')
            ->with('success', 'Offerte succesvol verwijderd!');
    }

    public function send(Quote $quote)
    {
        $quote->update(['status' => 'verzonden']);

        return redirect()->route('quotes.show', $quote)
            ->with('success', 'Offerte succesvol verzonden!');
    }

    public function accept(Quote $quote)
    {
        $quote->update(['status' => 'geaccepteerd']);

        return redirect()->route('quotes.show', $quote)
            ->with('success', 'Offerte gemarkeerd als geaccepteerd!');
    }

    public function reject(Quote $quote)
    {
        $quote->update(['status' => 'afgewezen']);

        return redirect()->route('quotes.show', $quote)
            ->with('success', 'Offerte gemarkeerd als afgewezen!');
    }

    public function downloadPdf(Quote $quote)
    {
        $quote->load(['customer', 'user', 'products.product']);

        $pdf = PDF::loadView('quotes.pdf', compact('quote'));

        return $pdf->download("offerte-{$quote->quote_number}.pdf");
    }

    public function duplicate(Quote $quote)
    {
        $newQuote = $quote->replicate();
        $newQuote->quote_number = Quote::generateQuoteNumber();
        $newQuote->status = 'concept';
        $newQuote->push();

        // Duplicate products
        foreach ($quote->products as $product) {
            $newProduct = $product->replicate();
            $newProduct->quote_id = $newQuote->id;
            $newProduct->push();
        }

        $newQuote->calculateTotals();

        return redirect()->route('quotes.show', $newQuote)
            ->with('success', 'Offerte succesvol gedupliceerd!');
    }
}
