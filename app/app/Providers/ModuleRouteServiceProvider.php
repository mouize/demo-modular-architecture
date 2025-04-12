<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Facades\Module;

class ModuleRouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        foreach (Module::allEnabled() as $module) {
            $name = $module->getName();
            $path = base_path("routes/modules/{$name}/api.php");

            if (file_exists($path)) {
                Route::prefix('api')
                    ->name('api.')
                    ->group($path);
            }
        }
    }
}
