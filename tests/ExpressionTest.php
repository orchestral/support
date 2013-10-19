<?php namespace Orchestra\Support\Tests;

use Orchestra\Support\Expression;

class ExpressionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test constructing Orchestra\Support\Expression
     *
     * @test
     */
    public function testConstructMethod()
    {
        $expected = "foobar";
        $actual   = new Expression($expected);

        $this->assertInstanceOf('\Orchestra\Support\Expression', $actual);
        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected, $actual->get());
    }
}
