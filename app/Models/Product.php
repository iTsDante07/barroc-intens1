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
        'category',
        'image'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Categorie constanten
    const CATEGORIES = [
        'koffiemachines' => 'Koffiemachines',
        'koffiebonen' => 'Koffiebonen',
        'accessoires' => 'Accessoires',
        'onderdelen' => 'Onderdelen',
        'overig' => 'Overig'
    ];

    // Scope voor actieve producten
    public function scopeActive($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Scope voor filtering op categorie
    public function scopeByCategory($query, $category)
    {
        if ($category && $category !== 'alle') {
            return $query->where('category', $category);
        }
        return $query;
    }

    // Scope voor zoeken
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
        }
        return $query;
    }

    // Scope voor voorraad status
    public function scopeByStockStatus($query, $status)
    {
        switch ($status) {
            case 'op_voorraad':
                return $query->where('stock', '>', 10);
            case 'lage_voorraad':
                return $query->whereBetween('stock', [1, 10]);
            case 'uit_voorraad':
                return $query->where('stock', 0);
            default:
                return $query;
        }
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

    // Accessor voor stock status kleur
    public function getStockStatusColorAttribute()
    {
        if ($this->stock <= 0) {
            return 'red';
        } elseif ($this->stock <= $this->min_stock) {
            return 'orange';
        } else {
            return 'green';
        }
    }
}
