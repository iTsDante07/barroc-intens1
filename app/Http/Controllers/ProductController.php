<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Zoekfunctionaliteit
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        // Categorie filter
        if ($request->has('category') && $request->category != '') {
            $query->byCategory($request->category);
        }

        // Voorraad status filter
        if ($request->has('stock_status') && $request->stock_status != '') {
            $query->byStockStatus($request->stock_status);
        }

        // Sortering (optioneel - toegevoegd voor betere UX)
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');

        $validSorts = ['name', 'price', 'stock', 'created_at'];
        $validDirections = ['asc', 'desc'];

        if (in_array($sort, $validSorts) && in_array($direction, $validDirections)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('name');
        }

        $products = $query->paginate(12)->withQueryString();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
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
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
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
