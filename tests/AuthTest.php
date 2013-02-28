<?php namespace Orchestra\Support\Tests;

class AuthTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$mock = \Mockery::mock('Application')
			->shouldReceive('instance')->andReturn(true);
		
		$eventMock = \Mockery::mock('Event')
			->shouldReceive('until')->andReturn(array('admin', 'editor'));
		\Illuminate\Support\Facades\Event::setFacadeApplication(
			$mock->getMock()
		);
		\Illuminate\Support\Facades\Event::swap($eventMock->getMock());

		$userMock     = new \stdClass();
		$userMock->id = 0;

		$authMock = \Mockery::mock('Illuminate\Auth\Guard')
			->shouldReceive('user')->andReturn($userMock);
		\Illuminate\Support\Facades\Auth::setFacadeApplication(
			$mock->getMock()
		);
		\Illuminate\Support\Facades\Auth::swap($authMock->getMock());
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * Test Orchestra\Support\Auth::roles() returning valid roles
	 * 
	 * @test
	 * @group support
	 */
	public function testRolesMethod()
	{
		$expected = array('admin', 'editor');
		$output   = \Orchestra\Support\Auth::roles();

		$this->assertEquals($expected, $output);
	}

	/**
	 * Test Orchestra\Support\Auth::is() returning valid roles
	 * 
	 * @test
	 * @group support
	 */
	public function testIsMethod()
	{
		$this->assertTrue(\Orchestra\Support\Auth::is('admin'));
		$this->assertTrue(\Orchestra\Support\Auth::is('editor'));
		$this->assertFalse(\Orchestra\Support\Auth::is('user'));
	}
}