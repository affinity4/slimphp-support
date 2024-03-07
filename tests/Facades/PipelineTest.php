<?php declare(strict_types=1);

namespace SlimFacades\Tests\Facades;

use DI\Bridge\Slim\Bridge;
use PHPUnit\Framework\TestCase;
use SlimFacades\Contracts\Pipeline as PipelineContract;
use SlimFacades\Facades\Pipeline;
use SlimFacades\Support\Facade;

final class PipelineTest extends TestCase
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
    

    public function testPipelineMethodsExist()
    {
        $this->assertInstanceOf(PipelineContract::class, Pipeline::getFacadeRoot());
    }
}
