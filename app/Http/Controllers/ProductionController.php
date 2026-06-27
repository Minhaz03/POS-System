<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductionController extends Controller
{
    /**
     * Display Production.
     */
    public function production(): View
    {
        $dbBatches = \App\Models\ProductionBatch::with('recipe')->orderBy('production_date', 'desc')->get();
        
        $batches = $dbBatches->map(function ($batch) {
            return [
                'id' => $batch->batch_number,
                'recipe' => $batch->recipe ? $batch->recipe->name : 'Unknown Recipe',
                'qty' => (float) $batch->qty,
                'status' => $batch->status,
                'date' => $batch->production_date->format('Y-m-d h:i A'),
            ];
        })->toArray();

        return view('dashboard.production', compact('batches'));
    }
}
