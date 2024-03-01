<?php declare(strict_types=1);

namespace SlimFacades\Facades;

use SlimFacades\Support\Facade;

class App extends Facade
{
    public static function getFacadeRoot()
    {
        return static::$app;
    }

    protected static function getFacadeAccessor(): string
    {
        return 'app';
    }
}
