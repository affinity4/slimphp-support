<?php declare(strict_types=1);

namespace SlimFacades\Facades;

use SlimFacades\Support\Facade;

/**
 * @method static \Psr\Http\Message\ResponseFactoryInterface createResponse(int $code = 200, string $reasonPhrase = '')
 */
class ResponseFactory extends Facade
{
    /**
     * @inheritDoc
     */
    public static function getFacadeRoot()
    {
        return static::$app->getResponseFactory();
    }

    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor(): string
    {
        return 'response-factory';
    }
}
