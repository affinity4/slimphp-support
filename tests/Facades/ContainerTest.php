<?php declare(strict_types=1);

namespace SlimFacades\Tests\Facades;

use DI\Bridge\Slim\Bridge;
use PHPUnit\Framework\TestCase;
use SlimFacades\Facades\App;
use SlimFacades\Facades\Container;
use SlimFacades\Support\Facade;

final class ContainerTest extends TestCase
{
    protected $app;

    public function setUp(): void
    {
        $this->app = Bridge::create();
        Facade::setFacadeApplication($this->app);
    }

    public function tearDown(): void
    {
        unset($this->app);
    }
    

    public function testContainer()
    {
        Container::set('test-container-set', function () {
            return 'test-container-set';
        });

        $container = App::getContainer();

        $this->assertTrue($container->has('test-container-set'));
        $this->assertEquals('test-container-set', $container->get('test-container-set'));
    }
}