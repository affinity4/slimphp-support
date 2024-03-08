<?php declare(strict_types=1);

namespace SlimFacades\Facades;

use SlimFacades\Support\Facade;
use SlimFacades\Support\Pipeline as PipelineBase;

/**
 * @method \SlimFacades\Support\Pipeline send(mixed $passable)
 * @method \SlimFacades\Support\Pipeline through(array|mixed $pipes)
 * @method \SlimFacades\Support\Pipeline pipe(array|mixed $pipes)
 * @method \SlimFacades\Support\Pipeline via(string $method)
 * @method \SlimFacades\Support\Pipeline then(\Closure $destination)
 * @method mixed thenReturn()
 * @method \SlimFacades\Support\PipelineBase getContainer()
 * @method \SlimFacades\Support\PipelineBase setContainer(ContainerInterface $container)
 */
class Pipeline extends Facade
{
    /**
     * @inheritDoc
     */
    public static function getFacadeRoot()
    {
        return new PipelineBase(static::$app->getContainer());
    }

    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor(): string
    {
        return 'pipeline';
    }
}