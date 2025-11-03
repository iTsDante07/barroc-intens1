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
        'status',
        'scheduled_date',
        'completed_date',
        'notes'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
