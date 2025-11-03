<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Quote;
use App\Models\Customer;
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

    public function createFromQuote(Quote $quote)
    {
        if ($quote->status !== 'geaccepteerd') {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Alleen geaccepteerde offertes kunnen worden omgezet in facturen.');
        }

        return view('invoices.create', compact('quote'));
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

        // Create invoice
        $invoice = Invoice::create([
            'quote_id' => $quote->id,
            'customer_id' => $quote->customer_id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'subtotal' => $quote->subtotal,
            'vat_amount' => $quote->vat_amount,
            'total_amount' => $quote->total_amount,
            'notes' => $validated['notes'],
            'status' => 'concept'
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
}
