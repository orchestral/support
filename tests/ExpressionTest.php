<?php

namespace Orchestra\Support\TestCase;

use PHPUnit\Framework\TestCase;
use Orchestra\Support\Expression;

class ExpressionTest extends TestCase
{
    /** @test */
    function it_can_be_used()
    {
        $expected = "foobar";
        $actual = new Expression($expected);

        $this->assertInstanceOf(Expression::class, $actual);
        $this->assertEquals($expected, $actual);
        $this->assertSame($expected, $actual->get());
    }
}
