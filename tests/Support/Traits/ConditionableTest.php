<?php declare(strict_types=1);

namespace SlimFacades\Tests\Support\Traits;

use SlimFacades\Support\HigherOrderWhenProxy;
use PHPUnit\Framework\TestCase;
use SlimFacades\Support\Traits\Conditionable;

class ConditionableTest extends TestCase
{
    public function testWhen(): void
    {
        $this->assertInstanceOf(HigherOrderWhenProxy::class, (new ConditionableLogger)->when(true));
        $this->assertInstanceOf(HigherOrderWhenProxy::class, (new ConditionableLogger)->when(false));
        $this->assertInstanceOf(HigherOrderWhenProxy::class, (new ConditionableLogger)->when());
        $this->assertInstanceOf(ConditionableLogger::class, (new ConditionableLogger)->when(false, null));
        $this->assertInstanceOf(ConditionableLogger::class, (new ConditionableLogger)->when(true, function () {}));
    }

    public function testUnless(): void
    {
        $this->assertInstanceOf(HigherOrderWhenProxy::class, (new ConditionableLogger)->unless(true));
        $this->assertInstanceOf(HigherOrderWhenProxy::class, (new ConditionableLogger)->unless(false));
        $this->assertInstanceOf(HigherOrderWhenProxy::class, (new ConditionableLogger)->unless());
        $this->assertInstanceOf(ConditionableLogger::class, (new ConditionableLogger)->unless(true, null));
        $this->assertInstanceOf(ConditionableLogger::class, (new ConditionableLogger)->unless(false, function () {}));
    }

    public function testWhenConditionCallback()
    {
        // With static condition
        $logger = (new ConditionableLogger())
            ->when(2, function ($logger, $condition) {
                $logger->log('when', $condition);
            }, function ($logger, $condition) {
                $logger->log('default', $condition);
            });

        $this->assertSame(['when', 2], $logger->values);

        // With callback condition
        $logger = (new ConditionableLogger())->log('init')
            ->when(function ($logger) {
                return $logger->has('init');
            }, function ($logger, $condition) {
                $logger->log('when', $condition);
            }, function ($logger, $condition) {
                $logger->log('default', $condition);
            });

        $this->assertSame(['init', 'when', true], $logger->values);
    }

    public function testWhenDefaultCallback()
    {
        // With static condition
        $logger = (new ConditionableLogger())
            ->when(null, function ($logger, $condition) {
                $logger->log('when', $condition);
            }, function ($logger, $condition) {
                $logger->log('default', $condition);
            });

        $this->assertSame(['default', null], $logger->values);

        // With callback condition
        $logger = (new ConditionableLogger())
            ->when(function ($logger) {
                return $logger->has('missing');
            }, function ($logger, $condition) {
                $logger->log('when', $condition);
            }, function ($logger, $condition) {
                $logger->log('default', $condition);
            });

        $this->assertSame(['default', false], $logger->values);
    }

    public function testUnlessConditionCallback()
    {
        // With static condition
        $logger = (new ConditionableLogger())
            ->unless(null, function ($logger, $condition) {
                $logger->log('unless', $condition);
            }, function ($logger, $condition) {
                $logger->log('default', $condition);
            });

        $this->assertSame(['unless', null], $logger->values);

        // With callback condition
        $logger = (new ConditionableLogger())
            ->unless(function ($logger) {
                return $logger->has('missing');
            }, function ($logger, $condition) {
                $logger->log('unless', $condition);
            }, function ($logger, $condition) {
                $logger->log('default', $condition);
            });

        $this->assertSame(['unless', false], $logger->values);
    }

    public function testUnlessDefaultCallback()
    {
        // With static condition
        $logger = (new ConditionableLogger())
            ->unless(2, function ($logger, $condition) {
                $logger->log('unless', $condition);
            }, function ($logger, $condition) {
                $logger->log('default', $condition);
            });

        $this->assertSame(['default', 2], $logger->values);

        // With callback condition
        $logger = (new ConditionableLogger())->log('init')
            ->unless(function ($logger) {
                return $logger->has('init');
            }, function ($logger, $condition) {
                $logger->log('unless', $condition);
            }, function ($logger, $condition) {
                $logger->log('default', $condition);
            });

        $this->assertSame(['init', 'default', true], $logger->values);
    }

    public function testWhenProxy()
    {
        // With static condition
        $logger = (new ConditionableLogger())
            ->when(true)->log('one')
            ->when(false)->log('two');

        $this->assertSame(['one'], $logger->values);

        // With callback condition
        $logger = (new ConditionableLogger())->log('init')
            ->when(function ($logger) {
                return $logger->has('init');
            })
            ->log('one')
            ->when(function ($logger) {
                return $logger->has('missing');
            })
            ->log('two')
            ->when()->has('init')->log('three')
            ->when()->has('missing')->log('four')
            ->when()->toggle->log('five')
            ->toggle()
            ->when()->toggle->log('six');

        $this->assertSame(['init', 'one', 'three', 'six'], $logger->values);
    }

    public function testUnlessProxy()
    {
        // With static condition
        $logger = (new ConditionableLogger())
            ->unless(true)->log('one')
            ->unless(false)->log('two');

        $this->assertSame(['two'], $logger->values);

        // With callback condition
        $logger = (new ConditionableLogger())->log('init')
            ->unless(function ($logger) {
                return $logger->has('init');
            })
            ->log('one')
            ->unless(function ($logger) {
                return $logger->has('missing');
            })
            ->log('two')
            ->unless()->has('init')->log('three')
            ->unless()->has('missing')->log('four')
            ->unless()->toggle->log('five')
            ->toggle()
            ->unless()->toggle->log('six');

        $this->assertSame(['init', 'two', 'four', 'five'], $logger->values);
    }
}

class ConditionableLogger
{
    use Conditionable;

    public $values = [];

    public $toggle = false;

    public function log(...$values)
    {
        array_push($this->values, ...$values);

        return $this;
    }

    public function has($value)
    {
        return in_array($value, $this->values);
    }

    public function toggle()
    {
        $this->toggle = ! $this->toggle;

        return $this;
    }
}
