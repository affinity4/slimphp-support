# SlimPHP Facades

Add Laravel style facades, traits and helper functions to any SlimPHP app

## Installation

```bash
composer require affinity4/slimphp-facades
```

## Usage

### Setting up Facades in your Application

To use SlimPHP Facades, you first need to create your Slim app as normal, with either `Slim\App\AppFactory` or `DI\Container\Slim\Bridge`. Then you'll need to call `SlimFacades\Support\Facade::setFacadeApplication($app)`:

```php
use Slim\Factory\AppFactory;
use SlimFacades\Support\Facade;

$app = AppFactory::createFromContainer();
Facade::setFacadeApplication($app);
```

You will now have access to all SlimFacades, as well as the helper function (e.g. `response()`)

### App Facade

Facade for `Slim\App`:

```php
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Factory\AppFactory;
use SlimFacades\Support\Facade;

$app = AppFactory::createFromContainer();
Facade::setFacadeApplication($app);

App::get('/', function(RequestInterface $request, ResponseInterface $response) {
    // return ...
});

App::run();
```

### Container

```php
use SlimFacades\Facades\Container;

Container::set('some-service', function () {
    return SomeService();
});

if (Container::has('some-service')) {
    $someService = Container::get('some-service');
}
```

### Response

#### JSON Response

```php
use SlimFacades\Facades\Container;

App::get('/', function($request) {
    return Response::json(['test' => 'payload'])->get();
});
```

## Helper functions

### response()

#### Standard application/text Response

```php
App::get('/', function ($request) {
    return response('Hello World')->get();
});
```

#### Standard JSON Response

```php
App::get('/', function ($request) {
    return response()->json(['data' => 'payload'])->get();
});
```

### tap()

```php
return tap(new Psr7Response(), function ($response) {
    $response->getBody()->write('foo');
});
```

## Traits

### Tappable

```php
use SlimFacades\Support\Traits\Tappable;

class TappableClass
{
    use Tappable;

    private $name;

    public static function make()
    {
        return new static;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}

$name = TappableClass::make()->tap(function ($tappable) {
    $tappable->setName('MyName');
})->getName();

// Or, even though setName does not return this you can now just chain from it!
$name = TappableClass::make()->tap()->setName('MyName')->getName()
```

### Macroable

Macros allow you to add methods to classes dynamically (without having to modify their code).

Let's say you are tired of having to do this:

```php
$app->get('/', function ($request, $response) {
    $response = new Response; 
    $response->getBody()->write('Hello');

    return $response;
})
```

Instead you just want to call a write method directly from the `$response` instance. First, we need to extend the Response class so we can use the `Macroable` trait, but still have all of our base Response methods.

```php
use GuzzleHttp\Psr7\Response;
use SlimFacades\Support\Traits\Macroable;

class MacroableResponse extends Response
{
    use Macroable;
}
```

Then we need to add `MacroableResponse` to our container, so we are always dealing with the same instance (not all instances will have the "macroed" methods).

```php
use SlimFacades\Facades\Container;
// ... above code here

Container::set('response', function () {
    return new MacroableResponse();
});
```

Then we can get our `MacroableResponse` instance from the container however you want, and just call `write`!

```php
App::get('/', function () {
   return Container::get('response')->write('Macro!');
});
```

### Conditionable

Allows to conditionally chain functionality.

For example, let's imagine we have a standard PSR-11 Container, which has a the bare minimum PSR-11 compliant methods, `set`, `get` and `has`. The `set` method adds a service to the container, `get` returns the service and `has` checks an service is in the container.

We have a `Logger` we want to add to the container, but it requires a `FileDriver` to be in the container already, or else we need to also add the `FileDriver` class to the container first.

We might then have some bootstrapping logic like so:

```php
$container = new Container;

if (!$container->has('FileDriver')) {
    $container->set('FileDriver', fn() => new FileDriver);
}

if (!$container->has('Logger')) {
    $container->set('Logger', function ($container) {
        $logger = new Logger;
        $logger->setDriver($container->get('FileDriver'));
        return $logger;
    });
}
```

However, if we extends our `Container` class and add the `Conditionable` trait, we can instead use the `unless` method to do this check with a fluent interface:

__NOTE: To check the opposite, there is also `when`.__

```php
class ConditionableContainer extends Container
{
    use Conditionable;
}

$container = new ConditionableContainer;
$container
    ->unless(
        fn($container) => $container->has('FileDriver'),
        function ($container) {
            $container->set('FileDriver', fn() => new FileDriver);
        }
    )->unless(
        fn($container) => $container->has('Logger'), 
        function ($container) {
            $container->set('Logger', function ($container) {
                $logger = new Logger;
                $logger->setDriver($container->get('FileDriver'));
                return $logger;
            });
        }
    );
```

You're probably thinking this is still quite bit verbose, so to clean this up you could create `invokable` ServiceFactory classes for all of your `$container->set` logic.__

```php
class FileDriverServiceFactory
{
    public function __invoke($container)
    {
        $container->set('FileDriver', fn() => new FileDriver);
    }
}

class LoggerServiceFactory
{
    public function __invoke($cotnainer)
    {
        $logger = new Logger;
        $logger->setDriver($container->get('FileDriver'));
        return $logger;
    }
}

$container = new ConditionableContainer;

// or, using unless, instead of when
$container
    ->unless(fn($container) => $container->has('FileDriver'), FileDriverServiceFactory($container))
    ->unless(fn($container) => $container->has('Logger'), LoggerServiceFactory($container));
```

## Pipeline Support class

Pipelines allow for a middleware-like interface to chain processing of tasks.

A pipeline processes each task, passed the returned value to the next process in the chain.

They are useful for multi-step data processing, http middleware, database querying and validation tasks.

Here's an example of how to use it to validation, filter, transform and save an incoming get request.

```php
// 1. Prepare the request
class PrepareRequest
{
    public function handle($request, $next)
    {
        $uri = $request->getUri();
        $query = $uri->getQuery(); // Get the query string (e.g., "param1=value1&param2=value2")
        parse_str($query, $queryParams); // Parse the query string into an array

        return $next($queryParams);
    }
}

// 2. Validate the request
class ValidateRequest
{
    public function handle($data, $next)
    {   
        // Validate parameters 
        // (e.g. check if 'email' and 'password' exist, validate 'email' and 'password' etc)

        // If invalid then $data['valid'] = false, else $data['valid'] = true;

        return $next($data);
    }
}

// 2. Transform the request
class TransformRequest
{
    public function handle($data, $next)
    {
        $data['password'] = bcrypt($data['password']);

        return $next($data);
    }
}

// 3. Save the data, or log errors
class SaveRequest
{
    public function handle($data, $next)
    {
        if (!$data['valid']) {
            // Log errors...

            return $next($data);
        }

        $data['saved'] = true;

        return $next($data);
    }
}

App::get('/', function ($request) {
    // 4. Define the pipeline
    $result = (new Pipeline(App::getContainer()))
        ->send($request)
        ->through([
            PrepareRequest::class,
            ValidateRequest::class,
            TransformRequest::class,
            SaveRequest::class,
        ])
        ->thenReturn();

    // 5. Respond with the processed data
    return response()->json(['result' => $result])->get();
});
```

This way our controller stays clean, and readable, and each responsibility is separated to it's own class to make maintainance easier in the long run. This would also make testing easier, as you could test the individual classes, and also the overall pipeline result, without needing to test the controller itself.
