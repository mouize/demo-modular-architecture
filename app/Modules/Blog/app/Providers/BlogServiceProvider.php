<?php

namespace Modules\Blog\Providers;

use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;

class BlogServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Blog';

    protected string $nameLower = 'blog';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../routes/api.php' => base_path('routes/modules/Blog/api.php'),
        ], 'blog-routes');
        $this->publishes([
            __DIR__.'/../../stubs/migrations' => database_path('migrations'),
        ], 'blog-migrations');
    }
}
