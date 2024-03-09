<?php declare(strict_types=1);

namespace Affinity4\SlimSupport\Facades;

use Affinity4\SlimSupport\Support\Facade;
use Affinity4\SlimSupport\Support\Pipeline as PipelineBase;

/**
 * @method \Affinity4\SlimSupport\Support\Pipeline send(mixed $passable)
 * @method \Affinity4\SlimSupport\Support\Pipeline through(array|mixed $pipes)
 * @method \Affinity4\SlimSupport\Support\Pipeline pipe(array|mixed $pipes)
 * @method \Affinity4\SlimSupport\Support\Pipeline via(string $method)
 * @method \Affinity4\SlimSupport\Support\Pipeline then(\Closure $destination)
 * @method mixed thenReturn()
 * @method \Affinity4\SlimSupport\Support\PipelineBase getContainer()
 * @method \Affinity4\SlimSupport\Support\PipelineBase setContainer(ContainerInterface $container)
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