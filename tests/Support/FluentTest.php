<?php declare(strict_types=1);

namespace Affinity4\SlimSupport\Tests\Support;

use Affinity4\SlimSupport\Support\Fluent;
use PHPUnit\Framework\TestCase;

class FluentTest extends TestCase
{
    public function testToArray()
    {
        $fluent = new Fluent(['foo' => 'bar']);
        $this->assertSame(['foo' => 'bar'], $fluent->toArray());
    }

    public function testJsonSerialize()
    {
        $fluent = new Fluent(['foo' => 'bar']);
        $this->assertSame(['foo' => 'bar'], $fluent->jsonSerialize());
    }

    public function testToJson()
    {
        $fluent = new Fluent(['foo' => 'bar']);
        $this->assertSame('{"foo":"bar"}', $fluent->toJson());
    }

    public function testGetAttributes()
    {
        $fluent = new Fluent(['foo' => 'bar']);
        $this->assertSame(['foo' => 'bar'], $fluent->getAttributes());
    }

    public function testGet()
    {
        $fluent = new Fluent(['foo' => 'bar']);
        $this->assertSame('bar', $fluent->get('foo'));
    }

    public function testGetWithDefault()
    {
        $fluent = new Fluent(['foo' => 'bar']);
        $this->assertSame('bar', $fluent->get('foo', 'default'));
        $this->assertSame('default', $fluent->get('bar', 'default'));
    }

    public function testGetWithClosure()
    {
        $fluent = new Fluent(['foo' => 'bar']);
        $this->assertSame('bar', $fluent->get('foo', function () {
            return 'default';
        }));
    }

    public function testGetWithClosureAndDefault()
    {
        $fluent = new Fluent(['foo' => 'bar']);
        $this->assertSame('default', $fluent->get('bar', function () {
            return 'default';
        }));
    }

    public function testGetWithCallable()
    {
        $fluent = new Fluent(['foo' => 'bar']);
        $this->assertSame('bar', $fluent->get('foo', function () {
            return 'default';
        }));
    }

    public function testGetWithCallableAndDefault()
    {
        $fluent = new Fluent(['foo' => 'bar']);
        $this->assertSame('default', $fluent->get('bar', function () {
            return 'default';
        }));
    }

    public function testGetViaMagicCall()
    {
        $fluent = new Fluent(['foo' => 'bar']);
        $this->assertSame('bar', $fluent->foo);
    }
}