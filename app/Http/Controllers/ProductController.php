<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Product::CATEGORIES;

        $products = Product::query()
            ->byCategory($request->category)
            ->search($request->search)
            ->byStockStatus($request->stock_status)
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Product::CATEGORIES;
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'category' => 'required|string|in:' . implode(',', array_keys(Product::CATEGORIES)),
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product succesvol aangemaakt!');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Product::CATEGORIES;
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'category' => 'required|string|in:' . implode(',', array_keys(Product::CATEGORIES)),
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Verwijder oude afbeelding
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Product succesvol bijgewerkt!');
    }

    public function destroy(Product $product)
    {
        // Verwijder afbeelding als die bestaat
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product succesvol verwijderd!');
    }

    // Nieuwe methode om alleen de afbeelding te verwijderen
    public function deleteImage(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
            $product->update(['image' => null]);

            return redirect()->route('products.show', $product)
                ->with('success', 'Afbeelding succesvol verwijderd!');
        }

        return redirect()->route('products.show', $product)
            ->with('warning', 'Geen afbeelding gevonden om te verwijderen.');
    }
}
