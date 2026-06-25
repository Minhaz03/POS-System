<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    /**
     * Display a list of all recipes.
     */
    public function index(Request $request): View
    {
        $query = Recipe::with(['product', 'ingredients']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $recipes = $query->latest()->paginate(12);

        $categories = Recipe::distinct()->pluck('category')->filter()->sort()->values();

        return view('dashboard.recipes.index', compact('recipes', 'categories'));
    }

    /**
     * Show the form for creating a new recipe.
     */
    public function create(): View
    {
        $products = Product::with('unit')->where('is_active', true)->orderBy('name')->get();

        return view('dashboard.recipes.create', compact('products'));
    }

    /**
     * Store a newly created recipe.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'product_id'   => 'nullable|exists:products,id',
            'category'     => 'nullable|string|max:100',
            'description'  => 'nullable|string',
            'prep_time'    => 'nullable|string|max:100',
            'bake_time'    => 'nullable|string|max:100',
            'yield_qty'    => 'required|integer|min:1',
            'yield_unit'   => 'nullable|string|max:50',
            'instructions' => 'nullable|string',
            'notes'        => 'nullable|string',
            'is_active'    => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Validate ingredients
        $ingredientNames = $request->input('ingredients.ingredient_name', []);
        if (empty(array_filter($ingredientNames))) {
            return back()->withInput()->withErrors(['ingredients' => 'Please add at least one ingredient.']);
        }

        DB::transaction(function () use ($request, $validated) {
            $recipe = Recipe::create($validated);

            $this->syncIngredients($recipe, $request);

            // Recalculate estimated cost
            $totalCost = $recipe->ingredients()->sum('subtotal');
            $recipe->update(['estimated_cost' => $totalCost]);
        });

        return redirect()->route('dashboard.recipes')->with('success', 'Recipe "' . $validated['name'] . '" created successfully!');
    }

    /**
     * Display a specific recipe.
     */
    public function show(Recipe $recipe): View
    {
        $recipe->load(['product', 'ingredients.product']);
        return view('dashboard.recipes.show', compact('recipe'));
    }

    /**
     * Show the form to edit a recipe.
     */
    public function edit(Recipe $recipe): View
    {
        $recipe->load(['product', 'ingredients.product']);
        $products = Product::with('unit')->where('is_active', true)->orderBy('name')->get();

        return view('dashboard.recipes.edit', compact('recipe', 'products'));
    }

    /**
     * Update an existing recipe.
     */
    public function update(Request $request, Recipe $recipe): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'product_id'   => 'nullable|exists:products,id',
            'category'     => 'nullable|string|max:100',
            'description'  => 'nullable|string',
            'prep_time'    => 'nullable|string|max:100',
            'bake_time'    => 'nullable|string|max:100',
            'yield_qty'    => 'required|integer|min:1',
            'yield_unit'   => 'nullable|string|max:50',
            'instructions' => 'nullable|string',
            'notes'        => 'nullable|string',
            'is_active'    => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Validate ingredients
        $ingredientNames = $request->input('ingredients.ingredient_name', []);
        if (empty(array_filter($ingredientNames))) {
            return back()->withInput()->withErrors(['ingredients' => 'Please add at least one ingredient.']);
        }

        DB::transaction(function () use ($request, $validated, $recipe) {
            $recipe->update($validated);

            // Remove old ingredients and re-sync
            $recipe->ingredients()->delete();
            $this->syncIngredients($recipe, $request);

            // Recalculate estimated cost
            $totalCost = $recipe->fresh()->ingredients()->sum('subtotal');
            $recipe->update(['estimated_cost' => $totalCost]);
        });

        return redirect()->route('dashboard.recipes')->with('success', 'Recipe "' . $validated['name'] . '" updated successfully!');
    }

    /**
     * Delete a recipe.
     */
    public function destroy(Recipe $recipe): RedirectResponse
    {
        $name = $recipe->name;
        $recipe->delete();

        return redirect()->route('dashboard.recipes')->with('success', 'Recipe "' . $name . '" deleted successfully!');
    }

    /**
     * Sync ingredients from the request into the recipe.
     */
    private function syncIngredients(Recipe $recipe, Request $request): void
    {
        $ingredientNames = $request->input('ingredients.ingredient_name', []);
        $productIds      = $request->input('ingredients.product_id', []);
        $quantities      = $request->input('ingredients.quantity', []);
        $units           = $request->input('ingredients.unit', []);
        $unitCosts       = $request->input('ingredients.unit_cost', []);
        $notes           = $request->input('ingredients.notes', []);

        foreach ($ingredientNames as $index => $name) {
            if (empty(trim($name))) {
                continue;
            }

            $qty      = (float) ($quantities[$index] ?? 0);
            $cost     = (float) ($unitCosts[$index] ?? 0);
            $subtotal = $qty * $cost;

            RecipeIngredient::create([
                'recipe_id'       => $recipe->id,
                'product_id'      => !empty($productIds[$index]) ? $productIds[$index] : null,
                'ingredient_name' => trim($name),
                'quantity'        => $qty,
                'unit'            => $units[$index] ?? null,
                'unit_cost'       => $cost,
                'subtotal'        => $subtotal,
                'notes'           => $notes[$index] ?? null,
                'sort_order'      => $index,
            ]);
        }
    }
}
