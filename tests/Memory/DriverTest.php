<?php namespace Orchestra\Support\Tests\Memory;

class DriverTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test Orchestra\Support\Memory\Driver::initiate()
	 *
	 * @test
	 * @group support
	 */
	public function testInitiateMethod()
	{
		$stub = new MemoryDriverStub;
		$this->assertTrue($stub->initiated);
	}

	/**
	 * Test Orchestra\Support\Memory\Driver::shutdown()
	 *
	 * @test
	 * @group support
	 */
	public function testShutdownMethod()
	{
		$stub = new MemoryDriverStub;
		$this->assertFalse($stub->shutdown);
		$stub->shutdown();
		$this->assertTrue($stub->shutdown);
	}

	/**
	 * Test Orchestra\Support\Memory\Driver::stringify() method.
	 *
	 * @test
	 * @group support
	 */
	public function testStringifyMethod()
	{
		$stub     = new MemoryDriverStub;
		$expected = 'foobar';
		$this->assertEquals($expected, $stub->stringify($expected));
	}

	/**
	 * Test Orchestra\Support\Memory\Driver::get() method.
	 *
	 * @test
	 * @group support
	 */
	public function testGetMethod()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test Orchestra\Support\Memory\Driver::put() method.
	 *
	 * @test
	 * @group support
	 */
	public function testPutMethod()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test Orchestra\Support\Memory\Driver::forget() method.
	 *
	 * @test
	 * @group support
	 */
	public function testForgetMethod()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}

class MemoryDriverStub extends \Orchestra\Support\Memory\Driver {

	public $initiated = false;
	public $shutdown  = false;

	public function initiate() 
	{
		$this->initiated = true;
	}

	public function shutdown() 
	{
		$this->shutdown = true;
	}
}