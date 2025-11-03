<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'customer_id',
        'user_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'subtotal',
        'vat_amount',
        'total_amount',
        'status',
        'notes'
    ];

    protected $attributes = [
        'subtotal' => 0,
        'vat_amount' => 0,
        'total_amount' => 0,
        'status' => 'concept'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public static function generateInvoiceNumber()
    {
        $year = date('Y');

        // Zoek het hoogste bestaande factuurnummer voor dit jaar
        $lastInvoice = self::where('invoice_number', 'like', "F{$year}-%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            // Extraheer het nummer deel en verhoog met 1
            $parts = explode('-', $lastInvoice->invoice_number);
            $lastNumber = intval($parts[1] ?? 0);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $invoiceNumber = "F{$year}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Dubbele controle: zorg ervoor dat het nummer echt uniek is
        while (self::where('invoice_number', $invoiceNumber)->exists()) {
            $newNumber++;
            $invoiceNumber = "F{$year}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        return $invoiceNumber;
    }

    public static function createFromQuote(Quote $quote, array $data = [])
    {
        // Controleer of de offerte geaccepteerd is
        if ($quote->status !== 'geaccepteerd') {
            throw new \Exception('Alleen geaccepteerde offertes kunnen worden omgezet in facturen.');
        }

        $invoice = self::create(array_merge([
            'quote_id' => $quote->id,
            'customer_id' => $quote->customer_id,
            'user_id' => auth()->id() ?? $quote->user_id,
            'invoice_number' => self::generateInvoiceNumber(),
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
            'subtotal' => $quote->subtotal,
            'vat_amount' => $quote->vat_amount,
            'total_amount' => $quote->total_amount,
            'status' => 'concept',
            'notes' => $quote->notes
        ], $data));

        foreach ($quote->products as $quoteProduct) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $quoteProduct->product->name,
                'quantity' => $quoteProduct->quantity,
                'unit_price' => $quoteProduct->unit_price,
                'total_price' => $quoteProduct->total_price
            ]);
        }

        return $invoice;
    }

    public function calculateTotals()
    {
        $subtotal = $this->items->sum('total_price');
        $vatAmount = $subtotal * 0.21;
        $totalAmount = $subtotal + $vatAmount;

        $this->update([
            'subtotal' => $subtotal,
            'vat_amount' => $vatAmount,
            'total_amount' => $totalAmount
        ]);

        return $this;
    }

    public function isOverdue()
    {
        return $this->status === 'verzonden' && $this->due_date->isPast();
    }
}
