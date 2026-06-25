<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PurchaseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Purchase::with(['supplier', 'creator'])
            ->withCount('items');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', '%' . $search . '%')
                  ->orWhereHas('supplier', fn ($sq) => $sq->where('name', 'like', '%' . $search . '%'));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $purchases = $query->latest('purchase_date')->paginate(15);

        return view('dashboard.purchases.index', compact('purchases'));
    }

    public function create(): View
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $products  = Product::where('is_active', true)->with('unit')->orderBy('name')->get();

        return view('dashboard.purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id'     => 'nullable|exists:suppliers,id',
            'purchase_date'   => 'required|date',
            'discount_amount' => 'required|numeric|min:0',
            'tax_amount'      => 'required|numeric|min:0',
            'shipping_cost'   => 'required|numeric|min:0',
            'amount_paid'     => 'required|numeric|min:0',
            'payment_method'  => 'nullable|in:cash,bank_transfer,cheque,credit',
            'notes'           => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|numeric|min:0.001',
            'items.*.unit_cost'  => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_cost'];
            }

            $grandTotal = $subtotal
                - $validated['discount_amount']
                + $validated['tax_amount']
                + $validated['shipping_cost'];

            $amountPaid   = $validated['amount_paid'];
            $amountDue    = $grandTotal - $amountPaid;
            $paymentStatus = $amountPaid <= 0 ? 'unpaid' : ($amountDue <= 0 ? 'paid' : 'partial');

            $purchase = Purchase::create([
                'supplier_id'     => $validated['supplier_id'],
                'purchase_date'   => $validated['purchase_date'],
                'subtotal'        => $subtotal,
                'discount_amount' => $validated['discount_amount'],
                'tax_amount'      => $validated['tax_amount'],
                'shipping_cost'   => $validated['shipping_cost'],
                'grand_total'     => $grandTotal,
                'amount_paid'     => $amountPaid,
                'amount_due'      => $amountDue,
                'payment_status'  => $paymentStatus,
                'payment_method'  => $validated['payment_method'],
                'notes'           => $validated['notes'],
                'status'          => 'ordered',
                'created_by'      => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $item['product_id'],
                    'quantity'    => $item['quantity'],
                    'unit_cost'   => $item['unit_cost'],
                    'subtotal'    => $item['quantity'] * $item['unit_cost'],
                ]);
            }

            // Update supplier balance (we now owe them more)
            if ($purchase->supplier_id) {
                $purchase->supplier->increment('current_balance', $grandTotal - $amountPaid);
            }
        });

        return redirect()->route('dashboard.purchases')
            ->with('success', 'Purchase order created successfully!');
    }

    public function show(Purchase $purchase): View
    {
        $purchase->load(['supplier', 'creator', 'items.product.unit']);
        return view('dashboard.purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase): View
    {
        if (in_array($purchase->status, ['received', 'returned'])) {
            return redirect()->route('dashboard.purchases.show', $purchase)
                ->with('error', 'Cannot edit a received or returned purchase order.');
        }

        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $products  = Product::where('is_active', true)->with('unit')->orderBy('name')->get();
        $purchase->load('items.product');

        return view('dashboard.purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    public function update(Request $request, Purchase $purchase): RedirectResponse
    {
        if (in_array($purchase->status, ['received', 'returned'])) {
            return redirect()->route('dashboard.purchases.show', $purchase)
                ->with('error', 'Cannot edit a received or returned purchase order.');
        }

        $validated = $request->validate([
            'supplier_id'     => 'nullable|exists:suppliers,id',
            'purchase_date'   => 'required|date',
            'discount_amount' => 'required|numeric|min:0',
            'tax_amount'      => 'required|numeric|min:0',
            'shipping_cost'   => 'required|numeric|min:0',
            'amount_paid'     => 'required|numeric|min:0',
            'payment_method'  => 'nullable|in:cash,bank_transfer,cheque,credit',
            'notes'           => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|numeric|min:0.001',
            'items.*.unit_cost'  => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $purchase) {
            // Reverse old supplier balance
            if ($purchase->supplier_id) {
                $purchase->supplier->decrement('current_balance', $purchase->amount_due);
            }

            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_cost'];
            }

            $grandTotal   = $subtotal - $validated['discount_amount'] + $validated['tax_amount'] + $validated['shipping_cost'];
            $amountPaid   = $validated['amount_paid'];
            $amountDue    = $grandTotal - $amountPaid;
            $paymentStatus = $amountPaid <= 0 ? 'unpaid' : ($amountDue <= 0 ? 'paid' : 'partial');

            $purchase->update([
                'supplier_id'     => $validated['supplier_id'],
                'purchase_date'   => $validated['purchase_date'],
                'subtotal'        => $subtotal,
                'discount_amount' => $validated['discount_amount'],
                'tax_amount'      => $validated['tax_amount'],
                'shipping_cost'   => $validated['shipping_cost'],
                'grand_total'     => $grandTotal,
                'amount_paid'     => $amountPaid,
                'amount_due'      => $amountDue,
                'payment_status'  => $paymentStatus,
                'payment_method'  => $validated['payment_method'],
                'notes'           => $validated['notes'],
            ]);

            // Replace items
            $purchase->items()->delete();
            foreach ($validated['items'] as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $item['product_id'],
                    'quantity'    => $item['quantity'],
                    'unit_cost'   => $item['unit_cost'],
                    'subtotal'    => $item['quantity'] * $item['unit_cost'],
                ]);
            }

            // Apply new supplier balance
            if ($validated['supplier_id']) {
                $supplier = \App\Models\Supplier::find($validated['supplier_id']);
                $supplier?->increment('current_balance', $amountDue);
            }
        });

        return redirect()->route('dashboard.purchases')
            ->with('success', 'Purchase order updated successfully!');
    }

    /**
     * Mark the purchase as received — updates stock quantities and supplier balance.
     */
    public function receive(Purchase $purchase): RedirectResponse
    {
        if ($purchase->status === 'received') {
            return redirect()->route('dashboard.purchases.show', $purchase)
                ->with('error', 'This purchase has already been received.');
        }

        DB::transaction(function () use ($purchase) {
            $purchase->load('items.product');

            foreach ($purchase->items as $item) {
                // Increment product stock
                $item->product->increment('stock_qty', $item->quantity);

                // Update cost price to the latest purchase cost
                $item->product->update(['cost_price' => $item->unit_cost]);
            }

            $purchase->update(['status' => 'received']);
        });

        return redirect()->route('dashboard.purchases.show', $purchase)
            ->with('success', 'Purchase received! Stock has been updated.');
    }

    public function destroy(Purchase $purchase): RedirectResponse
    {
        if ($purchase->status === 'received') {
            return redirect()->route('dashboard.purchases')
                ->with('error', 'Cannot delete a received purchase order. Please reverse the stock manually.');
        }

        // Reverse supplier balance on delete
        if ($purchase->supplier_id) {
            $purchase->supplier->decrement('current_balance', $purchase->amount_due);
        }

        $purchase->delete();

        return redirect()->route('dashboard.purchases')
            ->with('success', 'Purchase order deleted successfully!');
    }
}
