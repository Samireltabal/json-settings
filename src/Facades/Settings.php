<?php

namespace Samireltabal\JsonSettings\Facades;

use Illuminate\Support\Facades\Facade;

class Settings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Settings';
    }
}
