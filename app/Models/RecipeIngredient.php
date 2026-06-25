<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'recipe_id',
    'product_id',
    'ingredient_name',
    'quantity',
    'unit',
    'unit_cost',
    'subtotal',
    'notes',
    'sort_order',
])]
class RecipeIngredient extends Model
{
    protected function casts(): array
    {
        return [
            'quantity'   => 'decimal:3',
            'unit_cost'  => 'decimal:2',
            'subtotal'   => 'decimal:2',
            'sort_order' => 'integer',
        ];
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
