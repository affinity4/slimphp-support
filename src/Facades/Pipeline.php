<?php declare(strict_types=1);

namespace SlimFacades\Facades;

use SlimFacades\Support\Facade;
use SlimFacades\Support\Pipeline as PipelineBase;

class Pipeline extends Facade
{
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