<?php namespace Orchestra\Support\Tests;

class MessagesTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		\Illuminate\Support\Facades\Session::setFacadeApplication(
			$app = \Mockery::mock('\Illuminate\Foundation\Application')
		);

		$app->shouldReceive('instance')->andReturn(true);
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
		$message = new \Orchestra\Support\Messages;
		$message->add('welcome', 'Hello world');
		$message->setFormat();

		$this->assertInstanceOf('\Orchestra\Support\Messages', $message);
		$this->assertEquals(array('Hello world'), $message->get('welcome'));

		$message->add('welcome', 'Hi Foobar');
		$this->assertEquals(array('Hello world', 'Hi Foobar'), $message->get('welcome'));
	}
		
	/**
	 * Test Orchestra\Support\Messages::shutdown()
	 *
	 * @test
	 * @group support
	 */
	public function testShutdownMethod()
	{
		$stub = new \Orchestra\Support\Messages;

		\Illuminate\Support\Facades\Session::swap($session = \Mockery::mock('Session'));
		$session->shouldReceive('put')->once()->andReturn(true);

		$stub->shutdown();
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
		\Illuminate\Support\Facades\Session::swap($session = \Mockery::mock('Session'));
		$session->shouldReceive('put')->once()->andReturn(true);
		
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
		\Illuminate\Support\Facades\Session::swap($session = \Mockery::mock('Session'));
		$session->shouldReceive('has')->once()->andReturn(true)
			->shouldReceive('get')->once()->andReturn('a:2:{s:5:"hello";a:1:{i:0;s:8:"Hi World";}s:3:"bye";a:1:{i:0;s:7:"Goodbye";}}')
			->shouldReceive('forget')->once()->andReturn(true);

		$retrieve = with(new \Orchestra\Support\Messages)->retrieve();
		$retrieve->setFormat();

		$this->assertInstanceOf('\Orchestra\Support\Messages', $retrieve);
		$this->assertEquals(array('Hi World'), $retrieve->get('hello'));
		$this->assertEquals(array('Goodbye'), $retrieve->get('bye'));
	}
}