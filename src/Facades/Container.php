<?php declare(strict_types=1);

namespace App\Facades;

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
