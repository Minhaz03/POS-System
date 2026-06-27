<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PosController extends Controller
{
    /**
     * Display POS Terminal.
     */
    public function posTerminal(): View
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('is_pos_enabled', true)
            ->get();

        $posItems = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->sale_price,
                'category' => $product->category ? $product->category->name : 'Uncategorized',
                'image' => $product->image ?: 'bi-box-seam',
                'stock' => (float) $product->stock_qty,
            ];
        })->toArray();

        $categories = collect($posItems)->pluck('category')->unique()->values()->toArray();

        return view('dashboard.pos-terminal', compact('posItems', 'categories'));
    }
}
