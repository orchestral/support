<?php namespace Orchestra\Support\Tests;

use Mockery as m;
use Orchestra\Support\Messages;

class MessagesTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$app = m::mock('\Illuminate\Foundation\Application');
		$app->shouldReceive('instance')->andReturn(true);

		\Illuminate\Support\Facades\Session::setFacadeApplication($app);
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Support\Messages::make()
	 *
	 * @test
	 */
	public function testMakeMethod()
	{
		$message = new Messages;
		$message->add('welcome', 'Hello world');
		$message->setFormat();

		$this->assertInstanceOf('\Orchestra\Support\Messages', $message);
		$this->assertEquals(array('Hello world'), $message->get('welcome'));

		$message->add('welcome', 'Hi Foobar')->add('welcome', 'Heya Admin');
		$this->assertEquals(array('Hello world', 'Hi Foobar', 'Heya Admin'), $message->get('welcome'));
	}
		
	/**
	 * Test Orchestra\Support\Messages::save()
	 *
	 * @test
	 */
	public function testSaveMethod()
	{
		$session = m::mock('Session');
		$session->shouldReceive('flash')->once()->andReturn(true);

		\Illuminate\Support\Facades\Session::swap($session);

		with(new Messages)->save();
	}

	/**
	 * Test serializing and storing Orchestra\Support\Messages over
	 * Session
	 *
	 * @test
	 */
	public function testStoreMethod()
	{
		$session = m::mock('Session');
		$session->shouldReceive('flash')->once()->andReturn(true);		
		\Illuminate\Support\Facades\Session::swap($session);
		
		$message = new Messages;
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
	 */
	public function testRetrieveMethod()
	{
		$session = m::mock('Session');
		$session->shouldReceive('has')->once()->andReturn(true)
			->shouldReceive('get')->once()->andReturn('a:2:{s:5:"hello";a:1:{i:0;s:8:"Hi World";}s:3:"bye";a:1:{i:0;s:7:"Goodbye";}}')
			->shouldReceive('forget')->once()->andReturn(true);

		\Illuminate\Support\Facades\Session::swap($session);
		
		$retrieve = with(new Messages)->retrieve();
		$retrieve->setFormat();

		$this->assertInstanceOf('\Orchestra\Support\Messages', $retrieve);
		$this->assertEquals(array('Hi World'), $retrieve->get('hello'));
		$this->assertEquals(array('Goodbye'), $retrieve->get('bye'));
	}
}
