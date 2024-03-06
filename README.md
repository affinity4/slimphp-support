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
```
