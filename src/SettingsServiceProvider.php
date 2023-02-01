<?php

namespace Samireltabal\JsonSettings;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/settings.php', 'SESettings');
        $this->publishes([
            __DIR__.'/../Config/settings.php' => config_path('SESettings.php'),
        ], 'config');
        $configFileName = config('SESettings.default_app');
        if (config('SESettings.enabled')) {
            $this->app->singleton('Settings', function () use ($configFileName) {
                return new SettingsClass($configFileName);
            });
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/settings.php', 'SESettings');
    }
}
