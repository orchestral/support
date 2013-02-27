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