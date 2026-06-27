<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'batch_code',
    'recipe_id',
    'qty',
    'status',
    'scheduled_at',
    'completed_at',
    'created_by',
])]
class ProductionBatch extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'qty' => 'decimal:3',
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($batch) {
            if (empty($batch->batch_code)) {
                $latest = static::withTrashed()->orderBy('id', 'desc')->first();
                $nextId = $latest ? $latest->id + 1001 : 1001;
                $batch->batch_code = 'PRD-' . $nextId;
            }
        });
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
