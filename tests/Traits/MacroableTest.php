<?php

namespace Orchestra\Support\TestCase\Traits;

use PHPUnit\Framework\TestCase;
use Orchestra\Support\Traits\Macroable;

class MacroableTest extends TestCase
{
    /** @test */
    public function it_can_be_executed()
    {
        $stub = new class() {
            use Macroable;
        };

        $stub->macro('foo', function () {
            return 'foobar';
        });

        $this->assertEquals('foobar', $stub->foo());
    }

    /**
     * @test
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Method foo does not exist.
     */
    public function it_cant_be_executed_should_throw_exception()
    {
        (new class() {
            use Macroable;
        })->foo();
    }
}
