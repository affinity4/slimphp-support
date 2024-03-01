<?php declare(strict_types=1);

namespace SlimFacades\Support;

abstract class Facade
{
    /**
     * The resolved object instances.
     *
     * @var mixed
     */
    protected static $resolvedInstance;

    /**
     * SlimPHP application instance.
     *
     * @var Slim\App
     */
    protected static $app;

    /**
     * Set the SlimPHP application instance.
     *
     * This will allow us to access the SlimPHP application instance
     * and therefore the container instance.
     *
     * @param \Slim\App|null $app
     *
     * @return void
     */
    public static function setFacadeApplication(?\Slim\App $app )
    {
        static::$app = $app;
    }

    /**
     * Get the root object behind the facade.
     *
     * @return \Slim\App
     */
    public static function getFacadeApplication(): \Slim\App
    {
        return static::$app;
    }

    /**
     * Get the get the resolved instance or set and then get the resolved instance.
     *
     * @param string $name
     *
     * @return mixed
     */
    public static function resolveFacadeInstance(string $name): mixed
    {
        if (isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }

        return static::$resolvedInstance[$name] = static::$app->getContainer()->get($name);
    }

    /**
     * Clear all of the resolved instances.
     *
     * @param string $name
     *
     * @return void
     */
    public static function clearResolvedInstance(string $name)
    {
        unset(static::$resolvedInstance[$name]);
    }

    /**
     * Clear all of the resolved instances.
     *
     * @return void
     */
    public static function clearResolvedInstances()
    {
        static::$resolvedInstance = [];
    }

    /**
     * Get the root object behind the facade.
     *
     * @return mixed
     */
    public static function getFacadeRoot()
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor(): string
    {
        throw new \RuntimeException('Facade does not implement getFacadeAccessor method.');
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $args): mixed
    {
        $instance = static::getFacadeRoot();

        if (! $instance) {
            throw new \RuntimeException('A facade root has not been set.');
        }

        return $instance->$method(...$args);
    }
}
