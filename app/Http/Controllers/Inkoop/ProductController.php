<?php

namespace App\Http\Controllers\Inkoop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Check if user has purchase access
     */
    private function checkPurchaseAccess()
    {
        $user = auth()->user();

        // Check op basis van department
        if ($user->department && strtolower($user->department->name) === 'purchase') {
            return true;
        }

        // Check op basis van role
        if ($user->role === 'manager' || $user->role === 'admin') {
            return true;
        }

        // Extra: check role 'inkoop' als die bestaat
        if ($user->role === 'inkoop') {
            return true;
        }

        return false;
    }

    public function index(Request $request)
    {
        if (!$this->checkPurchaseAccess()) {
            abort(403, 'Alleen inkoop medewerkers (Purchase department), managers en admins hebben toegang.');
        }

        $query = Product::query();

        // Zoeken
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
        }

        // Filter op voorraadstatus
        if ($request->has('stock_status') && $request->stock_status) {
            if ($request->stock_status === 'available') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            } elseif ($request->stock_status === 'low_stock') {
                $query->where('stock', '<=', \DB::raw('min_stock'));
            }
        }

        $products = $query->latest()->paginate(20);

        return view('inkoop.products.index', compact('products'));
    }

    public function create()
    {
        if (!$this->checkPurchaseAccess()) {
            abort(403, 'Alleen inkoop medewerkers (Purchase department), managers en admins kunnen producten aanmaken.');
        }

        return view('inkoop.products.create');
    }

    public function store(Request $request)
    {
        if (!$this->checkPurchaseAccess()) {
            abort(403, 'Alleen inkoop medewerkers (Purchase department), managers en admins kunnen producten aanmaken.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('inkoop.products.index')
            ->with('success', 'Product succesvol aangemaakt.');
    }

    public function edit(Product $product)
    {
        if (!$this->checkPurchaseAccess()) {
            abort(403, 'Alleen inkoop medewerkers (Purchase department), managers en admins kunnen producten bewerken.');
        }

        return view('inkoop.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if (!$this->checkPurchaseAccess()) {
            abort(403, 'Alleen inkoop medewerkers (Purchase department), managers en admins kunnen producten bewerken.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Oude afbeelding verwijderen
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('inkoop.products.index')
            ->with('success', 'Product succesvol bijgewerkt.');
    }

    public function destroy(Product $product)
    {
        if (!$this->checkPurchaseAccess()) {
            abort(403, 'Alleen inkoop medewerkers (Purchase department), managers en admins kunnen producten verwijderen.');
        }

        // Check of er nog bestellingen zijn voor dit product
        if ($product->purchaseOrderItems()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Dit product kan niet worden verwijderd omdat het al in bestellingen is gebruikt.');
        }

        // Afbeelding verwijderen
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('inkoop.products.index')
            ->with('success', 'Product succesvol verwijderd.');
    }

    public function updateStock(Request $request, Product $product)
    {
        if (!$this->checkPurchaseAccess()) {
            abort(403, 'Alleen inkoop medewerkers (Purchase department), managers en admins kunnen voorraad bijwerken.');
        }

        $request->validate([
            'quantity' => 'required|integer',
            'type' => 'required|in:in,out,adjustment',
            'reason' => 'required|string|max:255'
        ]);

        if ($request->type === 'in') {
            $product->stock += $request->quantity;
        } elseif ($request->type === 'out') {
            $product->stock -= $request->quantity;
        }

        $product->save();

        return redirect()->back()
            ->with('success', 'Voorraad succesvol bijgewerkt.');
    }

    public function lowStock()
    {
        if (!$this->checkPurchaseAccess()) {
            abort(403, 'Alleen inkoop medewerkers (Purchase department), managers en admins hebben toegang.');
        }

        $products = Product::where('stock', '<=', \DB::raw('min_stock'))->get();
        return view('inkoop.products.low-stock', compact('products'));
    }

    public function deleteImage(Product $product)
    {
        if (!$this->checkPurchaseAccess()) {
            abort(403, 'Alleen inkoop medewerkers (Purchase department), managers en admins kunnen afbeeldingen verwijderen.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
            $product->update(['image' => null]);
        }

        return redirect()->back()
            ->with('success', 'Afbeelding succesvol verwijderd.');
    }
}
