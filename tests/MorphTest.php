<?php namespace Orchestra\Support\Tests;

class MorphTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Test MorphStub::connect() return foo_connect().
	 * 
	 * @test
	 * @group support
	 */
	public function testStubFooConnect()
	{
		$this->assertTrue(MorphStub::connect());
	}

	/**
	 * Test MorphStub::invalid() throws an Exception.
	 *
	 * @expectedException \RuntimeException
	 * @group support
	 */
	public function testStubFooInvalidThrowsException()
	{
		MorphStub::invalid();
	}
}

function foo_connect()
{
	return true;
}

class MorphStub extends \Orchestra\Support\Morph {

	public static $prefix = '\Orchestra\Support\Tests\foo_';

}
