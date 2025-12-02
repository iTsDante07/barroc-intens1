<?php

namespace App\Http\Controllers\Inkoop;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with(['user', 'supplier'])
            ->latest()
            ->paginate(20);

        return view('inkoop.products.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::active()->get();
        $suppliers = Supplier::active()->get();

        return view('inkoop.purchase-orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        // Bestelnummer genereren
        $orderNumber = 'PO-' . date('Ymd') . '-' . str_pad(PurchaseOrder::count() + 1, 4, '0', STR_PAD_LEFT);

        $order = PurchaseOrder::create([
            'order_number' => $orderNumber,
            'user_id' => auth()->id(),
            'supplier_id' => $validated['supplier_id'],
            'total_amount' => 0, // Wordt hierna berekend
            'notes' => $validated['notes'] ?? null,
        ]);

        $totalAmount = 0;

        // Items toevoegen
        foreach ($validated['items'] as $itemData) {
            $itemTotal = $itemData['quantity'] * $itemData['unit_price'];
            $totalAmount += $itemTotal;

            $order->items()->create([
                'product_id' => $itemData['product_id'],
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'],
                'total_price' => $itemTotal,
            ]);
        }

        // Totaalbedrag bijwerken en goedkeuring controleren
        $order->total_amount = $totalAmount;
        $order->checkApprovalRequirement();
        $order->save();

        $message = $order->needs_approval
            ? 'Bestelling aangemaakt en wacht op goedkeuring (bedrag > €5.000)'
            : 'Bestelling succesvol aangemaakt.';

        return redirect()->route('purchase-orders.index')
            ->with('success', $message);
    }

    public function approve(PurchaseOrder $order)
    {
        if (!auth()->user()->hasRole('manager')) {
            abort(403, 'Alleen managers kunnen bestellingen goedkeuren.');
        }

        return view('inkoop.orders.approve', compact('order'));
    }

    public function processApproval(Request $request, PurchaseOrder $order)
    {
        if (!auth()->user()->hasRole('manager')) {
            abort(403, 'Alleen managers kunnen bestellingen goedkeuren.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string'
        ]);

        if ($request->action === 'approve') {
            $order->approve(auth()->user());
            $message = 'Bestelling goedgekeurd.';
        } else {
            $order->reject(auth()->user());
            $message = 'Bestelling afgekeurd.';
        }

        return redirect()->route('purchase-orders.index')
            ->with('success', $message);
    }
}
