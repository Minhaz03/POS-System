<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionBatch extends Model
{
    protected $fillable = [
        'batch_number',
        'recipe_id',
        'qty',
        'status',
        'production_date',
    ];

    protected $casts = [
        'production_date' => 'datetime',
        'qty' => 'decimal:2',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }}
