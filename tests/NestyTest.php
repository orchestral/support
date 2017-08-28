<?php

namespace Orchestra\Support\TestCase;

use Orchestra\Support\Nesty;
use Orchestra\Support\Fluent;
use PHPUnit\Framework\TestCase;
use Orchestra\Support\Collection;

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
    protected function setUp()
    {
        $this->stub = new Nesty([]);
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        unset($this->stub);
    }

    /**
     * Test instanceof stub.
     *
     * @test
     */
    public function testInstanceOfNesty()
    {
        $this->assertInstanceOf('\Orchestra\Support\Nesty', $this->stub);

        $refl = new \ReflectionObject($this->stub);
        $config = $refl->getProperty('config');
        $config->setAccessible(true);

        $this->assertEquals([], $config->getValue($this->stub));
    }

    /**
     * Get newly instantiated Orchestra\Support\Nesty::get() return empty
     * string.
     *
     * @test
     */
    public function testNewInstanceReturnEmptyArray()
    {
        $this->assertEquals(new Collection([]), with(new Nesty([]))->items());
    }

    /**
     * Test adding an item to Orchestra\Support\Nesty.
     *
     * @test
     */
    public function testAddMethod()
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

    /**
     * Test Orchestra\Support\Nesty::addBefore() method.
     */
    public function testAddBeforeMethod()
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

    /**
     * Test Orchestra\Support\Nesty::addAfter() method.
     */
    public function testAddAfterMethod()
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

    /**
     * Test adding an item to Orchestra\Support\Nesty when decendant is not
     * presented.
     *
     * @test
     */
    public function testAddMethodWhenDecendantIsNotPresented()
    {
        $stub = new Nesty([]);

        $stub->add('foo', '^:home');
        $this->assertEquals(new Collection([]), $stub->items());
    }
}
