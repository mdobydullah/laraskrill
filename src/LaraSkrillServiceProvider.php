<?php

namespace Obydul\LaraSkrill;

use Illuminate\Support\ServiceProvider;

class LaraSkrillServiceProvider extends ServiceProvider
{
    public function boot()
    {

        // Publish config files
        $this->publishes([
            __DIR__ . './../config/config.php' => config_path('laraskrill.php'),
        ]);

    }

    public function register()
    {
        $this->mergeConfig();
    }

    /**
     * Merges user's and larapal's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . './../config/config.php',
            'laraskrill'
        );
    }
}
