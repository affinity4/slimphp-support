<?php declare(strict_types=1);

namespace SlimFacades\Facades;

use SlimFacades\Support\Facade;

class Container extends Facade
{
    public static function getFacadeRoot()
    {
        return static::$app->getContainer();
    }

    protected static function getFacadeAccessor(): string
    {
        return 'container';
    }
}
