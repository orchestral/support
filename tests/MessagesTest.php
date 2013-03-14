<?php namespace Orchestra\Support\Tests;

class MessagesTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$appMock = \Mockery::mock('Application')
			->shouldReceive('instance')->andReturn(true);
		\Illuminate\Support\Facades\Session::setFacadeApplication(
			$appMock->getMock()
		);
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * Test Orchestra\Support\Messages::make()
	 *
	 * @test
	 * @group support
	 */
	public function testMakeMethod()
	{
		$message = \Orchestra\Support\Messages::make();
		$message->add('welcome', 'Hello world');
		$message->setFormat();

		$this->assertInstanceOf('\Orchestra\Support\Messages', $message);
		$this->assertEquals(array('Hello world'), $message->get('welcome'));

		$message->add('welcome', 'Hi Foobar');
		$this->assertEquals(array('Hello world', 'Hi Foobar'), $message->get('welcome'));
		$this->assertTrue($message === \Orchestra\Support\Messages::make());
	}
		
	/**
	 * Test Orchestra\Support\Messages::shutdown()
	 *
	 * @test
	 * @group support
	 */
	public function testShutdownMethod()
	{
		\Orchestra\Support\Messages::make();

		$mock = \Mockery::mock('Session')
			->shouldReceive('flash')->once()->andReturn(true);

		\Illuminate\Support\Facades\Session::swap($mock->getMock());

		\Orchestra\Support\Messages::shutdown();
	}

	/**
	 * Test serializing and storing Orchestra\Support\Messages over
	 * Session
	 *
	 * @test
	 * @group support
	 */
	public function testStoreMethod()
	{
		$mock = \Mockery::mock('Session')
			->shouldReceive('flash')->once()->andReturn(true);

		\Illuminate\Support\Facades\Session::swap($mock->getMock());

		$message = new \Orchestra\Support\Messages;
		$message->add('hello', 'Hi World');
		$message->add('bye', 'Goodbye');

		$serialize = $message->serialize();

		$this->assertTrue(is_string($serialize));
		$this->assertContains('hello', $serialize);
		$this->assertContains('Hi World', $serialize);
		$this->assertContains('bye', $serialize);
		$this->assertContains('Goodbye', $serialize);

		$message->save();
	}

	/**
	 * Test un-serializing and retrieving Orchestra\Support\Messages over
	 * Session
	 *
	 * @test
	 * @group support
	 */
	public function testGetSessionFlashMethod()
	{
		$mock = \Mockery::mock('Session')
			->shouldReceive('has')->once()->andReturn(true)
			->shouldReceive('getFlash')->once()->andReturn(
				'a:2:{s:5:"hello";a:1:{i:0;s:8:"Hi World";}s:3:"bye";a:1:{i:0;s:7:"Goodbye";}}'
			)->shouldReceive('forget')->once()->andReturn(true);

		\Illuminate\Support\Facades\Session::swap($mock->getMock());

		$retrieve = \Orchestra\Support\Messages::retrieve();
		$retrieve->setFormat();

		$this->assertInstanceOf('\Orchestra\Support\Messages', $retrieve);
		$this->assertEquals(array('Hi World'), $retrieve->get('hello'));
		$this->assertEquals(array('Goodbye'), $retrieve->get('bye'));
	}
}