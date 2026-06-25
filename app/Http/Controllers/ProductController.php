<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'brand', 'unit', 'tax']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%')
                  ->orWhere('barcode', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'low_stock') {
                $query->whereColumn('stock_qty', '<=', 'alert_qty');
            } elseif ($request->status === 'out_of_stock') {
                $query->where('stock_qty', '<=', 0);
            } elseif ($request->status === 'in_stock') {
                $query->where('stock_qty', '>', 0);
            }
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::all();

        return view('dashboard.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $taxes = Tax::orderBy('name')->get();

        return view('dashboard.products.create', compact('categories', 'brands', 'units', 'taxes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'tax_id' => 'nullable|exists:taxes,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'cost_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'mrp_price' => 'required|numeric|min:0',
            'stock_qty' => 'required|numeric|min:0',
            'alert_qty' => 'required|numeric|min:0',
            'reorder_qty' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'is_pos_enabled' => 'nullable|boolean',
            'is_bakery_item' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_pos_enabled'] = $request->has('is_pos_enabled');
        $validated['is_bakery_item'] = $request->has('is_bakery_item');

        if (empty($validated['sku'])) {
            $nextId = (Product::max('id') ?? 0) + 1;
            $validated['sku'] = 'SKU-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        }

        if (empty($validated['barcode'])) {
            $validated['barcode'] = '880' . str_pad(mt_rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        Product::create($validated);

        return redirect()->route('dashboard.products')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $taxes = Tax::orderBy('name')->get();

        return view('dashboard.products.edit', compact('product', 'categories', 'brands', 'units', 'taxes'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'tax_id' => 'nullable|exists:taxes,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'cost_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'mrp_price' => 'required|numeric|min:0',
            'stock_qty' => 'required|numeric|min:0',
            'alert_qty' => 'required|numeric|min:0',
            'reorder_qty' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'is_pos_enabled' => 'nullable|boolean',
            'is_bakery_item' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_pos_enabled'] = $request->has('is_pos_enabled');
        $validated['is_bakery_item'] = $request->has('is_bakery_item');

        if (empty($validated['sku'])) {
            $validated['sku'] = $product->sku ?? 'SKU-' . str_pad($product->id, 6, '0', STR_PAD_LEFT);
        }

        if (empty($validated['barcode'])) {
            $validated['barcode'] = $product->barcode ?? '880' . str_pad(mt_rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);
        }

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('dashboard.products')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('dashboard.products')->with('success', 'Product deleted successfully!');
    }

    public function show(Product $product): View
    {
        $product->load(['category', 'brand', 'unit', 'tax']);
        return view('dashboard.products.show', compact('product'));
    }

    public function toggleStock(Product $product): RedirectResponse
    {
        if ($product->stock_qty > 0) {
            $product->update(['stock_qty' => 0]);
            $message = 'Product marked as Out of Stock.';
        } else {
            $newQty = max(10, $product->alert_qty + 5);
            $product->update(['stock_qty' => $newQty]);
            $message = 'Product marked as In Stock.';
        }

        return redirect()->back()->with('success', $message);
    }
}
