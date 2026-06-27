<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CustomOrderController extends Controller
{
    /**
     * Display a listing of custom orders.
     */
    public function index(Request $request): View
    {
        $query = CustomOrder::with('customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhere('details', 'like', '%' . $search . '%')
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('delivery_date', 'asc')->paginate(15);

        return view('dashboard.custom_orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new custom order.
     */
    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();
        return view('dashboard.custom_orders.create', compact('customers'));
    }

    /**
     * Store a newly created custom order in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'details' => 'required|string',
            'price' => 'required|numeric|min:0',
            'advance' => 'required|numeric|min:0',
            'delivery_date' => 'required|date|after_or_equal:today',
            'status' => 'nullable|string|in:Pending,Confirmed,In Progress,Completed,Cancelled',
        ]);

        $validated['created_by'] = auth()->id();
        if (empty($validated['status'])) {
            $validated['status'] = 'Pending';
        }

        CustomOrder::create($validated);

        return redirect()->route('dashboard.custom-orders')->with('success', 'Custom order created successfully!');
    }

    /**
     * Display the specified custom order.
     */
    public function show(CustomOrder $custom_order): View
    {
        $custom_order->load(['customer', 'creator']);
        return view('dashboard.custom_orders.show', compact('custom_order'));
    }

    /**
     * Show the form for editing the specified custom order.
     */
    public function edit(CustomOrder $custom_order): View
    {
        $customers = Customer::orderBy('name')->get();
        return view('dashboard.custom_orders.edit', compact('custom_order', 'customers'));
    }

    /**
     * Update the specified custom order in storage.
     */
    public function update(Request $request, CustomOrder $custom_order): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'details' => 'required|string',
            'price' => 'required|numeric|min:0',
            'advance' => 'required|numeric|min:0',
            'delivery_date' => 'required|date',
            'status' => 'required|string|in:Pending,Confirmed,In Progress,Completed,Cancelled',
        ]);

        $custom_order->update($validated);

        return redirect()->route('dashboard.custom-orders')->with('success', 'Custom order updated successfully!');
    }

    /**
     * Remove the specified custom order from storage.
     */
    public function destroy(CustomOrder $custom_order): RedirectResponse
    {
        $custom_order->delete();

        return redirect()->route('dashboard.custom-orders')->with('success', 'Custom order deleted successfully!');
    }
}
