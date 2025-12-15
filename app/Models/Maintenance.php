<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'assigned_to',
        'title',
        'description',
        'type',
        'priority',
        'status',
        'scheduled_date',
        'completed_date',
        'notes',
        'technician_notes',
        'costs'
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'completed_date' => 'date',
        'costs' => 'decimal:2',
    ];
    protected $attributes = [
        'status' => 'gepland', // Default status
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function isOverdue()
    {
        return $this->status === 'gepland' && $this->scheduled_date->isPast();
    }

    public function getStatusColor()
    {
        return match ($this->status) {
            'voltooid' => 'green',
            'in_uitvoering' => 'blue',
            'geannuleerd' => 'red',
            default => $this->isOverdue() ? 'red' : 'yellow'
        };
    }

    public function getPriorityColor()
    {
        return match ($this->priority) {
            'urgent' => 'red',
            'hoog' => 'orange',
            'normaal' => 'yellow',
            default => 'gray'
        };
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['gepland', 'in_uitvoering'])
            ->where('scheduled_date', '>=', now());
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'gepland')
            ->where('scheduled_date', '<', now());
    }
}
