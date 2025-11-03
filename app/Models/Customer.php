<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_name',
        'email',
        'phone',
        'address',
        'city',           // Nieuw
        'postal_code',    // Nieuw
        'bkr_checked',    // Nieuw
        'bkr_approved',   // Nieuw
        'bkr_notes'       // Nieuw
    ];

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
