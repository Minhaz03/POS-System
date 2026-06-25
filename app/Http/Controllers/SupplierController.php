<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('contact_person', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        $suppliers = $query->orderBy('name')->paginate(15);
        return view('dashboard.suppliers.index', compact('suppliers'));
    }

    public function create(): View
    {
        return view('dashboard.suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'opening_balance' => 'required|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['current_balance'] = $validated['opening_balance'];

        Supplier::create($validated);

        return redirect()->route('dashboard.suppliers')->with('success', 'Supplier added successfully!');
    }

    public function edit(Supplier $supplier): View
    {
        return view('dashboard.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->id,
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'opening_balance' => 'required|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        // Adjust current balance based on difference in opening balance
        $balanceDiff = $validated['opening_balance'] - $supplier->opening_balance;
        $validated['current_balance'] = $supplier->current_balance + $balanceDiff;

        $supplier->update($validated);

        return redirect()->route('dashboard.suppliers')->with('success', 'Supplier updated successfully!');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();
        return redirect()->route('dashboard.suppliers')->with('success', 'Supplier deleted successfully!');
    }
}
