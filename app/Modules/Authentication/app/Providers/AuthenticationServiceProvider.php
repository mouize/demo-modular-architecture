<?php

namespace Modules\Authentication\Providers;

use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;

class AuthenticationServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Authentication';

    protected string $nameLower = 'authentication';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../routes/api.php' => base_path('routes/modules/Authentication/api.php'),
        ], 'authentication-routes');
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations/modules/Authentication'),
        ], 'authentication-migrations');
    }
}
