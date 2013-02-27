<?php namespace Orchestra\Support\Tests;

class MessageBagTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$mock = \Mockery::mock('Application')
			->shouldReceive('instance')->andReturn(true);
		\Illuminate\Support\Facades\Session::setFacadeApplication(
			$mock->getMock()
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
	 * Test Orchestra\Support\MessageBag::make()
	 *
	 * @test
	 * @group support
	 */
	public function testMakeInstance()
	{
		$messageBag = \Orchestra\Support\MessageBag::make('welcome', 'Hello world');
		$messageBag->setFormat();

		$this->assertInstanceOf('\Orchestra\Support\MessageBag', $messageBag);
		$this->assertEquals(array('Hello world'), $messageBag->get('welcome'));

		$messageBag->add('welcome', 'Hi Foobar');
		$this->assertEquals(array('Hello world', 'Hi Foobar'), $messageBag->get('welcome'));
	}

	/**
	 * Test serializing and storing Orchestra\Support\MessageBag over
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

		$messageBag = new \Orchestra\Support\MessageBag;
		$messageBag->add('hello', 'Hi World');
		$messageBag->add('bye', 'Goodbye');

		$serialize = $messageBag->serialize();

		$this->assertTrue(is_string($serialize));
		$this->assertContains('hello', $serialize);
		$this->assertContains('Hi World', $serialize);
		$this->assertContains('bye', $serialize);
		$this->assertContains('Goodbye', $serialize);

		$messageBag->store();
	}

	/**
	 * Test un-serializing and retrieving Orchestra\Support\MessageBag over
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

		$retrieve = \Orchestra\Support\MessageBag::getSessionFlash();
		$retrieve->setFormat();

		$this->assertInstanceOf('\Orchestra\Support\MessageBag', $retrieve);
		$this->assertEquals(array('Hi World'), $retrieve->get('hello'));
		$this->assertEquals(array('Goodbye'), $retrieve->get('bye'));
	}
}