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
        'user_id', // Voeg deze toe
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

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateInvoiceNumber()
    {
        $year = date('Y');
        $lastInvoice = self::where('invoice_number', 'like', "F{$year}-%")->latest()->first();

        if ($lastInvoice) {
            $lastNumber = intval(explode('-', $lastInvoice->invoice_number)[1]);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "F{$year}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
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
