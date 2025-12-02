<?php

namespace App\Models;

use App\Notifications\LowStockNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class Product extends Model
{
    use HasFactory;

    protected bool $wasLowBeforeSave = false;

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

    protected static function booted()
    {
        static::saving(function (Product $product) {
            $previousThreshold = $product->getOriginal('min_stock') ?? ($product->min_stock ?? 0);
            $originalStock = $product->exists ? $product->getOriginal('stock') : null;
            $product->wasLowBeforeSave = $originalStock !== null && $originalStock <= $previousThreshold;
        });

        static::saved(function (Product $product) {
            if ($product->isLowStock() && !$product->wasLowBeforeSave) {
                $product->notifyLowStock();
            }
        });
    }

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

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock !== null && $this->stock <= ($this->min_stock ?? 0);
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

    protected function notifyLowStock(): void
    {
        $inkoopUsers = User::where('department_id', 4)->get();

        if ($inkoopUsers->isEmpty()) {
            return;
        }

        Notification::send($inkoopUsers, new LowStockNotification($this));
    }
}
