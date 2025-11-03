<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Klant succesvol aangemaakt!');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Klant succesvol bijgewerkt!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Klant succesvol verwijderd!');
    }

    public function checkBkr(Customer $customer)
    {
        // Simuleer BKR check (in werkelijkheid zou dit een API call zijn)
        $isApproved = rand(0, 1) === 1; // 50% kans op goedkeuring

        $customer->update([
            'bkr_checked' => true,
            'bkr_approved' => $isApproved,
            'bkr_notes' => $isApproved
                ? 'BKR check uitgevoerd op ' . now()->format('d-m-Y H:i') . ' - GOEDGEKEURD'
                : 'BKR check uitgevoerd op ' . now()->format('d-m-Y H:i') . ' - AFGEKEURD: Betalingsachterstand gevonden'
        ]);

        $message = $isApproved
            ? 'BKR check voltooid: Klant is GOEDGEKEURD'
            : 'BKR check voltooid: Klant is AFGEKEURD';

        return redirect()->route('customers.show', $customer)
            ->with('success', $message);
    }
    public function createForCustomer(Customer $customer)
    {
        if (!$customer->bkr_approved) {
            return redirect()->route('customers.show', $customer)
                ->with('error', 'Offerte kan niet worden gemaakt voor een BKR afgekeurde klant.');
        }

        $products = Product::all();

        return view('quotes.create', compact('customer', 'products'));
    }
    public function bkrCheck()
    {
        $customersNeedingCheck = Customer::where('bkr_checked', false)->get();
        $approvedCount = Customer::where('bkr_checked', true)->where('bkr_approved', true)->count();
        $rejectedCount = Customer::where('bkr_checked', true)->where('bkr_approved', false)->count();
        $notCheckedCount = Customer::where('bkr_checked', false)->count();

        return view('customers.bkr-check', compact(
            'customersNeedingCheck',
            'approvedCount',
            'rejectedCount',
            'notCheckedCount'
        ));
    }

    public function quickBkrCheck(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'kvk_number' => 'required|string|max:50'
        ]);

        // Simuleer BKR check resultaat
        $isApproved = rand(0, 1) === 1;

        $result = [
            'company_name' => $request->company_name,
            'kvk_number' => $request->kvk_number,
            'approved' => $isApproved,
            'message' => $isApproved
                ? 'BKR check: GOEDGEKEURD - Geen bezwaren gevonden'
                : 'BKR check: AFGEKEURD - Betalingsachterstanden gevonden',
            'checked_at' => now()->format('d-m-Y H:i')
        ];

        return redirect()->route('customers.bkr-check')
            ->with('bkr_result', $result)
            ->with('success', 'BKR check uitgevoerd voor ' . $request->company_name);
    }
}
