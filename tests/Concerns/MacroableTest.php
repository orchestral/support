<?php

namespace Orchestra\Support\Tests\Concerns;

use PHPUnit\Framework\TestCase;
use Orchestra\Support\Concerns\Macroable;

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

    /** @test */
    public function it_cant_be_executed_should_throw_exception()
    {
        $this->expectException('BadMethodCallException');
        $this->expectExceptionMessage('Method foo does not exist.');

        (new class() {
            use Macroable;
        })->foo();
    }
}
