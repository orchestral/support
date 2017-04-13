<?php

namespace Orchestra\Support\TestCase;

use PHPUnit\Framework\TestCase;
use Orchestra\Support\Expression;

class ExpressionTest extends TestCase
{
    /**
     * Test constructing Orchestra\Support\Expression.
     *
     * @test
     */
    public function testConstructMethod()
    {
        $expected = "foobar";
        $actual = new Expression($expected);

        $this->assertInstanceOf('\Orchestra\Support\Expression', $actual);
        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected, $actual->get());
    }
}
