<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\ProductionBatch;
use App\Models\StockLedger;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ProductionBatchController extends Controller
{
    /**
     * Display a listing of the production batches.
     */
    public function index(Request $request): View
    {
        $query = ProductionBatch::with(['recipe.product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('batch_code', 'like', '%' . $search . '%')
                  ->orWhereHas('recipe', function ($rq) use ($search) {
                      $rq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $batches = $query->orderBy('scheduled_at', 'desc')->paginate(15);

        return view('dashboard.production_batches.index', compact('batches'));
    }

    /**
     * Show the form for creating a new production batch.
     */
    public function create(): View
    {
        $recipes = Recipe::where('is_active', true)->orderBy('name')->get();
        return view('dashboard.production_batches.create', compact('recipes'));
    }

    /**
     * Store a newly created production batch in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
            'qty' => 'required|numeric|min:0.001',
            'scheduled_at' => 'required|date',
            'status' => 'required|in:Scheduled,In Progress,Completed',
        ]);

        $validated['created_by'] = auth()->id();

        DB::transaction(function () use ($validated) {
            $batch = ProductionBatch::create($validated);

            if ($batch->status === 'Completed') {
                $batch->update(['completed_at' => now()]);
                $this->adjustStockForCompletedBatch($batch);
            }
        });

        return redirect()->route('dashboard.production')->with('success', 'Production batch created successfully!');
    }

    /**
     * Mark the batch as Completed and update stock.
     */
    public function complete(ProductionBatch $production_batch): RedirectResponse
    {
        if ($production_batch->status === 'Completed') {
            return redirect()->back()->with('info', 'Batch is already completed.');
        }

        DB::transaction(function () use ($production_batch) {
            $production_batch->update([
                'status' => 'Completed',
                'completed_at' => now(),
            ]);

            $this->adjustStockForCompletedBatch($production_batch);
        });

        return redirect()->back()->with('success', 'Production batch ' . $production_batch->batch_code . ' marked as completed and stock updated!');
    }

    /**
     * Cancel the production batch.
     */
    public function cancel(ProductionBatch $production_batch): RedirectResponse
    {
        if ($production_batch->status === 'Completed') {
            return redirect()->back()->with('error', 'Completed production batches cannot be cancelled.');
        }

        $production_batch->update(['status' => 'Cancelled']);

        return redirect()->back()->with('success', 'Production batch ' . $production_batch->batch_code . ' has been cancelled.');
    }

    /**
     * Remove the specified production batch from storage (Soft Delete).
     */
    public function destroy(ProductionBatch $production_batch): RedirectResponse
    {
        if ($production_batch->status === 'Completed') {
            return redirect()->back()->with('error', 'Completed production batches cannot be deleted.');
        }

        $production_batch->delete();

        return redirect()->route('dashboard.production')->with('success', 'Production batch deleted successfully!');
    }

    /**
     * Helper: Increment product stock and log to StockLedger
     */
    private function adjustStockForCompletedBatch(ProductionBatch $batch): void
    {
        $recipe = $batch->recipe;
        if ($recipe && $recipe->product_id) {
            $product = $recipe->product;
            if ($product) {
                $product->increment('stock_qty', $batch->qty);

                StockLedger::create([
                    'product_id' => $product->id,
                    'type' => 'Production (+)',
                    'qty' => $batch->qty,
                    'user_id' => auth()->id(),
                    'notes' => "Completed production batch {$batch->batch_code}.",
                ]);
            }
        }
    }
}
