<?php

namespace Orchestra\Support\TestCase;

use Orchestra\Support\Arr;

class ArrTest extends \PHPUnit_Framework_TestCase
{
    public function testExpand()
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
    public function testExpandWithDepth()
    {
        // Without specifying depth it expands recursively.
        $array = Arr::expand(['foo.bar.baz' => 'baz-value']);
        $this->assertEquals(['foo' => ['bar' => ['baz' => 'baz-value']]], $array);

        $array = Arr::expand(['foo.bar.baz.bizz' => 'baz-value'], 1);
        $this->assertEquals(['foo' => ['bar.baz.bizz' => 'baz-value']], $array);

        $array = Arr::expand(['foo.bar.baz.bizz' => 'baz-value'], 2);
        $this->assertEquals(['foo' => ['bar' => ['baz.bizz' => 'baz-value']]], $array);

        $array = Arr::expand(['foo.bar.baz.bizz' => 'baz-value'], 3);
        $this->assertEquals(['foo' => ['bar' => ['baz' => ['bizz' => 'baz-value']]]], $array);

        $array = Arr::expand(['foo.bar' => 'baz', 'foo.bar1.bizz' => 'baz1', 'foo2' => 'bar2'], 2);
        $this->assertEquals(['foo' => ['bar' => 'baz', 'bar1' => ['bizz' => 'baz1']], 'foo2' => 'bar2'], $array);

        $array = Arr::expand(['foo.bar' => 'baz', 'foo.bar1.bizz' => 'baz1', 'foo2' => 'bar2'], 1);
        $this->assertEquals(['foo' => ['bar' => 'baz', 'bar1.bizz' => 'baz1'], 'foo2' => 'bar2'], $array);
    }
}
