<?php declare(strict_types=1);

namespace Affinity4\SlimSupport\Tests\Facades;

use DI\Bridge\Slim\Bridge;
use PHPUnit\Framework\TestCase;
use Affinity4\SlimSupport\Support\Facade;
use Slim\Factory\AppFactory;

final class FacadeTest extends TestCase
{
    public function setUp(): void
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);
    }

    public function testSetFacadeApplication()
    {
        $app = AppFactory::create();
        Facade::setFacadeApplication($app);
        $this->assertInstanceOf(\Slim\App::class, Facade::getFacadeApplication());
    }

    public function testFacadeCallsUnderlyingObject()
    {
        $app = Bridge::create();
        $container = $app->getContainer();
        $container->set('foo', function () {
            return new StubClass();
        });
        Facade::setFacadeApplication($app);

        $this->assertInstanceOf(StubClass::class, FacadeStub::resolveFacadeInstance('foo'));
        $this->assertTrue(method_exists(FacadeStub::resolveFacadeInstance('foo'), 'bar'));
        $this->assertEquals('baz', FacadeStub::bar());
    }
}

class FacadeStub extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'foo';
    }
}

class StubClass
{
    public function bar()
    {
        return 'baz';
    }
}
