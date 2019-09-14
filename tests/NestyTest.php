<?php

namespace Orchestra\Support\Tests;

use Orchestra\Support\Collection;
use Orchestra\Support\Fluent;
use Orchestra\Support\Nesty;
use PHPUnit\Framework\TestCase;

class NestyTest extends TestCase
{
    /**
     * Stub instance.
     *
     * @var Orchestra\Support\Nesty
     */
    private $stub = null;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        $this->stub = new Nesty([]);
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        unset($this->stub);
    }

    /** @test */
    public function instance_has_proper_signature()
    {
        $this->assertInstanceOf('Orchestra\Support\Nesty', $this->stub);

        $refl = new \ReflectionObject($this->stub);
        $config = $refl->getProperty('config');
        $config->setAccessible(true);

        $this->assertEquals([], $config->getValue($this->stub));
    }

    /** @test */
    public function it_can_return_new_instance_with_empty_array()
    {
        $this->assertEquals(new Collection([]), (new Nesty([]))->items());
    }

    /** @test */
    public function it_can_add_childs()
    {
        $foobar = new Fluent([
            'id' => 'foobar',
            'childs' => [
                'hello-foobar' => new Fluent([
                    'id' => 'hello-foobar',
                    'childs' => [],
                ]),
            ],
        ]);

        $foo = new Fluent([
            'id' => 'foo',
            'childs' => [
                'bar' => new Fluent([
                    'id' => 'bar',
                    'childs' => [],
                ]),
                'foobar' => $foobar,
                'foo-bar' => new Fluent([
                    'id' => 'foo-bar',
                    'childs' => [],
                ]),
                'hello-world-foobar' => new Fluent([
                    'id' => 'hello-world-foobar',
                    'childs' => [],
                ]),
            ],
        ]);

        $expected = [
            'crynobone' => new Fluent([
                'id' => 'crynobone',
                'childs' => [],
            ]),
            'hello' => new Fluent([
                'id' => 'hello',
                'childs' => [],
            ]),
            'world' => new Fluent([
                'id' => 'world',
                'childs' => [],
            ]),
            'foo' => $foo,
            'orchestra' => new Fluent([
                'id' => 'orchestra',
                'childs' => [],
            ]),
        ];

        $this->stub->add('foo');
        $this->stub->add('hello', '<:foo');
        $this->stub->add('world', '>:hello');
        $this->stub->add('bar', '^:foo');
        $this->stub->add('foobar', '^:foo');
        $this->stub->add('foo-bar', '^:foo');
        $this->stub->add('hello-foobar', '^:foo.foobar');
        $this->stub->add('hello-world-foobar', '^:foo.dummy');
        $this->stub->add('crynobone', '<');
        $this->stub->add('orchestra', '#');

        $this->assertEquals(new Collection($expected), $this->stub->items());
        $this->assertEquals($expected, $this->stub->is(null));
        $this->assertEquals($foo, $this->stub->is('foo'));
        $this->assertEquals($foobar, $this->stub->is('foo.foobar'));
        $this->assertNull($this->stub->is('foobar'));

        $this->assertTrue($this->stub->has('foo'));
        $this->assertTrue($this->stub->has('foo.foobar'));
        $this->assertFalse($this->stub->has('foo.foo'));
        $this->assertFalse($this->stub->has('bar'));
    }

    /** @test */
    public function it_can_add_child_before_selected_node()
    {
        $stub = new Nesty([]);

        $expected = [
            'foobar' => new Fluent([
                'id' => 'foobar',
                'childs' => [],
            ]),
            'foo' => new Fluent([
                'id' => 'foo',
                'childs' => [],
            ]),
        ];

        $stub->add('foo', '<:home');
        $stub->add('foobar', '<:foo');

        $this->assertEquals(new Collection($expected), $stub->items());
    }

    /** @test */
    public function it_can_add_child_after_selected_node()
    {
        $stub = new Nesty([]);

        $expected = [
            'foobar' => new Fluent([
                'id' => 'foobar',
                'childs' => [],
            ]),
            'foo' => new Fluent([
                'id' => 'foo',
                'childs' => [],
            ]),
        ];

        $stub->add('foobar', '>:home');
        $stub->add('foo', '>:foobar');

        $this->assertEquals(new Collection($expected), $stub->items());
    }

    /** @test */
    public function it_can_add_child_when_decendant_is_not_available()
    {
        $stub = new Nesty([]);

        $stub->add('foo', '^:home');

        $this->assertEquals(new Collection([]), $stub->items());
    }
}
