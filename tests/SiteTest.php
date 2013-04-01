<?php namespace Orchestra\Support\Tests;

class SiteTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$appMock = \Mockery::mock('Application')
			->shouldReceive('instance')->andReturn(true);

		$configMock = \Mockery::mock('Config')
			->shouldReceive('get')
				->with('app.timezone', 'UTC')->andReturn('UTC');

		\Illuminate\Support\Facades\Auth::setFacadeApplication($appMock->getMock());
		\Illuminate\Support\Facades\Config::setFacadeApplication($appMock->getMock());
		\Illuminate\Support\Facades\Config::swap($configMock->getMock());

		\Orchestra\Support\Site::$items = array();
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Orchestra\Support\Site::$items = array();
		\Mockery::close();		
	}

	/**
	 * Test Orchestra\Support\Site::get() method.
	 *
	 * @test
	 * @group support
	 */
	public function testGetMethod()
	{
		\Orchestra\Support\Site::$items = array(
			'title'       => 'Hello World',
			'description' => 'Just another Hello World'
		);

		$this->assertEquals('Hello World', \Orchestra\Support\Site::get('title'));
		$this->assertNull(\Orchestra\Support\Site::get('title.foo'));
	}

	/**
	 * Test Orchestra\Support\Site::set() method.
	 *
	 * @test
	 * @group support
	 */
	public function testSetMethod()
	{
		\Orchestra\Support\Site::$items = array();

		\Orchestra\Support\Site::set('title', 'Foo');
		\Orchestra\Support\Site::set('foo.bar', 'Foobar');

		$this->assertEquals(array(
			'title' => 'Foo',
			'foo'   => array(
				'bar' => 'Foobar',
			),
		), \Orchestra\Support\Site::$items);
	}

	/**
	 * Test Orchestra\Support\Site::has() method.
	 *
	 * @test
	 * @group support
	 */
	public function testHasMethod()
	{
		\Orchestra\Support\Site::$items = array(
			'title'       => 'Hello World',
			'description' => 'Just another Hello World',
			'hello'       => null,
		);

		$this->assertTrue(\Orchestra\Support\Site::has('title'));
		$this->assertFalse(\Orchestra\Support\Site::has('title.foo'));
		$this->assertFalse(\Orchestra\Support\Site::has('hello'));
	}

	/**
	 * Test Orchestra\Support\Site::forget() method.
	 *
	 * @test
	 * @group support
	 */
	public function testForgetMethod()
	{
		\Orchestra\Support\Site::$items = array(
			'title'       => 'Hello World',
			'description' => 'Just another Hello World',
			'hello'       => null,
			'foo'         => array(
				'hello' => 'foo',
				'bar'   => 'foobar',
			),
		);

		\Orchestra\Support\Site::forget('title');
		\Orchestra\Support\Site::forget('hello');
		\Orchestra\Support\Site::forget('foo.bar');

		$this->assertFalse(\Orchestra\Support\Site::has('title'));
		$this->assertTrue(\Orchestra\Support\Site::has('description'));
		$this->assertFalse(\Orchestra\Support\Site::has('hello'));
		$this->assertEquals(array('hello' => 'foo'), \Orchestra\Support\Site::get('foo'));
	}

	/**
	 * Test localtime() return proper datetime when is guest.
	 *
	 * @test
	 * @group support
	 */
	public function testLocalTimeReturnProperDateTimeWhenIsGuest()
	{
		$authMock = \Mockery::mock('Illuminate\Auth\Guard')
			->shouldReceive('guest')->andReturn(true);
		\Illuminate\Support\Facades\Auth::swap($authMock->getMock());

		$this->assertEquals(new \DateTimeZone('UTC'),
				\Orchestra\Support\Site::localtime('2012-01-01 00:00:00')->getTimezone());
	}

	/**
	 * Test localtime() return proper datetime when is user.
	 *
	 * @test
	 * @group support
	 */
	public function testLocalTimeReturnProperDateTimeWhenIsUser()
	{
		$date = new \DateTime('2012-01-01 00:00:00');
		$userMock = \Mockery::mock('Orchestra\Model\User')
			->shouldReceive('localtime')->with($date)
			->andReturn($date->setTimeZone(new \DateTimeZone('Asia/Kuala_Lumpur')));

		$authMock = \Mockery::mock('Illuminate\Auth\Guard')
			->shouldReceive('guest')->andReturn(false)
			->shouldReceive('user')->andReturn($userMock->getMock());
		\Illuminate\Support\Facades\Auth::swap($authMock->getMock());

		$this->assertEquals(new \DateTimeZone('Asia/Kuala_Lumpur'),
				\Orchestra\Support\Site::localtime($date)->getTimezone());
	}
}