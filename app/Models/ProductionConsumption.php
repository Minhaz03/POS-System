<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionConsumption extends Model
{
    protected $fillable = [
        'production_batch_id',
        'product_id',
        'qty',
        'unit_cost',
        'total_cost',
    ];

    public function batch()
    {
        return $this->belongsTo(ProductionBatch::class, 'production_batch_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
