<?php namespace Orchestra\Support\TestCase;

use Mockery as m;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Support\Manager::driver() method.
     *
     * @test
     */
    public function testDriverMethod()
    {
        $stub = new ManagerStub(m::mock('\Illuminate\Foundation\Application'));
        $stub->extend('awesome', function ($app, $name) {
            return new ManagerAwesomeFoobar($app, $name);
        });

        $output1 = $stub->make('foo.bar');
        $output2 = $stub->driver('foo.bar');
        $output3 = $stub->driver('foo');
        $output4 = $stub->driver('foobar.daylerees');
        $output5 = $stub->driver('awesome.taylor');

        $this->assertInstanceOf('\Orchestra\Support\TestCase\ManagerFoo', $output1);
        $this->assertEquals('bar', $output1->name);
        $this->assertEquals($output1, $output2);
        $this->assertEquals('default', $output3->name);
        $this->assertNotEquals($output2, $output3);
        $this->assertInstanceOf('\Orchestra\Support\TestCase\ManagerFoobar', $output4);
        $this->assertEquals('daylerees', $output4->name);
        $this->assertInstanceOf('\Orchestra\Support\TestCase\ManagerAwesomeFoobar', $output5);
        $this->assertEquals('taylor', $output5->name);
    }

    /**
     * Test Orchestra\Support\Manager::driver() throws exception.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testDriverMethodThrowsException()
    {
        with(new ManagerStub(m::mock('\Illuminate\Foundation\Application')))->driver('invalidDriver');
    }

    /**
     * Test Orchestra\Support\Manager::driver() throws exception when name
     * contain dotted.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testDriverMethodGivenNameWithDottedThrowsException()
    {
        with(new ManagerStub(m::mock('\Illuminate\Foundation\Application')))
            ->driver('foo.bar.hello');
    }
}

class ManagerFoo
{
    public $name = null;

    public function __construct($app, $name)
    {
        $this->name = $name;
    }
}

class ManagerFoobar
{
    public $name = null;

    public function __construct($app, $name)
    {
        $this->name = $name;
    }
}

class ManagerAwesomeFoobar
{
    public $name = null;

    public function __construct($app, $name)
    {
        $this->name = $name;
    }
}

class ManagerStub extends \Orchestra\Support\Manager
{
    public function createFooDriver($name)
    {
        return new ManagerFoo($this->app, $name);
    }

    public function createFoobarDriver($name)
    {
        return new ManagerFoobar($this->app, $name);
    }

    public function getDefaultDriver()
    {
        return 'foo';
    }
}
