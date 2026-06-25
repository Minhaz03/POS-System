<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Show the general settings form.
     */
    public function index(): View
    {
        $settings = Setting::where('group', 'general')->get()->keyBy('key');
        $user = auth()->user();
        return view('admin.settings.index', compact('settings', 'user'));
    }

    /**
     * Save general settings.
     */
    public function update(Request $request): RedirectResponse
    {
        if (!$request->user()->can('settings.manage')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'business_name'    => 'nullable|string|max:100',
            'business_address' => 'nullable|string|max:255',
            'business_phone'   => 'nullable|string|max:30',
            'business_email'   => 'nullable|email|max:100',
            'currency_symbol'  => 'nullable|string|max:5',
            'currency_code'    => 'nullable|string|max:5',
        ]);

        $fields = [
            'business_name', 'business_address', 'business_phone',
            'business_email', 'currency_symbol', 'currency_code',
        ];

        foreach ($fields as $field) {
            Setting::set($field, $request->input($field, ''), 'string', 'general');
        }

        activity()
            ->withProperties(['ip' => $request->ip()])
            ->log('General settings updated');

        return back()->with('success', 'Settings saved successfully.');
    }
}
