<?php declare(strict_types=1);

namespace Affinity4\SlimSupport\Tests\Facades;

use DI\Bridge\Slim\Bridge;
use PHPUnit\Framework\TestCase;
use Affinity4\SlimSupport\Contracts\Pipeline as PipelineContract;
use Affinity4\SlimSupport\Facades\Pipeline;
use Affinity4\SlimSupport\Support\Facade;

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
    

    public function testIsInstanceOfPipeline()
    {
        $this->assertInstanceOf(PipelineContract::class, Pipeline::getFacadeRoot());
    }
}
