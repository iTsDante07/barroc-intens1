<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'quote_number',
        'subtotal',
        'vat_amount',
        'total_amount',
        'status',
        'valid_until',
        'notes',
        'terms'
    ];

    protected $attributes = [
        'subtotal' => 0,
        'vat_amount' => 0,
        'total_amount' => 0,
        'status' => 'concept'
    ];

    protected $casts = [
        'valid_until' => 'date',
        'subtotal' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(QuoteProduct::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function canCreateInvoice()
    {
        return $this->status === 'geaccepteerd' && $this->invoices->isEmpty();
    }

    public static function generateQuoteNumber()
    {
        $year = date('Y');
        $lastQuote = self::where('quote_number', 'like', "Q{$year}-%")->latest()->first();

        if ($lastQuote) {
            $lastNumber = intval(explode('-', $lastQuote->quote_number)[1]);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "Q{$year}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function calculateTotals()
    {
        $subtotal = $this->products->sum('total_price');
        $vatAmount = $subtotal * 0.21; // 21% BTW
        $totalAmount = $subtotal + $vatAmount;

        $this->update([
            'subtotal' => $subtotal,
            'vat_amount' => $vatAmount,
            'total_amount' => $totalAmount
        ]);

        return $this;
    }
}
