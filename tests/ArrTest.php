<?php

namespace Orchestra\Support\Tests;

use Orchestra\Support\Arr;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{
    /** @test */
    public function it_can_be_expanded()
    {
        $array = Arr::expand(['foo.bar' => 'baz']);
        $this->assertEquals(['foo' => ['bar' => 'baz']], $array);

        $array = Arr::expand([]);
        $this->assertEquals([], $array);

        $array = Arr::expand(['foo.bar' => 'baz', 'foo1' => 'bar1']);
        $this->assertEquals(['foo' => ['bar' => 'baz'], 'foo1' => 'bar1'], $array);

        $array = Arr::expand(['foo.bar' => 'baz', 'foo.bar1' => 'baz1', 'foo2' => 'bar2']);
        $this->assertEquals(['foo' => ['bar' => 'baz', 'bar1' => 'baz1'], 'foo2' => 'bar2'], $array);
    }
}
