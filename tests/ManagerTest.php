<?php

namespace Orchestra\Support\TestCase;

use Mockery as m;
use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    /** @test */
    public function manager_can_be_extended()
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
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function invalid_driver_should_throw_exception()
    {
        (new ManagerStub(m::mock('\Illuminate\Foundation\Application')))->driver('invalidDriver');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function invalid_driver_with_dotted_should_throw_exception()
    {
        (new ManagerStub(m::mock('\Illuminate\Foundation\Application')))
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
