<?php declare(strict_types=1);

namespace Affinity4\SlimSupport\Tests\Facades;

use DI\Bridge\Slim\Bridge;
use DI\Container;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Affinity4\SlimSupport\Facades\App;
use Affinity4\SlimSupport\Support\Facade;

final class AppTest extends TestCase
{
    public function setUp(): void
    {
        $app = Bridge::create();
        Facade::setFacadeApplication($app);
    }

    public function testAppGetContainer()
    {
        $container = App::getContainer();

        $this->assertInstanceOf(Container::class, $container);
    }

    public function testAppGetResponseFactory()
    {
        $responseFactory = App::getResponseFactory();

        $this->assertInstanceOf(ResponseFactoryInterface::class, $responseFactory);
    }
}