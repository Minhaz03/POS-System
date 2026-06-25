<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockLedger;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardPageController extends Controller
{
    /**
     * Display a list of products.
     */
    public function products(): View
    {
        $products = [
            ['id' => 1, 'name' => 'Sourdough Bread', 'sku' => 'BAK-SDR-01', 'category' => 'Bread', 'price' => 180, 'stock' => 45, 'status' => 'In Stock'],
            ['id' => 2, 'name' => 'Butter Croissant', 'sku' => 'BAK-CRO-02', 'category' => 'Pastry', 'price' => 120, 'stock' => 60, 'status' => 'In Stock'],
            ['id' => 3, 'name' => 'Chocolate Muffin', 'sku' => 'BAK-MUF-03', 'category' => 'Cake', 'price' => 90, 'stock' => 5, 'status' => 'Low Stock'],
            ['id' => 4, 'name' => 'Baguette', 'sku' => 'BAK-BAG-04', 'category' => 'Bread', 'price' => 110, 'stock' => 30, 'status' => 'In Stock'],
            ['id' => 5, 'name' => 'Red Velvet Cake (Slice)', 'sku' => 'BAK-RVC-05', 'category' => 'Cake', 'price' => 250, 'stock' => 12, 'status' => 'In Stock'],
            ['id' => 6, 'name' => 'Apple Turnover', 'sku' => 'BAK-APT-06', 'category' => 'Pastry', 'price' => 150, 'stock' => 0, 'status' => 'Out of Stock'],
        ];

        return view('dashboard.products', compact('products'));
    }

    /**
     * Display categories.
     */
    public function categories(): View
    {
        $categories = [
            ['id' => 1, 'name' => 'Bread', 'desc' => 'Freshly baked artisanal breads, loaves, and baguettes.', 'count' => 12, 'status' => 'Active'],
            ['id' => 2, 'name' => 'Pastry', 'desc' => 'Flaky, buttery pastries including croissants, danishes, and turnovers.', 'count' => 18, 'status' => 'Active'],
            ['id' => 3, 'name' => 'Cake', 'desc' => 'Whole cakes, slices, cupcakes, and custom celebratory orders.', 'count' => 24, 'status' => 'Active'],
            ['id' => 4, 'name' => 'Beverage', 'desc' => 'Hot coffee, teas, juices, and bottled drinks.', 'count' => 8, 'status' => 'Active'],
        ];

        return view('dashboard.categories', compact('categories'));
    }

    /**
     * Display Stock Ledger.
     */
    public function stockLedger(): View
    {
        $ledger = StockLedger::with(['product', 'user'])
            ->latest()
            ->get();

        $products = Product::orderBy('name')->get();

        return view('dashboard.stock-ledger', compact('ledger', 'products'));
    }

    /**
     * Store a new stock adjustment.
     */
    public function adjustStock(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|string|in:Adjustment (+),Adjustment (-),Wastage (-),Production (+),Stock Audit (Adj)',
            'qty' => 'required|numeric|min:0.001',
            'notes' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $qty = $validated['qty'];
        $type = $validated['type'];

        // Determine signed quantity based on type and direction
        if ($type === 'Adjustment (+)' || $type === 'Production (+)') {
            $signedQty = $qty;
        } elseif ($type === 'Adjustment (-)' || $type === 'Wastage (-)') {
            $signedQty = -$qty;
        } else { // 'Stock Audit (Adj)'
            $direction = $request->input('direction', 'add');
            $signedQty = ($direction === 'add') ? $qty : -$qty;
        }

        // Update product stock_qty
        $newStock = $product->stock_qty + $signedQty;
        $product->update(['stock_qty' => $newStock]);

        // Log to stock ledger
        StockLedger::create([
            'product_id' => $product->id,
            'type' => $type,
            'qty' => $signedQty,
            'user_id' => auth()->id(),
            'notes' => $validated['notes'],
        ]);

        return redirect()->back()->with('success', 'Stock adjusted successfully! Product: ' . $product->name . ', New Stock: ' . number_format($newStock, 2));
    }

    /**
     * Export Stock Ledger to Excel (CSV).
     */
    public function exportExcel()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="stock_ledger_' . date('Y-m-d_H-i-s') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Headers
            fputcsv($file, ['Ref ID', 'Product SKU', 'Product Name', 'Movement Type', 'Quantity', 'Updated By', 'Date & Time', 'Notes']);

            // Fetch ledger entries
            $entries = StockLedger::with(['product', 'user'])->latest()->get();

            foreach ($entries as $row) {
                fputcsv($file, [
                    'TXN-' . $row->id,
                    $row->product?->sku ?? 'N/A',
                    $row->product?->name ?? 'N/A',
                    $row->type,
                    ($row->qty > 0 ? '+' : '') . number_format($row->qty, 2),
                    $row->user?->name ?? 'System/Unknown',
                    $row->created_at->format('Y-m-d h:i A'),
                    $row->notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display Suppliers.
     */
    public function suppliers(): View
    {
        $suppliers = [
            ['id' => 1, 'name' => 'Premium Flour Mills', 'contact' => 'Abul Kashem', 'phone' => '+880 1711-223344', 'email' => 'flour@premium.com', 'address' => 'Narayanganj, Dhaka', 'balance' => 45000],
            ['id' => 2, 'name' => 'Dairy Fresh Distributors', 'contact' => 'Rahima Begum', 'phone' => '+880 1819-556677', 'email' => 'orders@dairyfresh.com', 'address' => 'Savara, Dhaka', 'balance' => 12000],
            ['id' => 3, 'name' => 'Sweet Sugar Co.', 'contact' => 'Tanvir Rahman', 'phone' => '+880 1912-889900', 'email' => 'sales@sweetsugar.com', 'address' => 'Chittagong', 'balance' => 0],
        ];

        return view('dashboard.suppliers', compact('suppliers'));
    }

    /**
     * Display Purchases.
     */
    public function purchases(): View
    {
        $purchases = [
            ['id' => 1001, 'supplier' => 'Premium Flour Mills', 'total' => 24500, 'status' => 'Received', 'payment' => 'Paid', 'date' => '2026-06-22'],
            ['id' => 1002, 'supplier' => 'Dairy Fresh Distributors', 'total' => 8500, 'status' => 'Ordered', 'payment' => 'Pending', 'date' => '2026-06-24'],
            ['id' => 1003, 'supplier' => 'Sweet Sugar Co.', 'total' => 15000, 'status' => 'Received', 'payment' => 'Paid', 'date' => '2026-06-20'],
        ];

        return view('dashboard.purchases', compact('purchases'));
    }

    /**
     * Display POS Terminal.
     */
    public function posTerminal(): View
    {
        $posItems = [
            ['id' => 1, 'name' => 'Sourdough Bread', 'price' => 180, 'category' => 'Bread', 'image' => 'bi-box-seam', 'stock' => 45],
            ['id' => 2, 'name' => 'Butter Croissant', 'price' => 120, 'category' => 'Pastry', 'image' => 'bi-egg', 'stock' => 60],
            ['id' => 3, 'name' => 'Chocolate Muffin', 'price' => 90, 'category' => 'Cake', 'image' => 'bi-cookie', 'stock' => 5],
            ['id' => 4, 'name' => 'Baguette', 'price' => 110, 'category' => 'Bread', 'image' => 'bi-box-seam', 'stock' => 30],
            ['id' => 5, 'name' => 'Red Velvet Cake', 'price' => 250, 'category' => 'Cake', 'image' => 'bi-cake2', 'stock' => 12],
            ['id' => 6, 'name' => 'Cold Brew Coffee', 'price' => 140, 'category' => 'Beverage', 'image' => 'bi-cup-hot', 'stock' => 20],
        ];

        return view('dashboard.pos-terminal', compact('posItems'));
    }

    /**
     * Display Sales.
     */
    public function sales(): View
    {
        $sales = [
            ['id' => 'INV-08492', 'customer' => 'Walk-in Customer', 'total' => 390, 'discount' => 10, 'tax' => 15, 'payment_method' => 'Cash', 'date' => '2026-06-24 11:20 AM'],
            ['id' => 'INV-08491', 'customer' => 'Mithun Chowdhury', 'total' => 740, 'discount' => 50, 'tax' => 35, 'payment_method' => 'Card', 'date' => '2026-06-24 10:45 AM'],
            ['id' => 'INV-08490', 'customer' => 'Anika Rahman', 'total' => 1200, 'discount' => 0, 'tax' => 60, 'payment_method' => 'Mobile Pay', 'date' => '2026-06-24 09:15 AM'],
            ['id' => 'INV-08489', 'customer' => 'Walk-in Customer', 'total' => 180, 'discount' => 0, 'tax' => 9, 'payment_method' => 'Cash', 'date' => '2026-06-23 04:30 PM'],
        ];

        return view('dashboard.sales', compact('sales'));
    }

    /**
     * Display Customers.
     */
    public function customers(): View
    {
        $customers = [
            ['id' => 1, 'name' => 'Mithun Chowdhury', 'phone' => '+880 1712-345678', 'email' => 'mithun@example.com', 'points' => 350, 'total_spent' => 12500],
            ['id' => 2, 'name' => 'Anika Rahman', 'phone' => '+880 1815-998877', 'email' => 'anika@example.com', 'points' => 720, 'total_spent' => 24500],
            ['id' => 3, 'name' => 'Kazi Farhan', 'phone' => '+880 1911-002233', 'email' => 'kazi.farhan@example.com', 'points' => 80, 'total_spent' => 4200],
        ];

        return view('dashboard.customers', compact('customers'));
    }

    /**
     * Display Recipes.
     */
    public function recipes(): View
    {
        $recipes = [
            ['id' => 1, 'name' => 'Sourdough Bread', 'ingredients' => 'Unbleached Bread Flour (400g), Water (280g), Levain/Starter (80g), Fine Sea Salt (8g)', 'prep_time' => '24 hours', 'cost' => 45],
            ['id' => 2, 'name' => 'Butter Croissant', 'ingredients' => 'Pastry Flour (250g), Butter for laminating (125g), Sugar (30g), Yeast (5g), Salt (5g), Milk (150ml)', 'prep_time' => '4 hours', 'cost' => 38],
            ['id' => 3, 'name' => 'Chocolate Muffin', 'ingredients' => 'Flour (200g), Cocoa Powder (50g), Chocolate Chips (100g), Sugar (150g), Eggs (2), Butter (100g), Baking Powder', 'prep_time' => '45 mins', 'cost' => 28],
        ];

        return view('dashboard.recipes', compact('recipes'));
    }

    /**
     * Display Production.
     */
    public function production(): View
    {
        $batches = [
            ['id' => 'PRD-1029', 'recipe' => 'Sourdough Bread', 'qty' => 40, 'status' => 'Completed', 'date' => '2026-06-24 06:00 AM'],
            ['id' => 'PRD-1030', 'recipe' => 'Butter Croissant', 'qty' => 60, 'status' => 'Completed', 'date' => '2026-06-24 07:30 AM'],
            ['id' => 'PRD-1031', 'recipe' => 'Chocolate Muffin', 'qty' => 24, 'status' => 'In Progress', 'date' => '2026-06-24 11:30 AM'],
            ['id' => 'PRD-1032', 'recipe' => 'Sourdough Bread (Batch B)', 'qty' => 30, 'status' => 'Scheduled', 'date' => '2026-06-24 02:00 PM'],
        ];

        return view('dashboard.production', compact('batches'));
    }

    /**
     * Display Custom Orders.
     */
    public function customOrders(): View
    {
        $orders = [
            ['id' => 'ORD-501', 'customer' => 'Rubaiya Islam', 'details' => '2-tier Chocolate Fudge Wedding Cake with white frosting and roses', 'delivery_date' => '2026-06-28', 'price' => 5500, 'advance' => 2000, 'status' => 'Confirmed'],
            ['id' => 'ORD-502', 'customer' => 'Tahmid Hasan', 'details' => 'Custom Spider-Man Birthday Cake (Vanilla, 2kg)', 'delivery_date' => '2026-06-25', 'price' => 2500, 'advance' => 1000, 'status' => 'In Progress'],
            ['id' => 'ORD-503', 'customer' => 'Nusrat Jahan', 'details' => 'Red Velvet anniversary cake (Heart-shaped, 1.5kg)', 'delivery_date' => '2026-06-30', 'price' => 2000, 'advance' => 0, 'status' => 'Pending Review'],
        ];

        return view('dashboard.custom-orders', compact('orders'));
    }

    /**
     * Display Analytics.
     */
    public function analytics(): View
    {
        $analytics = [
            'total_sales' => 148500,
            'orders_count' => 384,
            'avg_order_value' => 386.7,
            'top_selling' => [
                ['name' => 'Butter Croissant', 'qty' => 450, 'revenue' => 54000],
                ['name' => 'Sourdough Bread', 'qty' => 280, 'revenue' => 50400],
                ['name' => 'Chocolate Muffin', 'qty' => 190, 'revenue' => 17100],
            ],
            'sales_by_day' => [
                'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                'data' => [15000, 18500, 22000, 21000, 26000, 31000, 15000],
            ]
        ];

        return view('dashboard.analytics', compact('analytics'));
    }
}
