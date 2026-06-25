<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ModuleManager
{
    /**
     * Check if a module is enabled.
     */
    public static function isEnabled(string $module): bool
    {
        $config = config('modules');

        // Core modules are always enabled
        if (isset($config['core'][$module])) {
            return true;
        }

        // Infrastructure modules
        if (isset($config['infrastructure'][$module])) {
            return (bool) Setting::get("module_{$module}_enabled", false);
        }

        // Business Type modules (mutually exclusive)
        if (isset($config['business_type'][$module])) {
            $activeType = Setting::get('active_business_type', 'bakery');
            return $activeType === $module;
        }

        return false;
    }

    /**
     * Enable or disable an infrastructure module.
     */
    public static function toggleInfrastructure(string $module, bool $enable): void
    {
        $config = config('modules');
        if (!isset($config['infrastructure'][$module])) {
            throw new \InvalidArgumentException("{$module} is not a valid infrastructure module.");
        }

        Setting::set("module_{$module}_enabled", $enable, 'boolean', 'modules');

        if ($enable) {
            self::runMigrationsAndSeeders($module);
        }
    }

    /**
     * Set the active business type module (mutual exclusion enforced).
     */
    public static function setBusinessType(string $module): void
    {
        $config = config('modules');
        if (!isset($config['business_type'][$module])) {
            throw new \InvalidArgumentException("{$module} is not a valid business type module.");
        }

        Setting::set('active_business_type', $module, 'string', 'modules');

        self::runMigrationsAndSeeders($module);
    }

    /**
     * Run module migrations and seeders if not run yet.
     */
    protected static function runMigrationsAndSeeders(string $module): void
    {
        $studly = Str::studly($module);
        $settingKey = "module_{$module}_migrated";

        if (Setting::get($settingKey, false)) {
            return; // Already run
        }

        // Migration path inside app/Modules/{StudlyModuleName}/Database/Migrations
        $migrationPath = "app/Modules/{$studly}/Database/Migrations";
        $fullPath = base_path($migrationPath);

        if (is_dir($fullPath)) {
            try {
                Log::info("Running migrations for module: {$module}");
                Artisan::call('migrate', [
                    '--path' => $migrationPath,
                    '--force' => true
                ]);

                // Run seeder if class exists
                $seederClass = "App\\Modules\\{$studly}\\Database\\Seeders\\{$studly}DatabaseSeeder";
                if (class_exists($seederClass)) {
                    Log::info("Running seeder for module: {$module}");
                    Artisan::call('db:seed', [
                        '--class' => $seederClass,
                        '--force' => true
                    ]);
                }

                Setting::set($settingKey, true, 'boolean', 'modules');
            } catch (\Exception $e) {
                Log::error("Failed to migrate/seed module: {$module}. Error: " . $e->getMessage());
                throw $e;
            }
        } else {
            Log::warning("Migration directory not found for module: {$module} at {$fullPath}");
        }
    }
}
