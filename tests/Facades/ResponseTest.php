<?php declare(strict_types=1);

namespace Affinity4\SlimSupport\Tests\Facades;

use Affinity4\SlimSupport\Facades\Response;
use DI\Bridge\Slim\Bridge;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Affinity4\SlimSupport\Http\Response as HttpResponse;
use Affinity4\SlimSupport\Support\Facade;

final class ResponseTest extends TestCase
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

    public function testResponseIsInstanceOfSlimSupportHttpResponse()
    {
        $response = Response::getFacadeRoot();

        $this->assertInstanceOf(HttpResponse::class, $response);
    }

    public function testResponseIsPsrCompliant()
    {
        $response = Response::get();

        $this->assertTrue(method_exists($response, 'getBody'));
    }

    /**
     * @depends testResponseIsInstanceOfSlimSupportHttpResponse
     *
     * @return void
     */
    public function testJsonReturnsInstanceOfHttpResponse()
    {
        $response = Response::json(['test' => 'payload']);

        $this->assertInstanceOf(HttpResponse::class, $response);
    }

    /**
     * @depends testJsonReturnsInstanceOfHttpResponse
     */
    public function testGetReturnsInstanceOfAResponseInterface()
    {
        $response = Response::json(['test' => 'payload'])->get();

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * @depends testGetReturnsInstanceOfAResponseInterface
     */
    public function testResponseJsonHasContentTypeJson()
    {
        $response = Response::json(['test' => 'payload'])->get();

        $headers = $response->getHeaders();

        $this->assertArrayHasKey('Content-Type', $headers);
        $this->assertEquals('application/json', $headers['Content-Type'][0]);
    }
}