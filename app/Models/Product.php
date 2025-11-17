<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'min_stock',
        'sku',
        'brand',
        'category',
        'is_visible_to_customers',
        'is_visible_to_maintenance',
        'image'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_visible_to_customers' => 'boolean',
        'is_visible_to_maintenance' => 'boolean'
    ];

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    // Scopes voor filtering
    public function scopeVisibleToCustomers($query)
    {
        return $query->where('is_visible_to_customers', true);
    }

    public function scopeVisibleToMaintenance($query)
    {
        return $query->where('is_visible_to_maintenance', true);
    }

    // Accessor voor stock status
    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return 'Uit voorraad';
        } elseif ($this->stock <= $this->min_stock) {
            return 'Lage voorraad';
        } else {
            return 'Momenteel leverbaar';
        }
    }
}
