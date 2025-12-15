<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class LeaseContractItem extends Model
    {
        use HasFactory;

        protected $fillable = [
            'lease_contract_id',
            'product_id',
            'description',
            'type',
            'quantity',
            'coffee_bags_per_month',
            'monthly_price'
        ];

        protected $casts = [
            'monthly_price' => 'decimal:2'
        ];

        public function leaseContract()
        {
            return $this->belongsTo(LeaseContract::class);
        }

        public function product()
        {
            return $this->belongsTo(Product::class);
        }
    }
