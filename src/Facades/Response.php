<?php declare(strict_types=1);

namespace Affinity4\SlimSupport\Facades;

use Affinity4\SlimSupport\Http\Response as HttpResponse;
use Affinity4\SlimSupport\Support\Facade;

/**
 * @method static \Psr\Http\Message\ResponseInterface get()
 * @method static \Affinity4\SlimSupport\Http\Response json(array $data)
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
