<?php

namespace App\Http\Middleware;

use App\Services\ModuleManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleActive
{
    /**
     * Handle an incoming request.
     * Usage in routes: ->middleware('module:warehouse')
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (!ModuleManager::isEnabled($module)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => "The '{$module}' module is not enabled."], 403);
            }

            return redirect()->route('dashboard')->with('error', "The '{$module}' module is currently disabled. Please contact your administrator.");
        }

        return $next($request);
    }
}
