<?php namespace Orchestra\Support\Tests;

use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Orchestra\Support\Nesty;

class NestyTest extends \PHPUnit_Framework_TestCase
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
    public function setUp()
    {
        $this->stub = new Nesty(array());
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
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

        $refl   = new \ReflectionObject($this->stub);
        $config = $refl->getProperty('config');
        $config->setAccessible(true);

        $this->assertEquals(array(), $config->getValue($this->stub));
    }

    /**
     * Get newly instantiated Orchestra\Support\Nesty::get() return empty
     * string.
     *
     * @test
     */
    public function testNewInstanceReturnEmptyArray()
    {
        $this->assertEquals(new Collection(array()), with(new Nesty(array()))->getItems());
    }

    /**
     * Test adding an item to Orchestra\Support\Nesty.
     *
     * @test
     */
    public function testAddMethod()
    {
        $foobar = new Fluent(array(
            'id'     => 'foobar',
            'childs' => array(
                'hello-foobar' => new Fluent(array(
                    'id'     => 'hello-foobar',
                    'childs' => array(),
                )),
            ),
        ));
        $foo = new Fluent(array(
            'id'     => 'foo',
            'childs' => array(
                'bar' => new Fluent(array(
                    'id'     => 'bar',
                    'childs' => array(),
                )),
                'foobar' => $foobar,
                'foo-bar' => new Fluent(array(
                    'id'     => 'foo-bar',
                    'childs' => array(),
                )),
                'hello-world-foobar' => new Fluent(array(
                    'id'     => 'hello-world-foobar',
                    'childs' => array(),
                )),
            )
        ));

        $expected = array(
            'crynobone' => new Fluent(array(
                'id'     => 'crynobone',
                'childs' => array(),
            )),
            'hello' => new Fluent(array(
                'id'     => 'hello',
                'childs' => array(),
            )),
            'world' => new Fluent(array(
                'id'     => 'world',
                'childs' => array(),
            )),
            'foo' => $foo,
            'orchestra' => new Fluent(array(
                'id'     => 'orchestra',
                'childs' => array(),
            )),
        );

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

        $this->assertEquals(new Collection($expected), $this->stub->getItems());
        $this->assertEquals($expected, $this->stub->is(null));
        $this->assertEquals($foo, $this->stub->is('foo'));
        $this->assertEquals($foobar, $this->stub->is('foo.foobar'));
        $this->assertNull($this->stub->is('foobar'));
    }

    /**
     * Test Orchestra\Support\Nesty::addBefore() method.
     */
    public function testAddBeforeMethod()
    {
        $stub = new Nesty(array());

        $expected = array(
            'foobar' => new Fluent(array(
                'id'     => 'foobar',
                'childs' => array(),
            )),
            'foo' => new Fluent(array(
                'id'     => 'foo',
                'childs' => array(),
            )),
        );

        $stub->add('foo', '<:home');
        $stub->add('foobar', '<:foo');

        $this->assertEquals(new Collection($expected), $stub->getItems());
    }

    /**
     * Test Orchestra\Support\Nesty::addAfter() method.
     */
    public function testAddAfterMethod()
    {
        $stub = new Nesty(array());

        $expected = array(
            'foobar' => new Fluent(array(
                'id'     => 'foobar',
                'childs' => array(),
            )),
            'foo' => new Fluent(array(
                'id'     => 'foo',
                'childs' => array(),
            )),
        );

        $stub->add('foobar', '>:home');
        $stub->add('foo', '>:foobar');

        $this->assertEquals(new Collection($expected), $stub->getItems());
    }

    /**
     * Test adding an item to Orchestra\Support\Nesty when decendant is not
     * presented.
     *
     * @test
     */
    public function testAddMethodWhenDecendantIsNotPresented()
    {
        $stub = new Nesty(array());

        $stub->add('foo', '^:home');
        $this->assertEquals(new Collection(array()), $stub->getItems());
    }
}
