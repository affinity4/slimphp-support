<?php declare(strict_types=1);

namespace SlimFacades\Facades;

use SlimFacades\Http\Response as HttpResponse;
use SlimFacades\Support\Facade;

/**
 * @method static \Psr\Http\Message\ResponseInterface get()
 * @method static \SlimFacades\Http\Response json(array $data)
 */
class Response extends Facade
{
    /**
     * @inheritDoc
     */
    public static function getFacadeRoot()
    {
        return new HttpResponse();
    }

    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor(): string
    {
        return 'response';
    }
}
