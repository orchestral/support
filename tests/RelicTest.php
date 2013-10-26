<?php namespace Orchestra\Support\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Support\Relic;

class RelicTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Site::get() method.
     *
     * @test
     * @group support
     */
    public function testGetMethod()
    {
        $stub = new Relic;

        $refl = new \ReflectionObject($stub);
        $items = $refl->getProperty('items');
        $items->setAccessible(true);
        $items->setValue($stub, array(
            'title'       => 'Hello World',
            'description' => 'Just another Hello World',
        ));

        $this->assertEquals('Hello World', $stub->get('title'));
        $this->assertNull($stub->get('title.foo'));
    }

    /**
     * Test Orchestra\Foundation\Site::set() method.
     *
     * @test
     * @group support
     */
    public function testSetMethod()
    {
        $stub = new Relic;
        $stub->set('title', 'Foo');
        $stub->set('foo.bar', 'Foobar');

        $expected = array('title' => 'Foo', 'foo' => array('bar' => 'Foobar'));
        $this->assertEquals($expected, $stub->all());
    }

    /**
     * Test Orchestra\Foundation\Site::has() method.
     *
     * @test
     * @group support
     */
    public function testHasMethod()
    {
        $stub = new Relic;

        $refl = new \ReflectionObject($stub);
        $items = $refl->getProperty('items');
        $items->setAccessible(true);
        $items->setValue($stub, array(
            'title'       => 'Hello World',
            'description' => 'Just another Hello World',
            'hello'       => null,
        ));

        $this->assertTrue($stub->has('title'));
        $this->assertFalse($stub->has('title.foo'));
        $this->assertFalse($stub->has('hello'));
    }

    /**
     * Test Orchestra\Foundation\Site::forget() method.
     *
     * @test
     * @group support
     */
    public function testForgetMethod()
    {
        $stub = new Relic;

        $refl = new \ReflectionObject($stub);
        $items = $refl->getProperty('items');
        $items->setAccessible(true);
        $items->setValue($stub, array(
            'title'       => 'Hello World',
            'description' => 'Just another Hello World',
            'hello'       => null,
            'foo'         => array(
                'hello' => 'foo',
                'bar'   => 'foobar',
            ),
        ));

        $stub->forget('title');
        $stub->forget('hello');
        $stub->forget('foo.bar');

        $this->assertFalse($stub->has('title'));
        $this->assertTrue($stub->has('description'));
        $this->assertFalse($stub->has('hello'));
        $this->assertEquals(array('hello' => 'foo'), $stub->get('foo'));
    }
}
