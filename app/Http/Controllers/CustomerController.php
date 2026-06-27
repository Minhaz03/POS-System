<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        $customers = $query->orderBy('name')->paginate(15);
        return view('dashboard.customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('dashboard.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30|unique:customers,phone',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'loyalty_points' => 'nullable|integer|min:0',
            'total_spent' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['loyalty_points'] = $validated['loyalty_points'] ?? 0;
        $validated['total_spent'] = $validated['total_spent'] ?? 0;
        $validated['is_active'] = $request->has('is_active') || $request->wantsJson();

        $customer = Customer::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'customer' => $customer,
                'message' => 'Customer registered successfully!'
            ]);
        }

        return redirect()->route('dashboard.customers')->with('success', 'Customer registered successfully!');
    }

    public function edit(Customer $customer): View
    {
        return view('dashboard.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30|unique:customers,phone,' . $customer->id,
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'loyalty_points' => 'required|integer|min:0',
            'total_spent' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $customer->update($validated);

        return redirect()->route('dashboard.customers')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        // Don't delete Walk-in Customer (phone = '0000000000')
        if ($customer->phone === '0000000000') {
            return redirect()->route('dashboard.customers')->with('error', 'Cannot delete default Walk-in Customer!');
        }

        $customer->delete();
        return redirect()->route('dashboard.customers')->with('success', 'Customer deleted successfully!');
    }
}
