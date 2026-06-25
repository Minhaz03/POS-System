<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class UnitController extends Controller
{
    public function index(): View
    {
        $units = Unit::orderBy('name')->paginate(15);
        return view('dashboard.units.index', compact('units'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:units,name',
            'short_name' => 'required|string|max:20|unique:units,short_name',
        ]);

        $unit = Unit::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Unit created successfully!',
                'data' => $unit
            ]);
        }

        return redirect()->back()->with('success', 'Unit created successfully!');
    }

    public function update(Request $request, Unit $unit): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:units,name,' . $unit->id,
            'short_name' => 'required|string|max:20|unique:units,short_name,' . $unit->id,
        ]);

        $unit->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Unit updated successfully!',
                'data' => $unit
            ]);
        }

        return redirect()->back()->with('success', 'Unit updated successfully!');
    }

    public function destroy(Unit $unit): RedirectResponse|JsonResponse
    {
        $unit->delete();

        return redirect()->back()->with('success', 'Unit deleted successfully!');
    }
}
