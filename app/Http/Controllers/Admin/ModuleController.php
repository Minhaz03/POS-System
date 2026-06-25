<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ModuleManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModuleController extends Controller
{
    /**
     * Show the Module Control Panel.
     */
    public function index(): View
    {
        $modules = config('modules', []);

        $infrastructureStatus = [];
        foreach (array_keys($modules['infrastructure'] ?? []) as $module) {
            $infrastructureStatus[$module] = ModuleManager::isEnabled($module);
        }

        $activeBusinessType = \App\Models\Setting::get('active_business_type', 'bakery');

        return view('admin.modules.index', compact('modules', 'infrastructureStatus', 'activeBusinessType'));
    }

    /**
     * Toggle an infrastructure module on or off.
     */
    public function toggleInfrastructure(Request $request, string $module): RedirectResponse
    {
        $request->validate([
            'enabled' => 'required|boolean',
        ]);

        try {
            ModuleManager::toggleInfrastructure($module, (bool) $request->input('enabled'));
            $state = $request->input('enabled') ? 'enabled' : 'disabled';

            activity()
                ->withProperties(['module' => $module, 'state' => $state, 'ip' => $request->ip()])
                ->log("Infrastructure module '{$module}' {$state}");

            return back()->with('success', ucfirst($module) . ' module has been ' . $state . '.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to toggle module: ' . $e->getMessage());
        }
    }

    /**
     * Set the active business type module.
     */
    public function setBusinessType(Request $request): RedirectResponse
    {
        $modules = array_keys(config('modules.business_type', []));
        $request->validate([
            'business_type' => 'required|in:' . implode(',', $modules),
        ]);

        try {
            $type = $request->input('business_type');
            ModuleManager::setBusinessType($type);

            activity()
                ->withProperties(['business_type' => $type, 'ip' => $request->ip()])
                ->log("Business type module changed to '{$type}'");

            return back()->with('success', 'Business type module set to ' . ucfirst($type) . '.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to change business type: ' . $e->getMessage());
        }
    }
}
