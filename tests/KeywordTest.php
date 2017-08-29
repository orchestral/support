<?php

namespace Orchestra\Support\TestCase;

use Orchestra\Support\Keyword;
use PHPUnit\Framework\TestCase;

class KeywordTest extends TestCase
{
    /** @test */
    function it_can_be_used()
    {
        $stub1 = Keyword::make('Hello World');

        $this->assertEquals('hello-world', $stub1->getSlug());
        $this->assertEquals('hello-world', (string) $stub1);
        $this->assertEquals('Hello World', $stub1->getValue());
        $this->assertEquals(1, $stub1->searchIn(['foo', 'hello-world']));
        $this->assertFalse($stub1->searchIn(['foo', 'bar']));

        $stub2 = Keyword::make(5);

        $this->assertNull($stub2->getSlug());
        $this->assertEmpty((string) $stub2);
        $this->assertEquals(5, $stub2->getValue());
        $this->assertEquals(4, $stub2->searchIn(['hello', 'world', 'foo', 'bar', 5, 'foobar']));
    }

    /** @test */
    function it_can_return_self_when_given_same_type()
    {
        $keyword = new Keyword('hello');

        $stub = Keyword::make($keyword);

        $this->assertEquals($keyword, $stub);
    }
}
