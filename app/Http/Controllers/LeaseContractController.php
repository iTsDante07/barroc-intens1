<?php

namespace App\Http\Controllers;

use App\Models\LeaseContract;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class LeaseContractController extends Controller
{
    public function index(Request $request)
    {
        // Alleen finance, managers en admins
        if (!auth()->user()->department ||
            (auth()->user()->department->name !== 'Finance' &&
             auth()->user()->role !== 'manager' &&
             auth()->user()->role !== 'admin')) {
            abort(403, 'Alleen finance medewerkers, managers en admins hebben toegang.');
        }

        $query = LeaseContract::with(['customer', 'creator']);

        // Filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('customer_id') && $request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('contract_number', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', function($q) use ($request) {
                      $q->where('company_name', 'like', "%{$request->search}%")
                        ->orWhere('contact_name', 'like', "%{$request->search}%");
                  });
            });
        }

        $contracts = $query->latest()->paginate(20);
        $customers = Customer::all();
        $activeCount = LeaseContract::where('status', 'active')->count();
        $endedCount = LeaseContract::where('status', 'ended')->count();

        return view('finance.lease-contracts.index', compact(
            'contracts', 'customers', 'activeCount', 'endedCount'
        ));
    }

    public function create()
    {
        if (!auth()->user()->department ||
            (auth()->user()->department->name !== 'Finance' &&
             auth()->user()->role !== 'manager' &&
             auth()->user()->role !== 'admin')) {
            abort(403, 'Alleen finance medewerkers, managers en admins hebben toegang.');
        }

        $customers = Customer::where('bkr_approved', true)->get();
        $products = Product::all();

        return view('finance.lease-contracts.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->department ||
            (auth()->user()->department->name !== 'Finance' &&
             auth()->user()->role !== 'manager' &&
             auth()->user()->role !== 'admin')) {
            abort(403, 'Alleen finance medewerkers, managers en admins hebben toegang.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'billing_frequency' => 'required|in:monthly,quarterly,yearly',
            'terms' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.type' => 'required|in:machine,coffee,service',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.coffee_bags_per_month' => 'required_if:items.*.type,coffee|nullable|integer|min:0',
            'items.*.monthly_price' => 'required|numeric|min:0'
        ]);

        // Bereken totaal maandelijks bedrag
        $monthlyAmount = 0;
        foreach ($validated['items'] as $item) {
            $quantity = $item['type'] === 'coffee' ? $item['coffee_bags_per_month'] : $item['quantity'];
            $monthlyAmount += $quantity * $item['monthly_price'];
        }

        // Maak contract aan
        $contract = LeaseContract::create([
            'customer_id' => $validated['customer_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'billing_frequency' => $validated['billing_frequency'],
            'monthly_amount' => $monthlyAmount,
            'terms' => $validated['terms'],
            'notes' => $validated['notes'],
            'created_by' => auth()->id()
        ]);

        // Voeg items toe
        foreach ($validated['items'] as $itemData) {
            $contract->items()->create($itemData);
        }

        return redirect()->route('lease-contracts.show', $contract)
            ->with('success', 'Leasecontract succesvol aangemaakt.');
    }

    public function show(LeaseContract $contract)
    {
        if (!auth()->user()->department ||
            (auth()->user()->department->name !== 'Finance' &&
             auth()->user()->role !== 'manager' &&
             auth()->user()->role !== 'admin')) {
            abort(403, 'Alleen finance medewerkers, managers en admins hebben toegang.');
        }

        $contract->load(['customer', 'items.product', 'invoices', 'creator']);

        return view('finance.lease-contracts.show', compact('contract'));
    }

    public function edit(LeaseContract $contract)
    {
        if (!auth()->user()->department ||
            (auth()->user()->department->name !== 'Finance' &&
             auth()->user()->role !== 'manager' &&
             auth()->user()->role !== 'admin')) {
            abort(403, 'Alleen finance medewerkers, managers en admins hebben toegang.');
        }

        $customers = Customer::where('bkr_approved', true)->get();
        $products = Product::all();

        return view('finance.lease-contracts.edit', compact('contract', 'customers', 'products'));
    }

    public function update(Request $request, LeaseContract $contract)
    {
        if (!auth()->user()->department ||
            (auth()->user()->department->name !== 'Finance' &&
             auth()->user()->role !== 'manager' &&
             auth()->user()->role !== 'admin')) {
            abort(403, 'Alleen finance medewerkers, managers en admins hebben toegang.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'billing_frequency' => 'required|in:monthly,quarterly,yearly',
            'status' => 'required|in:active,ended,cancelled',
            'terms' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.type' => 'required|in:machine,coffee,service',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.coffee_bags_per_month' => 'required_if:items.*.type,coffee|nullable|integer|min:0',
            'items.*.monthly_price' => 'required|numeric|min:0'
        ]);

        // Bereken nieuw maandelijks bedrag
        $monthlyAmount = 0;
        foreach ($validated['items'] as $item) {
            $quantity = $item['type'] === 'coffee' ? $item['coffee_bags_per_month'] : $item['quantity'];
            $monthlyAmount += $quantity * $item['monthly_price'];
        }

        // Update contract
        $contract->update([
            'customer_id' => $validated['customer_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'billing_frequency' => $validated['billing_frequency'],
            'status' => $validated['status'],
            'monthly_amount' => $monthlyAmount,
            'terms' => $validated['terms'],
            'notes' => $validated['notes']
        ]);

        // Verwijder oude items en voeg nieuwe toe
        $contract->items()->delete();
        foreach ($validated['items'] as $itemData) {
            $contract->items()->create($itemData);
        }

        return redirect()->route('lease-contracts.show', $contract)
            ->with('success', 'Leasecontract succesvol bijgewerkt.');
    }

    public function destroy(LeaseContract $contract)
    {
        if (!auth()->user()->department ||
            (auth()->user()->department->name !== 'Finance' &&
             auth()->user()->role !== 'manager' &&
             auth()->user()->role !== 'admin')) {
            abort(403, 'Alleen finance medewerkers, managers en admins hebben toegang.');
        }

        // Check of er facturen zijn
        if ($contract->invoices()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Dit contract kan niet worden verwijderd omdat er al facturen voor zijn aangemaakt.');
        }

        $contract->delete();

        return redirect()->route('lease-contracts.index')
            ->with('success', 'Leasecontract succesvol verwijderd.');
    }

    public function createInvoice(LeaseContract $contract, Request $request)
    {
        if (!auth()->user()->department ||
            (auth()->user()->department->name !== 'Finance' &&
             auth()->user()->role !== 'manager' &&
             auth()->user()->role !== 'admin')) {
            abort(403, 'Alleen finance medewerkers, managers en admins hebben toegang.');
        }

        $period = $request->get('period', date('F Y'));
        $invoice = $contract->createInvoice($period);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Factuur succesvol aangemaakt voor lease contract ' . $contract->contract_number);
    }

    public function generateAllInvoices()
    {
        if (!auth()->user()->department ||
            (auth()->user()->department->name !== 'Finance' &&
             auth()->user()->role !== 'manager' &&
             auth()->user()->role !== 'admin')) {
            abort(403, 'Alleen finance medewerkers, managers en admins hebben toegang.');
        }

        // Haal alle actieve contracten op
        $contracts = LeaseContract::active()->get();
        $generated = 0;

        foreach ($contracts as $contract) {
            // Check of er al een factuur is voor deze maand
            $existingInvoice = $contract->invoices()
                ->whereMonth('invoice_date', now()->month)
                ->whereYear('invoice_date', now()->year)
                ->first();

            if (!$existingInvoice) {
                $contract->createInvoice(date('F Y'));
                $generated++;
            }
        }

        return redirect()->route('invoices.index')
            ->with('success', $generated . ' facturen succesvol aangemaakt voor lease contracten.');
    }
}
