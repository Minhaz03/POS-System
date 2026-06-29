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
        $dbBatches = \App\Models\ProductionBatch::with('recipe')->orderBy('scheduled_at', 'desc')->get();
        
        $batches = $dbBatches->map(function ($batch) {
            return [
                'real_id' => $batch->id,
                'id'      => $batch->batch_code,
                'recipe'  => $batch->recipe ? $batch->recipe->name : 'Unknown Recipe',
                'qty'     => (float) $batch->qty,
                'status'  => $batch->status,
                'date'    => $batch->scheduled_at ? $batch->scheduled_at->format('Y-m-d h:i A') : '—',
            ];
        })->toArray();

        $recipes = \App\Models\Recipe::where('is_active', true)->orderBy('name')->get();
        return view('dashboard.production', compact('batches', 'recipes'));
    }

    /**
     * Store a new Production Batch.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipe_id'   => 'required|exists:recipes,id',
            'qty'         => 'required|numeric|min:0.01',
            'scheduled_at' => 'required|date',
        ]);

        \App\Models\ProductionBatch::create([
            'batch_code'   => 'PB-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
            'recipe_id'    => $validated['recipe_id'],
            'qty'          => $validated['qty'],
            'status'       => 'Scheduled',
            'scheduled_at' => $validated['scheduled_at'],
            'created_by'   => auth()->id(),
        ]);

        return redirect()->route('dashboard.production')->with('success', 'Production batch scheduled successfully.');
    }

    /**
     * Mark Production Batch as Completed.
     */
    public function complete(\App\Models\ProductionBatch $batch)
    {
        if ($batch->status === 'Completed' || $batch->status === 'Cancelled') {
            return redirect()->back()->with('error', 'Cannot complete this batch.');
        }

        $recipe = $batch->recipe()->with('ingredients.product', 'product')->first();

        if (!$recipe) {
            return redirect()->back()->with('error', 'Associated recipe not found.');
        }

        $multiplier = $batch->qty / $recipe->yield_qty;

        \Illuminate\Support\Facades\DB::transaction(function () use ($batch, $recipe, $multiplier) {
            // Deduct raw ingredients
            foreach ($recipe->ingredients as $ingredient) {
                if ($ingredient->product) {
                    $deductQty = $ingredient->quantity * $multiplier;
                    $ingredient->product->decrement('stock_qty', $deductQty);

                    \App\Models\StockLedger::create([
                        'product_id' => $ingredient->product_id,
                        'type'       => 'Production Usage (-)',
                        'qty'        => -$deductQty,
                        'user_id'    => auth()->id(),
                        'notes'      => "Used in batch: {$batch->batch_code}",
                    ]);
                }
            }

            // Add finished product
            if ($recipe->product) {
                $recipe->product->increment('stock_qty', $batch->qty);

                \App\Models\StockLedger::create([
                    'product_id' => $recipe->product_id,
                    'type'       => 'Production (+)',
                    'qty'        => $batch->qty,
                    'user_id'    => auth()->id(),
                    'notes'      => "Produced from batch: {$batch->batch_code}",
                ]);
            }

            $batch->update([
                'status'       => 'Completed',
                'completed_at' => now(),
            ]);
        });

        return redirect()->route('dashboard.production')->with('success', 'Batch completed and stock updated.');
    }

    /**
     * Cancel Production Batch.
     */
    public function cancel(\App\Models\ProductionBatch $batch)
    {
        if ($batch->status === 'Completed') {
            return redirect()->back()->with('error', 'Cannot cancel a completed batch.');
        }

        $batch->update(['status' => 'Cancelled']);

        return redirect()->route('dashboard.production')->with('success', 'Production batch cancelled.');
    }
}
