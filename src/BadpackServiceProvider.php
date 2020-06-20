<?php

namespace SergeYugai\Laravel\Badpack;

use Illuminate\Support\ServiceProvider;

class BadpackServiceProvider extends ServiceProvider
{
    protected array $commands = [];

    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsWithFallback();
    }

    public function loadViewsWithFallback(): void
    {
        $badPackOverride = resource_path('views/vendor/badpack');

        if (file_exists($badPackOverride)) {
            $this->loadViewsFrom($badPackOverride, 'badpack');
        }
        $this->loadViewsFrom(dirname(__DIR__).'/resources/views', 'badpack');
    }
}
