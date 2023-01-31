<?php

namespace Samireltabal\JsonSettings;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Samireltabal\JsonSettings\Exceptions\CountException;

class SettingsClass
{
    protected string $configName;

    protected string $file;

    public function __construct($given = null)
    {
        $this->configName = $given ?? config('app.name');
        $filePath = storage_path('settings/'.$this->configName.'.json');
        $exists = File::exists($filePath);
        $initialConfig = collect([
            'settings' => ['ok' => true],
        ])->toJson(JSON_PRETTY_PRINT);
        if ($exists) {
            $this->file = File::get($filePath);
        } else {
            if (! File::isDirectory(storage_path('settings'))) {
                // @codeCoverageIgnoreStart
                File::makeDirectory(storage_path('settings'));
                // @codeCoverageIgnoreEnd
            }
            File::put($filePath, $initialConfig);
            $this->file = File::get($filePath);
        }
    }

    public function get($key = null)
    {
        if (is_null($key)) {
            return Cache::get('SESettings-'.$this->configName, function () {
                $collection = collect(json_decode($this->file));

                return $collection->toArray();
            });
        }

        return Cache::get($this->configName.'-'.$key, function () use ($key) {
            $key = explode('.', $key);
            $collection = collect(json_decode($this->file));
            foreach ($key as $level) {
                if ($collection->has($level)) {
                    $collection = collect($collection[$level]);
                } else {
                    return null;
                }
            }

            return $collection->toArray()[0] ?? $collection->toArray();
        });
    }

    protected function reread()
    {
        $filePath = storage_path('settings/'.$this->configName.'.json');
        $this->file = File::get($filePath);
    }

    public function set($key, $value)
    {
        $filePath = storage_path('settings/'.$this->configName.'.json');
        $file = json_decode($this->file);
        $file = collect($file);
        $file = self::keyNotExists($file, $key, $value);
        $file = $file->toJson(JSON_PRETTY_PRINT);
        File::put($filePath, $file);
        self::reread();
        $file = json_decode($this->file);
        $file = collect($file);
        $file = data_set($file, $key, $value);
        $file = $file->toJson(JSON_PRETTY_PRINT);

        return File::put($filePath, $file);
    }

    protected function keyNotExists(&$file, $key, $value)
    {
        if (! data_get($file, $key)) {
            $keys = explode('.', $key);
            if (count($keys) > 2) {
                throw new CountException('Maximum of two levels allowed', 0, null, count($keys), $key);
            }
            $mainObject = data_get($file, $keys[0]);
            $mainObject = collect($mainObject)->toArray();
            if (count($keys) > 1) {
                $mainObject[$keys[1]] = $value;
            } else {
                $mainObject[] = $value;
            }
            data_set($file, $keys[0], $mainObject);
            $file = collect($file);
        } else {
            return $file;
        }

        return $file;
    }
}
