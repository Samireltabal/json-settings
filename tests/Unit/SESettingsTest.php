<?php

namespace Samireltabal\JsonSettings\Tests\Unit;

use Illuminate\Support\Facades\File;
use Samireltabal\JsonSettings\Exceptions\CountException;
use Samireltabal\JsonSettings\Facades\Settings;
use Samireltabal\JsonSettings\Tests\TestCase;

class SESettingsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_that_package_is_registered()
    {
        $settings = Settings::get();
        $this->assertNotNull($settings);
        $fileName = config('SESettings.default_app');
        $this->assertFileExists(storage_path("settings/$fileName.json"));
        $file = File::get(storage_path("settings/$fileName.json"));
        $this->assertJson($file, '"ok": true');
    }

    public function test_delete_file_then_check()
    {
        $fileName = config('SESettings.default_app');
        $this->assertFileExists(storage_path("settings/$fileName.json"));
        File::delete(storage_path("settings/$fileName.json"));
        $this->assertFileDoesNotExist(storage_path("settings/$fileName.json"));
        $settings = Settings::get();
        $this->assertNotNull($settings);
        $this->assertFileExists(storage_path("settings/$fileName.json"));
        $file = File::get(storage_path("settings/$fileName.json"));
        $this->assertJson($file, '"ok": true');
    }

    public function test_getting_single_key()
    {
        $fileName = config('SESettings.default_app');
        $this->assertFileExists(storage_path("settings/$fileName.json"));
        $settings = Settings::get();
        $this->assertNotNull($settings);
        $this->assertFileExists(storage_path("settings/$fileName.json"));
        $file = File::get(storage_path("settings/$fileName.json"));
        $this->assertJson($file, '"ok": true');
        $this->assertEquals(Settings::get('settings.ok'), true);
    }

    public function test_getting_not_existing_single_key()
    {
        $fileName = config('SESettings.default_app');
        $this->assertFileExists(storage_path("settings/$fileName.json"));
        $settings = Settings::get();
        $this->assertNotNull($settings);
        $this->assertFileExists(storage_path("settings/$fileName.json"));
        $file = File::get(storage_path("settings/$fileName.json"));
        $this->assertJson($file, '"ok": true');
        $this->assertEquals(Settings::get('settings.not-ok'), null);
    }

    public function test_setting_single_key()
    {
        $fileName = config('SESettings.default_app');
        $this->assertFileExists(storage_path("settings/$fileName.json"));
        $settings = Settings::get();
        $this->assertNotNull($settings);
        $this->assertFileExists(storage_path("settings/$fileName.json"));
        $file = File::get(storage_path("settings/$fileName.json"));
        $this->assertJson($file, '"ok": true');
        $this->assertEquals(Settings::get('settings.ok'), true);
        $this->assertNotNull(Settings::set('settings.also', false));
        $this->assertNotNull(Settings::get('settings.also'));
    }

    public function test_setting_single_key_with_max_allowed()
    {
        $fileName = config('SESettings.default_app');
        $this->assertFileExists(storage_path("settings/$fileName.json"));
        $settings = Settings::get();
        $this->assertNotNull($settings);
        $this->assertFileExists(storage_path("settings/$fileName.json"));
        $file = File::get(storage_path("settings/$fileName.json"));
        $this->assertJson($file, '"ok": true');
        $this->assertEquals(Settings::get('settings.ok'), true);
        $this->expectException(CountException::class);
        $this->withoutExceptionHandling()->assertNotNull(Settings::set('settings.also.another', false));
        $this->assertNotNull(Settings::get('settings.also'));
    }

    public function test_setting_single_key_with_with_in_allowed()
    {
        $fileName = config('SESettings.default_app');
        $this->assertFileExists(storage_path("settings/$fileName.json"));
        $settings = Settings::get();
        $this->assertNotNull($settings);
        $this->assertFileExists(storage_path("settings/$fileName.json"));
        $file = File::get(storage_path("settings/$fileName.json"));
        $this->assertJson($file, '"ok": true');
        $this->assertEquals(Settings::get('settings.ok'), true);
        $this->withoutExceptionHandling()->assertNotNull(Settings::set('newset.sub', false));
        $this->withoutExceptionHandling()->assertNotNull(Settings::set('newset.sub', true));
        $this->assertNotNull(Settings::get('newset.sub'));
    }

    public function test_setting_first_level_single_key_with()
    {
        $fileName = config('SESettings.default_app');
        $this->assertFileExists(storage_path("settings/$fileName.json"));
        $settings = Settings::get();
        $this->assertNotNull($settings);
        $this->assertFileExists(storage_path("settings/$fileName.json"));
        $file = File::get(storage_path("settings/$fileName.json"));
        $this->assertJson($file, '"ok": true');
        $this->assertEquals(Settings::get('settings.ok'), true);
        $this->withoutExceptionHandling()->assertNotNull(Settings::set('newset', false));
        $this->withoutExceptionHandling()->assertNotNull(Settings::set('newset', true));
        $this->assertNotNull(Settings::get('newset'));
    }
}
