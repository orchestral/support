<?php namespace Orchestra\Support\Tests;

use Mockery as m;

class ManagerTest extends \PHPUnit_Framework_TestCase {

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
		$stub->extend('awesome', function ($app, $name)
		{
			return new Manager_AwesomeFoobar($app, $name);
		});

		$output1 = $stub->make('foo.bar');
		$output2 = $stub->driver('foo.bar');
		$output3 = $stub->driver('foo');
		$output4 = $stub->driver('foobar.daylerees');
		$output5 = $stub->driver('awesome.taylor');

		$this->assertInstanceOf('\Orchestra\Support\Tests\Manager_Foo', $output1);
		$this->assertEquals('bar', $output1->name);
		$this->assertEquals($output1, $output2);
		$this->assertEquals('default', $output3->name);
		$this->assertNotEquals($output2, $output3);
		$this->assertInstanceOf('\Orchestra\Support\Tests\Manager_Foobar', $output4);
		$this->assertEquals('daylerees', $output4->name);
		$this->assertInstanceOf('\Orchestra\Support\Tests\Manager_AwesomeFoobar', $output5);
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

class Manager_Foo {

	public $name = null;

	public function __construct($app, $name)
	{
		$this->name = $name;
	}
}

class Manager_Foobar {

	public $name = null;

	public function __construct($app, $name)
	{
		$this->name = $name;
	}
}

class Manager_AwesomeFoobar {

	public $name = null;

	public function __construct($app, $name)
	{
		$this->name = $name;
	}
}

class ManagerStub extends \Orchestra\Support\Manager {

	public function createFooDriver($name)
	{
		return new Manager_Foo($this->app, $name);
	}

	public function createFoobarDriver($name)
	{
		return new Manager_Foobar($this->app, $name);
	}

	public function createDefaultDriver()
	{
		return $this->createFooDriver();
	}
}
