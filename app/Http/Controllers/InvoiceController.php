<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Quote;
use App\Models\Customer;
use App\Models\LeaseContract;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use PDF;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['customer', 'quote'])->latest()->get();
        return view('invoices.index', compact('invoices'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'quote_id' => 'required|exists:quotes,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'notes' => 'nullable|string',
        ]);

        $quote = Quote::with('products.product')->findOrFail($validated['quote_id']);

        // Use the centralized invoice number generator
        $invoiceNumber = Invoice::generateInvoiceNumber('regular');

        // Create invoice
        $invoice = Invoice::create([
            'quote_id' => $quote->id,
            'customer_id' => $quote->customer_id,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'subtotal' => $quote->subtotal,
            'vat_amount' => $quote->vat_amount,
            'total_amount' => $quote->total_amount,
            'notes' => $validated['notes'],
            'status' => 'concept',
            'user_id' => auth()->id()
        ]);

        // Add items from quote
        foreach ($quote->products as $quoteProduct) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $quoteProduct->product->name,
                'quantity' => $quoteProduct->quantity,
                'unit_price' => $quoteProduct->unit_price,
                'total_price' => $quoteProduct->total_price
            ]);
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Factuur succesvol aangemaakt!');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'quote', 'items']);
        return view('invoices.show', compact('invoice'));
    }

    public function send(Invoice $invoice)
    {
        $invoice->update(['status' => 'verzonden']);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Factuur succesvol verzonden!');
    }

    public function markPaid(Invoice $invoice)
    {
        $invoice->update(['status' => 'betaald']);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Factuur gemarkeerd als betaald!');
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['customer', 'items']);

        $pdf = PDF::loadView('invoices.pdf', compact('invoice'));

        return $pdf->download("factuur-{$invoice->invoice_number}.pdf");
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status !== 'concept') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Alleen concept facturen kunnen worden verwijderd.');
        }

        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Factuur succesvol verwijderd!');
    }
    public function createFromQuote(Quote $quote)
    {
        if (!$quote->canCreateInvoice()) {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Alleen geaccepteerde offertes zonder bestaande factuur kunnen worden omgezet in facturen.');
        }

        return view('invoices.create-from-quote', compact('quote'));
    }



    public function storeFromQuote(Request $request, Quote $quote)
    {
        if (!$quote->canCreateInvoice()) {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Alleen geaccepteerde offertes zonder bestaande factuur kunnen worden omgezet in facturen.');
        }

        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'notes' => 'nullable|string',
        ]);

        try {
            $invoice = Invoice::createFromQuote($quote, $validated);

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Factuur succesvol aangemaakt vanuit offerte!');

        } catch (\Exception $e) {
            return redirect()->route('quotes.show', $quote)
                ->with('error', $e->getMessage());
        }
    }

    public function createSimple(Request $request)
    {
        if (!auth()->user()->department ||
            (auth()->user()->department->name !== 'Finance' &&
            auth()->user()->role !== 'manager' &&
            auth()->user()->role !== 'admin')) {
            abort(403, 'Alleen finance medewerkers, managers en admins hebben toegang.');
        }

        $customers = Customer::all();
        $contracts = LeaseContract::where('status', 'active')->get(); // Zorg dat deze lijn bestaat!

        // Als er een contract_id in de query string zit, laad dat contract
        $selectedContract = null;
        if ($request->has('contract_id')) {
            $selectedContract = LeaseContract::find($request->contract_id);
        }

        return view('invoices.create-simple', compact('customers', 'contracts', 'selectedContract'));
    }

// app/Http/Controllers/InvoiceController.php
    public function storeSimple(Request $request)
    {
        if (!auth()->user()->department ||
            (auth()->user()->department->name !== 'Finance' &&
            auth()->user()->role !== 'manager' &&
            auth()->user()->role !== 'admin')) {
            abort(403, 'Alleen finance medewerkers, managers en admins hebben toegang.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'notes' => 'nullable|string'
        ]);

        // Use the centralized invoice number generator
        $invoiceNumber = Invoice::generateInvoiceNumber('regular');

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'customer_id' => $validated['customer_id'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'subtotal' => $validated['amount'],
            'vat_amount' => $validated['amount'] * 0.21,
            'total_amount' => $validated['amount'] * 1.21,
            'status' => 'concept',
            'notes' => $validated['notes'],
            'user_id' => auth()->id()
        ]);

        // Add item
        $invoice->items()->create([
            'description' => $validated['description'],
            'quantity' => 1,
            'unit_price' => $validated['amount'],
            'total_price' => $validated['amount']
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Factuur voor aansluitkosten succesvol aangemaakt.');
    }
}
