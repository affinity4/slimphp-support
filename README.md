# SlimPHP Facades

Add Laravel style facades and helper functions to any SlimPHP app.

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

### SlimFacades\Traits\Tappable

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

### Support\Traits\Macroable

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
