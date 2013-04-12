<?php namespace Orchestra\Support\Tests;

class ManagerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test Orchestra\Support\Manager::driver() method.
	 *
	 * @test
	 */
	public function testDriverMethod()
	{
		$stub = new ManagerStub(\Mockery::mock('\Illuminate\Foundation\Application'));
		$stub->extend('awesome', function ($app, $name)
		{
			return new Manager_AwesomeFoobar($app, $name);
		});

		$output1 = $stub->driver('foo.bar');
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
		$stub = new ManagerStub(\Mockery::mock('\Illuminate\Foundation\Application'));

		$stub->driver('invalidDriver');
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