<?php

// app/Models/LeaseContract.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaseContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_number',
        'customer_id',
        'start_date',
        'end_date',
        'billing_frequency',
        'monthly_amount',
        'status',
        'terms',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'monthly_amount' => 'decimal:2'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(LeaseContractItem::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'lease_contract_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Automatically generate contract number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contract) {
            if (!$contract->contract_number) {
                $year = date('Y');
                $count = LeaseContract::whereYear('created_at', $year)->count() + 1;
                $contract->contract_number = 'LC-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    // Scope for active contracts
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Create an invoice for this contract
    public function createInvoice($period = null)
    {
        // Use the centralized invoice number generator
        $invoiceNumber = Invoice::generateInvoiceNumber('lease');

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'customer_id' => $this->customer_id,
            'lease_contract_id' => $this->id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
            'subtotal' => $this->monthly_amount,
            'vat_amount' => $this->monthly_amount * 0.21,
            'total_amount' => $this->monthly_amount * 1.21,
            'status' => 'concept',
            'notes' => "Factuur voor lease contract {$this->contract_number}" . ($period ? " - Periode: {$period}" : ''),
            'user_id' => auth()->id()
        ]);

        // Add items to the invoice
        foreach ($this->items as $item) {
            $invoice->items()->create([
                'description' => $item->description,
                'quantity' => $item->type === 'coffee' ? $item->coffee_bags_per_month : $item->quantity,
                'unit_price' => $item->monthly_price,
                'total_price' => ($item->type === 'coffee' ? $item->coffee_bags_per_month : $item->quantity) * $item->monthly_price
            ]);
        }

        return $invoice;
    }

    // Check if contract is active
    public function isActive()
    {
        return $this->status === 'active';
    }

    // Calculate remaining months
    public function remainingMonths()
    {
        if (!$this->end_date) {
            return null; // Infinite contract
        }

        $now = now();
        $end = $this->end_date;

        if ($end <= $now) {
            return 0;
        }

        return $now->diffInMonths($end);
    }
}
