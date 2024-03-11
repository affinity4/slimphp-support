<?php declare(strict_types=1);

namespace Affinity4\SlimSupport\Tests\Collections;

use PHPUnit\Framework\TestCase;

final class HelperFunctionsTest extends TestCase
{
    public function testValue(): void
    {
        $this->assertSame(1, value(function () {
            return 1;
        }));
        $this->assertSame(1, value(1));
        $this->assertSame(1, value(1, 2));
    }
}
