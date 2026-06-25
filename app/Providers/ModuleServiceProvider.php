<?php

namespace App\Providers;

use App\Services\ModuleManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Safety wrap: avoid errors if database is not set up yet
        try {
            if ($this->shouldBootModules()) {
                $this->bootModules();
            }
        } catch (\Exception $e) {
            // Silence exceptions during migration or project setup
        }
    }

    /**
     * Determine if we should boot modules.
     */
    protected function shouldBootModules(): bool
    {
        if (app()->runningInConsole()) {
            $argv = request()->server('argv', []);
            $commandStr = implode(' ', $argv);
            if (Str::contains($commandStr, ['migrate:install', 'key:generate', 'config:cache', 'route:cache', 'package:discover'])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Load enabled modules dynamically.
     */
    protected function bootModules(): void
    {
        $config = config('modules', []);

        // Load Infrastructure Modules
        if (isset($config['infrastructure'])) {
            foreach (array_keys($config['infrastructure']) as $module) {
                if (ModuleManager::isEnabled($module)) {
                    $this->loadModule($module);
                }
            }
        }

        // Load Business Type Modules
        if (isset($config['business_type'])) {
            foreach (array_keys($config['business_type']) as $module) {
                if (ModuleManager::isEnabled($module)) {
                    $this->loadModule($module);
                }
            }
        }
    }

    /**
     * Register a single module's routes, migrations, and views.
     */
    protected function loadModule(string $module): void
    {
        $studly = Str::studly($module);
        
        // 1. Load Migrations
        $migrationPath = base_path("app/Modules/{$studly}/Database/Migrations");
        if (is_dir($migrationPath)) {
            $this->loadMigrationsFrom($migrationPath);
        }

        // 2. Load Views
        $viewsPath = base_path("app/Modules/{$studly}/resources/views");
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, $module);
        }

        // 3. Load Routes
        $routesPath = base_path("app/Modules/{$studly}/routes/web.php");
        if (file_exists($routesPath)) {
            Route::middleware('web')
                ->group($routesPath);
        }
    }
}
