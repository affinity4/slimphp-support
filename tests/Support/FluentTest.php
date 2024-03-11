<?php declare(strict_types=1);

namespace Affinity4\SlimSupport\Tests\Support;

use Affinity4\SlimSupport\Support\Fluent;
use PHPUnit\Framework\TestCase;

class FluentTest extends TestCase
{
    public function testAttributesAreSetByConstructor()
    {
        $array = ['name' => 'Luke', 'age' => 39];
        $fluent = new Fluent($array);

        $refl = new \ReflectionObject($fluent);
        $attributes = $refl->getProperty('attributes');

        $this->assertEquals($array, $attributes->getValue($fluent));
        $this->assertEquals($array, $fluent->getAttributes());
    }

    public function testAttributesAreSetByConstructorGivenstdClass()
    {
        $array = ['name' => 'Luke', 'age' => 39];
        $fluent = new Fluent((object) $array);

        $refl = new \ReflectionObject($fluent);
        $attributes = $refl->getProperty('attributes');

        $this->assertEquals($array, $attributes->getValue($fluent));
        $this->assertEquals($array, $fluent->getAttributes());
    }

    public function testAttributesAreSetByConstructorGivenArrayIterator()
    {
        $array = ['name' => 'Luke', 'age' => 39];
        $fluent = new Fluent(new FluentArrayIteratorStub($array));

        $refl = new \ReflectionObject($fluent);
        $attributes = $refl->getProperty('attributes');

        $this->assertEquals($array, $attributes->getValue($fluent));
        $this->assertEquals($array, $fluent->getAttributes());
    }

    public function testGetMethodReturnsAttribute()
    {
        $fluent = new Fluent(['name' => 'Luke']);

        $this->assertSame('Luke', $fluent->get('name'));
        $this->assertSame('Default', $fluent->get('foo', 'Default'));
        $this->assertSame('Luke', $fluent->name);
        $this->assertNull($fluent->foo);
    }

    public function testArrayAccessToAttributes()
    {
        $fluent = new Fluent(['attributes' => '1']);

        $this->assertTrue(isset($fluent['attributes']));
        $this->assertEquals(1, $fluent['attributes']);

        $fluent->attributes();

        $this->assertTrue($fluent['attributes']);
    }

    public function testMagicMethodsCanBeUsedToSetAttributes()
    {
        $fluent = new Fluent;

        $fluent->name = 'Luke';
        $fluent->developer();
        $fluent->age(25);

        $this->assertSame('Luke', $fluent->name);
        $this->assertTrue($fluent->developer);
        $this->assertEquals(25, $fluent->age);
        $this->assertInstanceOf(Fluent::class, $fluent->programmer());
    }

    public function testIssetMagicMethod()
    {
        $array = ['name' => 'Luke', 'age' => 39];
        $fluent = new Fluent($array);

        $this->assertTrue(isset($fluent->name));

        unset($fluent->name);

        $this->assertFalse(isset($fluent->name));
    }

    public function testToArrayReturnsAttribute()
    {
        $array = ['name' => 'Luke', 'age' => 39];
        $fluent = new Fluent($array);

        $this->assertEquals($array, $fluent->toArray());
    }

    public function testToJsonEncodesTheToArrayResult()
    {
        $fluent = $this->getMockBuilder(Fluent::class)->onlyMethods(['toArray'])->getMock();
        $fluent->expects($this->once())->method('toArray')->willReturn(['foo']);
        $results = $fluent->toJson();

        $this->assertJsonStringEqualsJsonString(json_encode(['foo']), $results);
    }
}

class FluentArrayIteratorStub implements \IteratorAggregate
{
    protected $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }
}