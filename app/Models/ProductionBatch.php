<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionBatch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'batch_code',
        'recipe_id',
        'qty',
        'status',
        'scheduled_at',
        'completed_at',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at'  => 'datetime',
        'completed_at'  => 'datetime',
        'qty'           => 'decimal:3',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
