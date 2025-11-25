<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'supplier_id',
        'total_amount',
        'status',
        'needs_approval',
        'notes',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'needs_approval' => 'boolean',
        'approved_at' => 'datetime'
    ];

    // Relaties
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    // Methods
    public function calculateTotal()
    {
        return $this->items->sum('total_price');
    }

    public function checkApprovalRequirement()
    {
        $this->needs_approval = $this->total_amount > 5000;
        $this->save();
    }

    public function approve($approvedBy)
    {
        $this->status = 'approved';
        $this->approved_by = $approvedBy->id;
        $this->approved_at = now();
        $this->save();
    }

    public function reject($approvedBy)
    {
        $this->status = 'rejected';
        $this->approved_by = $approvedBy->id;
        $this->approved_at = now();
        $this->save();
    }

    public function markAsOrdered()
    {
        $this->status = 'ordered';
        $this->save();
    }

    public function markAsReceived()
    {
        $this->status = 'received';
        $this->save();

        // Voorraad bijwerken
        foreach ($this->items as $item) {
            $item->product->updateStock($item->quantity, 'in', 'Inkoop order ontvangen', $this);
        }
    }
}
