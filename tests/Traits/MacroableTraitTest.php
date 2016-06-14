<?php

namespace Orchestra\Support\TestCase\Traits;

use Orchestra\Support\Traits\MacroableTrait;

class MacroableTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test \Orchestra\Support\Traits\MacroableTrait is executable.
     *
     * @test
     */
    public function testMacroIsExecutable()
    {
        $stub = new MacroableStub();

        $stub->macro('foo', function () {
            return 'foobar';
        });

        $this->assertEquals('foobar', $stub->foo());
    }

    /**
     * Test \Orchestra\Support\Traits\MacroableTrait throws an exception
     * when macro is not executable.
     *
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Method foo does not exist.
     */
    public function testMacroThrowsExceptionWhenMacroIsntExecutable()
    {
        with(new MacroableStub())->foo();
    }
}

class MacroableStub
{
    use MacroableTrait;
}
