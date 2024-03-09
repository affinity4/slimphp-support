<?php declare(strict_types=1);

namespace Affinity4\SlimSupport\Facades;

use Affinity4\SlimSupport\Support\Facade;

/**
 * @method static mixed get(string $id)
 * @method static bool has(string $id)
 * @method static void set(string $name, mixed $value)
 */
class Container extends Facade
{
    /**
     * @inheritDoc
     */
    public static function getFacadeRoot()
    {
        return static::$app->getContainer();
    }

    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor(): string
    {
        return 'container';
    }
}
