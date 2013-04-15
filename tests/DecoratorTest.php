<?php namespace Orchestra\Support\Tests;

class DecoratorTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test add and using macros.
	 *
	 * @test
	 * @group support
	 */
	public function testAddAndUsingMacros()
	{
		$stub = new \Orchestra\Support\Decorator;
		
		$stub->macro('foo', function ()
		{
			return 'foo';
		});

		$this->assertEquals('foo', $stub->foo());
	}

	/**
	 * Test calling undefined macros throws an exception.
	 *
	 * @expectedException \BadMethodCallException
	 * @group support
	 */
	public function testCallingUndefinedMacrosThrowsException()
	{
		with(new \Orchestra\Support\Decorator)->foobar();
	}
}