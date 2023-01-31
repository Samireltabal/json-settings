<?php

namespace Samireltabal\JsonSettings\Tests;

use Illuminate\Support\Facades\File;
use Samireltabal\JsonSettings\SettingsServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $fileName = config('settings.default_app');
        File::delete(storage_path("settings/$fileName.json"));
        app()->Settings->get();
    }

    protected function getPackageProviders($app)
    {
        return [
            SettingsServiceProvider::class,
        ];
    }
}
