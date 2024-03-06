<?php declare(strict_types=1);

namespace SlimFacades\Facades;

use Psr\Http\Message\ResponseInterface;
use SlimFacades\Support\Facade;

/**
 * @method static \Slim\Interfaces\RouteResolverInterface getRouteResolver() 
 * @method static \Slim\Interfaces\MiddlewareDispatcherInterface getMiddlewareDispatcher()
 * @method static \Slim\App\App add($middleware)
 * @method static \Slim\App\App addMiddleware(\Psr\Http\Server\MiddlewareInterface $middleware)
 * @method static \Slim\Middleware\RoutingMiddleware addRoutingMiddleware()
 * @method static \Slim\Middleware\ErrorMiddleware addErrorMiddleware(bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails, LoggerInterface|null $logger)
 * @method static \Slim\Middleware\BodyParsingMiddleware addBodyParsingMiddleware(callable[]|array $bodyParsers)
 * @method static void run()
 * @method static \Psr\Http\Message\ResponseInterface handle(\Psr\Http\Message\ServerRequestInterface $request)
 * @method static \Psr\Container\ContainerInterface|null getContainer()
 * @method static \Psr\Http\Message\ResponseFactoryInterface getResponseFactory()
 * @method static \Slim\Interfaces\CallableResolverInterface getCallableResolver()
 * @method static \Slim\Interfaces\RouteCollectorInterface getRouteCollector()
 * @method static string getBasePath()
 * @method static \Slim\Interfaces\RouteCollectorProxyInterface setBasePath(string $basePath)
 * @method static \Slim\Interfaces\RouteInterface get(string $pattern, $callable)
 * @method static \Slim\Interfaces\RouteInterface post(string $pattern, $callable)
 * @method static \Slim\Interfaces\RouteInterface put(string $pattern, $callable)
 * @method static \Slim\Interfaces\RouteInterface patch(string $pattern, $callable)
 * @method static \Slim\Interfaces\RouteInterface delete(string $pattern, $callable)
 * @method static \Slim\Interfaces\RouteInterface options(string $pattern, $callable)
 * @method static \Slim\Interfaces\RouteInterface any(string $pattern, $callable)
 * @method static \Slim\Interfaces\RouteInterface map(array $methods, string $pattern, $callable)
 * @method static \Slim\Interfaces\RouteGroupInterface group(string $pattern, $callable)
 * @method static \Slim\Interfaces\RouteInterface redirect(string $from, $to, int $status = 302)
 */
class App extends Facade
{
    /**
     * @inheritDoc
     */
    public static function getFacadeRoot()
    {
        return static::$app;
    }

    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor(): string
    {
        return 'app';
    }
}
