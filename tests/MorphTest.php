<?php

namespace Orchestra\Support\TestCase;

use Orchestra\Support\Morph;
use PHPUnit\Framework\TestCase;

class MorphTest extends TestCase
{
    /** @test */
    public function it_can_be_called()
    {
        $this->assertTrue(MorphStub::connect());
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function invalid_morph_should_throw_exception()
    {
        MorphStub::invalid();
    }
}

function foo_connect()
{
    return true;
}

class MorphStub extends Morph
{
    public static $prefix = '\Orchestra\Support\TestCase\foo_';
}
