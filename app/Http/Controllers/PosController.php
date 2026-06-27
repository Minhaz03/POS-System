<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockLedger;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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
        $customers = Customer::where('is_active', true)->orderBy('name')->get();

        return view('dashboard.pos-terminal', compact('posItems', 'categories', 'customers'));
    }

    /**
     * Process checkout from POS Terminal.
     */
    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'sale_date'   => 'required|date',
            'discount'    => 'required|numeric|min:0',
            'tax'         => 'required|numeric|min:0',
            'subtotal'    => 'required|numeric|min:0',
            'total'       => 'required|numeric|min:0',
            'cart'        => 'required|array|min:1',
            'cart.*.id'   => 'required|exists:products,id',
            'cart.*.qty'  => 'required|numeric|min:0.1',
            'cart.*.price'=> 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Create Sale record
            $sale = Sale::create([
                'customer_id'     => $validated['customer_id'],
                'sale_date'       => $validated['sale_date'],
                'subtotal'        => $validated['subtotal'],
                'discount_amount' => $validated['discount'],
                'tax_amount'      => $validated['tax'],
                'grand_total'     => $validated['total'],
                'amount_tendered' => $validated['total'], // Assume fully paid for POS cash checkout
                'change_amount'   => 0,
                'payment_method'  => 'cash', // Default to cash for POS
                'status'          => 'completed',
                'created_by'      => auth()->id(),
            ]);

            // Create Sale Items and deduct stock
            foreach ($validated['cart'] as $item) {
                $product = Product::find($item['id']);
                
                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['qty'],
                    'unit_price' => $item['price'],
                    'subtotal'   => $item['qty'] * $item['price'],
                ]);

                // Deduct stock
                $product->stock_qty -= $item['qty'];
                $product->save();

                // Log stock ledger
                StockLedger::create([
                    'product_id' => $product->id,
                    'type'       => 'out',
                    'qty'        => $item['qty'],
                    'user_id'    => auth()->id(),
                    'notes'      => 'POS Sale - ' . $sale->invoice_no,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'invoice_no' => $sale->invoice_no,
                'total' => $sale->grand_total,
                'message' => 'Sale processed successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
